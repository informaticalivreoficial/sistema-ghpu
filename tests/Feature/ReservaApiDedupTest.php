<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\PreReservaCriada;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReservaApiDedupTest extends TestCase
{
    use RefreshDatabase;

    protected ApiKey $apiKey;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['employee', 'manager', 'admin', 'super-admin'] as $role) {
            Role::create(['name' => $role]);
        }

        Company::factory()->create(['id' => 18]);
        Company::factory()->create(['id' => 21]);

        $this->apiKey = ApiKey::create([
            'company_id' => 18,
            'key' => 'chave-teste-18',
            'active' => true,
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Hospede Teste',
            'email' => 'hospede@teste.com',
            'cpf' => '11122233344',
            'checkin' => '15/10/2026',
            'checkout' => '18/10/2026',
            'adultos' => 2,
            'criancas' => 1,
        ], $overrides);
    }

    public function test_api_creates_reservation_with_valid_key(): void
    {
        $response = $this->postJson('/api/reservations', $this->payload(), [
            'X-API-KEY' => 'chave-teste-18',
        ]);

        $response->assertCreated()->assertJsonPath('success', true);

        $this->assertDatabaseCount('reservations', 1);
        $this->assertSame('pendente', Reservation::first()->status);
        // company_id vem da API KEY, nunca do corpo da requisição
        $this->assertSame(18, (int) Reservation::first()->company_id);
    }

    public function test_api_rejects_invalid_key(): void
    {
        $this->postJson('/api/reservations', $this->payload(), [
            'X-API-KEY' => 'chave-invalida',
        ])->assertUnauthorized();

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_same_cpf_with_different_email_reuses_single_customer(): void
    {
        // 🔥 Cenário que quebrava antes: CPF igual, email diferente
        // (firstOrCreate não casava e o insert estourava unique de CPF)
        $this->postJson('/api/reservations', $this->payload([
            'email' => 'primeiro@teste.com',
        ]), ['X-API-KEY' => 'chave-teste-18'])->assertCreated();

        $this->postJson('/api/reservations', $this->payload([
            'email' => 'segundo@teste.com',
        ]), ['X-API-KEY' => 'chave-teste-18'])->assertCreated();

        // Um único cliente com DUAS reservas
        $this->assertDatabaseCount('customers', 1);
        $this->assertDatabaseCount('reservations', 2);

        $customer = Customer::sole();
        $this->assertSame(2, $customer->reservations()->count());
        // Email do primeiro cadastro não é sobrescrito
        $this->assertSame('primeiro@teste.com', $customer->email);
    }

    public function test_same_email_on_different_companies_creates_two_customers(): void
    {
        // Email agora é único POR EMPRESA — mesma hóspede nas duas empresas
        $this->postJson('/api/reservations', $this->payload(), [
            'X-API-KEY' => 'chave-teste-18',
        ])->assertCreated();

        ApiKey::create([
            'company_id' => 21,
            'key' => 'chave-teste-21',
            'active' => true,
        ]);

        $this->postJson('/api/reservations', $this->payload(), [
            'X-API-KEY' => 'chave-teste-21',
        ])->assertCreated();

        $this->assertDatabaseCount('customers', 2);
        $this->assertDatabaseCount('reservations', 2);
    }

    public function test_customers_without_documents_are_not_merged(): void
    {
        // 🔥 Sem CPF e sem email: NÃO pode fundir clientes diferentes num só
        // (o firstOrCreate antigo casava cpf null + email null)
        $this->postJson('/api/reservations', $this->payload([
            'name' => 'Primeiro Sem Documento',
            'email' => null,
            'cpf' => null,
        ]), ['X-API-KEY' => 'chave-teste-18'])->assertCreated();

        $this->postJson('/api/reservations', $this->payload([
            'name' => 'Segundo Sem Documento',
            'email' => null,
            'cpf' => null,
        ]), ['X-API-KEY' => 'chave-teste-18'])->assertCreated();

        $this->assertDatabaseCount('customers', 2);
        $this->assertDatabaseCount('reservations', 2);
    }

    public function test_missing_documents_field_is_validated(): void
    {
        $this->postJson('/api/reservations', [
            'checkin' => '15/10/2026',
            'checkout' => '18/10/2026',
            'adultos' => 2,
        ], ['X-API-KEY' => 'chave-teste-18'])->assertUnprocessable();

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_api_reservation_notifies_admins_and_company_members(): void
    {
        $admin = User::factory()->create(['company_id' => 18]);
        $admin->assignRole('admin');

        $managerDaEmpresa = User::factory()->create(['company_id' => 18]);
        $managerDaEmpresa->assignRole('manager');

        $managerOutraEmpresa = User::factory()->create(['company_id' => 21]);
        $managerOutraEmpresa->assignRole('manager');

        $employee = User::factory()->create(['company_id' => 18]);
        $employee->assignRole('employee');

        $this->postJson('/api/reservations', $this->payload(), [
            'X-API-KEY' => 'chave-teste-18',
        ])->assertCreated();

        // 🔔 Admin, manager e colaborador da empresa recebem via BD
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $admin->id,
            'type' => PreReservaCriada::class,
        ]);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $managerDaEmpresa->id,
            'type' => PreReservaCriada::class,
        ]);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $employee->id,
            'type' => PreReservaCriada::class,
        ]);

        // ❌ Manager de outra empresa não recebe
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => $managerOutraEmpresa->id,
            'type' => PreReservaCriada::class,
        ]);
    }
}
