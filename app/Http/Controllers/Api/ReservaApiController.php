<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\PreReservaCriada;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ReservaApiController extends Controller
{
    public function store(Request $request)
    {
        $companyId = $request->company_id; // injetado pelo ApiKeyMiddleware (X-API-KEY)

        // 1️⃣ Validação
        $data = $request->validate([

            // Cliente
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'cpf' => ['nullable', 'string', 'max:20'],
            'rg' => ['nullable', 'string', 'max:20'],
            'birthday' => ['nullable', 'date'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'zipcode' => ['nullable', 'string', 'max:20'],
            'street' => ['nullable', 'string'],
            'neighborhood' => ['nullable', 'string'],
            'number' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'estate' => ['nullable', 'string'],

            // Reserva
            'apartamento' => ['nullable', 'string'],
            'checkin' => ['required', 'date_format:d/m/Y'],
            'checkout' => ['required', 'date_format:d/m/Y', 'after:checkin'],
            'adultos' => ['required', 'integer', 'min:1'],
            'criancas' => ['nullable', 'integer', 'min:0'],
            'information' => ['nullable', 'string', 'max:1000'],
        ]);

        // 2️⃣ Criar ou recuperar cliente (dedup por CPF → email, nunca null+null)
        $customer = $this->findOrCreateCustomer((int) $companyId, $data);

        $checkin = Carbon::createFromFormat('d/m/Y', $request->checkin)->format('Y-m-d');
        $checkout = Carbon::createFromFormat('d/m/Y', $request->checkout)->format('Y-m-d');

        // 3️⃣ Criar reserva (um cliente pode ter várias)
        $reservation = Reservation::create([
            'company_id' => $companyId,
            'customer_id' => $customer->id,
            'apartamento_texto' => $data['apartamento'] ?? null,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'adultos' => $data['adultos'],
            'criancas' => $data['criancas'] ?? 0,
            'codigo' => strtoupper(Str::random(8)),
            'status' => 'pendente',
            'information' => $data['information'] ?? null,
        ]);

        // 4️⃣ Notificação via BD (super-admin e admin sempre + managers da empresa)
        $this->notifyNewReservation($reservation);

        // 5️⃣ Retorno
        return response()->json([
            'success' => true,
            'reservation_id' => $reservation->id,
            'customer_id' => $customer->id,
            'codigo' => $reservation->codigo,
        ], 201);
    }

    /**
     * Notificação de nova pré-reserva (canal database):
     * super-admin e admin sempre recebem (mesmo padrão do OcorrenciaForm),
     * managers E colaboradores da empresa da reserva também.
     */
    private function notifyNewReservation(Reservation $reservation): void
    {
        $users = User::role('super-admin')->get()
            ->concat(User::role('admin')->get())
            ->concat(
                User::role(['manager', 'employee'])
                    ->where('company_id', $reservation->company_id)
                    ->get()
            )
            ->unique('id');

        Notification::send($users, new PreReservaCriada($reservation));
    }

    /**
     * Dedup de clientes por empresa:
     * - procura por CPF (documento mais forte) e depois por email;
     * - NUNCA casa com cpf/email nulos (fundiria clientes diferentes num só);
     * - completa apenas campos vazios do cadastro encontrado;
     * - em corrida (dois POSTs simultâneos), refaz a busca após QueryException.
     */
    private function findOrCreateCustomer(int $companyId, array $data): Customer
    {
        $customer = $this->searchCustomer($companyId, $data);

        if ($customer) {
            $this->fillEmptyFields($customer, $companyId, $data);

            return $customer;
        }

        try {
            return Customer::create($this->newCustomerPayload($companyId, $data));
        } catch (QueryException $e) {
            // 🔥 Corrida: outra requisição criou o mesmo cliente entre a busca e o insert
            $customer = $this->searchCustomer($companyId, $data);

            if ($customer) {
                return $customer;
            }

            throw $e;
        }
    }

    private function searchCustomer(int $companyId, array $data): ?Customer
    {
        if (! empty($data['cpf'])) {
            $customer = Customer::where('company_id', $companyId)
                ->where('cpf', $data['cpf'])
                ->first();

            if ($customer) {
                return $customer;
            }
        }

        if (! empty($data['email'])) {
            return Customer::where('company_id', $companyId)
                ->where('email', $data['email'])
                ->first();
        }

        return null;
    }

    /** Completa apenas campos vazios — não sobrescreve dados cadastrais existentes */
    private function fillEmptyFields(Customer $customer, int $companyId, array $data): void
    {
        $dirty = [];

        foreach (['rg', 'birthday', 'whatsapp', 'zipcode', 'street', 'neighborhood', 'number', 'city', 'estate'] as $field) {
            if (empty($customer->{$field}) && ! empty($data[$field])) {
                $dirty[$field] = $data[$field];
            }
        }

        // Email só entra se nenhum outro cliente da empresa já o usar (unicidade por empresa)
        if (empty($customer->email) && ! empty($data['email'])) {
            $taken = Customer::where('company_id', $companyId)
                ->where('email', $data['email'])
                ->where('id', '!=', $customer->id)
                ->exists();

            if (! $taken) {
                $dirty['email'] = $data['email'];
            }
        }

        if ($dirty !== []) {
            $customer->update($dirty);
        }
    }

    private function newCustomerPayload(int $companyId, array $data): array
    {
        return [
            'company_id' => $companyId,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'cpf' => $data['cpf'] ?? null,
            'rg' => $data['rg'] ?? null,
            'birthday' => $data['birthday'] ?? null,
            'whatsapp' => $data['whatsapp'] ?? null,
            'zipcode' => $data['zipcode'] ?? null,
            'street' => $data['street'] ?? null,
            'neighborhood' => $data['neighborhood'] ?? null,
            'number' => $data['number'] ?? null,
            'city' => $data['city'] ?? null,
            'estate' => $data['estate'] ?? null,
            'status' => 'ativo',
        ];
    }
}
