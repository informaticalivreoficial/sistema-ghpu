<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreReservaCleanupTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Company::factory()->create(['id' => 18]);
    }

    private function makeCustomer(): Customer
    {
        return Customer::create([
            'company_id' => 18,
            'name' => 'Cliente Limpeza',
            'email' => 'limpeza@teste.com',
            'status' => 'ativo',
        ]);
    }

    private function makeReserva(Customer $customer, string $codigo, string $checkin): Reservation
    {
        return Reservation::create([
            'company_id' => 18,
            'customer_id' => $customer->id,
            'checkin' => $checkin,
            'checkout' => $checkin,
            'adultos' => 1,
            'criancas' => 0,
            'codigo' => $codigo,
            'status' => 'pendente',
        ]);
    }

    public function test_removes_reservations_with_checkin_older_than_nine_months(): void
    {
        $customer = $this->makeCustomer();

        // 🔹 Mais de 9 meses → removida
        $antiga = $this->makeReserva(
            $customer,
            'OLD001',
            now()->subMonths(10)->format('Y-m-d')
        );

        // 🔹 Dentro do prazo → mantidas
        $dentroDoPrazo = $this->makeReserva(
            $customer,
            'NEW001',
            now()->subMonths(8)->format('Y-m-d')
        );
        $hoje = $this->makeReserva($customer, 'NEW002', now()->format('Y-m-d'));

        $this->artisan('pre-reservas:cleanup')
            ->assertSuccessful();

        $this->assertDatabaseMissing('reservations', ['id' => $antiga->id]);
        $this->assertDatabaseHas('reservations', ['id' => $dentroDoPrazo->id]);
        $this->assertDatabaseHas('reservations', ['id' => $hoje->id]);
    }

    public function test_keeps_reservation_exactly_nine_months_old(): void
    {
        $customer = $this->makeCustomer();

        // 🔹 Exatamente 9 meses → "mais de 9 meses" é estrito: mantém
        $noLimite = $this->makeReserva(
            $customer,
            'LIMITE9M',
            now()->subMonths(9)->format('Y-m-d')
        );

        $this->artisan('pre-reservas:cleanup')
            ->assertSuccessful();

        $this->assertDatabaseHas('reservations', ['id' => $noLimite->id]);
    }

    public function test_removes_nine_months_plus_one_day(): void
    {
        $customer = $this->makeCustomer();

        $umDiaAlem = $this->makeReserva(
            $customer,
            'LIMITE9M1D',
            now()->subMonths(9)->subDay()->format('Y-m-d')
        );

        $this->artisan('pre-reservas:cleanup')
            ->assertSuccessful();

        $this->assertDatabaseMissing('reservations', ['id' => $umDiaAlem->id]);
    }
}
