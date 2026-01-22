<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservaApiController extends Controller
{   
    

    public function store(Request $request)
    {
        $companyId = $request->company_id;
        // 1️⃣ Validação
        $data = $request->validate([          

            // Cliente
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['nullable', 'email'],
            'cpf'          => ['nullable', 'string', 'max:20'],
            'rg'           => ['nullable', 'string', 'max:20'],
            'birthday'     => ['nullable', 'date'],
            'whatsapp'     => ['nullable', 'string', 'max:20'],
            'zipcode'      => ['nullable', 'string', 'max:20'],
            'street'       => ['nullable', 'string'],
            'neighborhood' => ['nullable', 'string'],
            'number'       => ['nullable', 'string'],
            'city'         => ['nullable', 'string'],
            'estate'       => ['nullable', 'string'],

            // Reserva
            'apartamento'=> ['nullable', 'string'],
            'checkin'    => ['required', 'date'],
            'checkout'   => ['required', 'date', 'after:checkin'],
            'adultos'    => ['required', 'integer', 'min:1'],
            'criancas'   => ['nullable', 'integer', 'min:0'],
        ]);
        
        // 2️⃣ Criar ou recuperar cliente
        $customer = Customer::firstOrCreate(            
            [
                'company_id'   => $companyId,
                'cpf' => $data['cpf'] ?? null,
                'email' => $data['email'] ?? null,
            ],
            [
                'name'         => $data['name'],
                'rg'           => $data['rg'] ?? null,
                'birthday'     => $data['birthday'] ?? null,
                'whatsapp'     => $data['whatsapp'] ?? null,
                'zipcode'      => $data['zipcode'] ?? null,
                'street'       => $data['street'] ?? null,
                'neighborhood' => $data['neighborhood'] ?? null,
                'number'       => $data['number'] ?? null,
                'city'         => $data['city'] ?? null,
                'estate'       => $data['estate'] ?? null,
                'status'       => 'ativo',
            ]
        );

        // 3️⃣ Criar reserva
        $reservation = Reservation::create([
            'company_id'   => $companyId,
            'customer_id'  => $customer->id,
            'apartamento_texto'  => $data['apartamento'] ?? null,
            'checkin'      => $data['checkin'],
            'checkout'     => $data['checkout'],
            'adultos'      => $data['adultos'],
            'criancas'     => $data['criancas'] ?? 0,
            'codigo'       => strtoupper(Str::random(8)),
            'status'       => 'pendente',
        ]);

        // 4️⃣ Retorno
        return response()->json([
            'success' => true,
            'reservation_id' => $reservation->id,
            'codigo' => $reservation->codigo,
        ], 201);
    }    
}
