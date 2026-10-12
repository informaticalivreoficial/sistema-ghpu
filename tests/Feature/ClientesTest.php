<?php

namespace Tests\Feature;

use App\Livewire\Dashboard\Reservas\Clientes;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ClientesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['employee', 'manager', 'admin', 'super-admin'] as $role) {
            Role::create(['name' => $role]);
        }

        Company::factory()->create(['id' => 18]);
        Company::factory()->create(['id' => 21]);
    }

    private function makeCustomer(int $companyId, string $name, string $email): Customer
    {
        return Customer::create([
            'company_id' => $companyId,
            'name' => $name,
            'email' => $email,
            'cpf' => null,
            'status' => 'ativo',
        ]);
    }

    private function makeReserva(Customer $customer, string $codigo): Reservation
    {
        return Reservation::create([
            'company_id' => $customer->company_id,
            'customer_id' => $customer->id,
            'checkin' => '2026-10-15',
            'checkout' => '2026-10-18',
            'adultos' => 2,
            'criancas' => 0,
            'codigo' => $codigo,
            'status' => 'pendente',
        ]);
    }

    private function makeUser(string $role, int $companyId): User
    {
        $user = User::factory()->create(['company_id' => $companyId]);
        $user->assignRole($role);

        return $user;
    }

    public function test_employee_sees_only_own_company_customers(): void
    {
        $this->makeCustomer(18, 'Maria Empresa 18', 'maria18@teste.com');
        $this->makeCustomer(21, 'Joao Empresa 21', 'joao21@teste.com');

        $employee = $this->makeUser('employee', 18);

        $this->actingAs($employee)
            ->get(route('clientes.index'))
            ->assertOk()
            ->assertSee('Maria Empresa 18')
            ->assertDontSee('Joao Empresa 21');
    }

    public function test_admin_sees_customers_from_both_companies(): void
    {
        $this->makeCustomer(18, 'Maria Empresa 18', 'maria18@teste.com');
        $this->makeCustomer(21, 'Joao Empresa 21', 'joao21@teste.com');

        $admin = $this->makeUser('admin', 18);

        $this->actingAs($admin)
            ->get(route('clientes.index'))
            ->assertOk()
            ->assertSee('Maria Empresa 18')
            ->assertSee('Joao Empresa 21');
    }

    public function test_company_filter_applies_for_admin(): void
    {
        $this->makeCustomer(18, 'Maria Empresa 18', 'maria18@teste.com');
        $this->makeCustomer(21, 'Joao Empresa 21', 'joao21@teste.com');

        $admin = $this->makeUser('admin', 18);

        $this->actingAs($admin);

        Livewire::test(Clientes::class)
            ->set('filterCompany', '21')
            ->assertSee('Joao Empresa 21')
            ->assertDontSee('Maria Empresa 18');
    }

    public function test_customer_with_two_reservations_shows_both_in_modal(): void
    {
        $customer = $this->makeCustomer(18, 'Maria Duas Reservas', 'maria2r@teste.com');
        $this->makeReserva($customer, 'RESERVA001');
        $this->makeReserva($customer, 'RESERVA002');

        $admin = $this->makeUser('admin', 18);

        $this->actingAs($admin);

        Livewire::test(Clientes::class)
            ->call('viewCustomer', $customer->id)
            ->assertSet('showModal', true)
            ->assertSee('RESERVA001')
            ->assertSee('RESERVA002');
    }

    public function test_manager_cannot_open_customer_from_other_company(): void
    {
        $customer = $this->makeCustomer(18, 'Maria Empresa 18', 'maria18b@teste.com');

        $manager = $this->makeUser('manager', 21);

        $this->actingAs($manager);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        Livewire::test(Clientes::class)->call('viewCustomer', $customer->id);
    }

    public function test_search_by_name_and_email(): void
    {
        $this->makeCustomer(18, 'Maria Busca', 'busca-maria@teste.com');
        $this->makeCustomer(18, 'Jose Outro', 'jose@teste.com');

        $admin = $this->makeUser('admin', 18);

        $this->actingAs($admin);

        Livewire::test(Clientes::class)
            ->set('search', 'busca-maria')
            ->assertSee('Maria Busca')
            ->assertDontSee('Jose Outro');
    }

    public function test_whatsapp_button_shows_when_number_registered(): void
    {
        Customer::create([
            'company_id' => 18,
            'name' => 'Cliente Com Zap',
            'email' => 'zap-clientes@teste.com',
            'whatsapp' => '+55 11 99999-7777',
            'status' => 'ativo',
        ]);
        $this->makeCustomer(18, 'Cliente Sem Zap', 'semzap@teste.com');

        $admin = $this->makeUser('admin', 18);

        $this->actingAs($admin)
            ->get(route('clientes.index'))
            ->assertOk()
            ->assertSee('wa.me/5511999997777', false);
    }

    public function test_whatsapp_button_hidden_without_number(): void
    {
        $this->makeCustomer(18, 'Cliente Sem Zap', 'semzap2@teste.com');

        $admin = $this->makeUser('admin', 18);

        $this->actingAs($admin)
            ->get(route('clientes.index'))
            ->assertOk()
            ->assertDontSee('wa.me');
    }

    public function test_infinite_scroll_loads_next_batch(): void
    {
        // 🔹 30 clientes (nome zero-pad → ordem alfabética = numérica)
        for ($i = 1; $i <= 30; $i++) {
            $this->makeCustomer(
                18,
                sprintf('Cliente %03d', $i),
                sprintf('cliente%03d@teste.com', $i)
            );
        }

        $admin = $this->makeUser('admin', 18);

        $this->actingAs($admin);

        // Primeiro lote: 24 registros (Cliente 001 até Cliente 024)
        Livewire::test(Clientes::class)
            ->assertSee('Cliente 001')
            ->assertDontSee('Cliente 030')
            ->assertSee('Carregar mais')
            ->call('loadMore')
            ->assertSee('Cliente 030')
            ->assertDontSee('Carregar mais');
    }
}
