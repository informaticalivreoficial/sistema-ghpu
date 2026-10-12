<?php

namespace Tests\Feature;

use App\Livewire\Dashboard\Reservas\Reservas;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReservasTest extends TestCase
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

    private function makeReserva(int $companyId, string $codigo, string $status = 'pendente'): Reservation
    {
        $customer = Customer::create([
            'company_id' => $companyId,
            'name' => "Cliente {$codigo}",
            'email' => strtolower($codigo).'@teste.com',
            'status' => 'ativo',
        ]);

        return Reservation::create([
            'company_id' => $companyId,
            'customer_id' => $customer->id,
            'checkin' => '2026-10-15',
            'checkout' => '2026-10-18',
            'adultos' => 2,
            'criancas' => 1,
            'codigo' => $codigo,
            'status' => $status,
        ]);
    }

    private function makeUser(string $role, int $companyId): User
    {
        $user = User::factory()->create(['company_id' => $companyId]);
        $user->assignRole($role);

        return $user;
    }

    public function test_employee_sees_only_own_company_reservations(): void
    {
        $this->makeReserva(18, 'RESERVAA18');
        $this->makeReserva(21, 'RESERVAB21');

        $employee = $this->makeUser('employee', 18);

        $this->actingAs($employee)
            ->get(route('reservas.index'))
            ->assertOk()
            ->assertSee('RESERVAA18')
            ->assertDontSee('RESERVAB21');
    }

    public function test_admin_sees_reservations_from_both_companies(): void
    {
        $this->makeReserva(18, 'RESERVAA18');
        $this->makeReserva(21, 'RESERVAB21');

        $admin = $this->makeUser('admin', 18);

        $this->actingAs($admin)
            ->get(route('reservas.index'))
            ->assertOk()
            ->assertSee('RESERVAA18')
            ->assertSee('RESERVAB21');
    }

    public function test_company_filter_applies_for_admin(): void
    {
        $this->makeReserva(18, 'RESERVAA18');
        $this->makeReserva(21, 'RESERVAB21');

        $admin = $this->makeUser('admin', 18);

        $this->actingAs($admin);

        Livewire::test(Reservas::class)
            ->set('filterCompany', '21')
            ->assertDontSee('RESERVAA18')
            ->assertSee('RESERVAB21');
    }

    public function test_employee_cannot_change_status(): void
    {
        $reserva = $this->makeReserva(18, 'RESERVAA18', 'pendente');

        $employee = $this->makeUser('employee', 18);

        $this->actingAs($employee);

        Livewire::test(Reservas::class)
            ->call('viewReserva', $reserva->id)
            ->set('modalStatus', 'confirmada')
            ->call('saveStatus');

        // 🔥 Guard está na action: não altera nada
        $this->assertDatabaseHas('reservations', [
            'id' => $reserva->id,
            'status' => 'pendente',
        ]);
    }

    public function test_manager_changes_status_of_own_company(): void
    {
        $reserva = $this->makeReserva(18, 'RESERVAA18', 'pendente');

        $manager = $this->makeUser('manager', 18);

        $this->actingAs($manager);

        Livewire::test(Reservas::class)
            ->call('viewReserva', $reserva->id)
            ->set('modalStatus', 'confirmada')
            ->call('saveStatus');

        $this->assertDatabaseHas('reservations', [
            'id' => $reserva->id,
            'status' => 'confirmada',
        ]);
    }

    public function test_manager_cannot_access_reservation_from_other_company(): void
    {
        $reserva = $this->makeReserva(18, 'RESERVAA18');

        $manager = $this->makeUser('manager', 21);

        $this->actingAs($manager);

        // Global Scope do tenant: reserva de outra empresa nem é encontrada
        $this->expectException(ModelNotFoundException::class);

        Livewire::test(Reservas::class)->call('viewReserva', $reserva->id);
    }

    public function test_invalid_status_is_rejected(): void
    {
        $reserva = $this->makeReserva(18, 'RESERVAA18', 'pendente');

        $manager = $this->makeUser('manager', 18);

        $this->actingAs($manager);

        Livewire::test(Reservas::class)
            ->call('viewReserva', $reserva->id)
            ->set('modalStatus', 'qualquer-coisa')
            ->call('saveStatus');

        $this->assertDatabaseHas('reservations', [
            'id' => $reserva->id,
            'status' => 'pendente',
        ]);
    }

    public function test_search_by_customer_name(): void
    {
        $this->makeReserva(18, 'RESERVAA18'); // cliente "Cliente RESERVAA18"
        $this->makeReserva(18, 'RESERVAB18');

        $admin = $this->makeUser('admin', 18);

        $this->actingAs($admin);

        Livewire::test(Reservas::class)
            ->set('search', 'RESERVAA18')
            ->assertSee('RESERVAA18')
            ->assertDontSee('RESERVAB18');
    }

    public function test_whatsapp_button_shows_when_number_registered(): void
    {
        $customer = Customer::create([
            'company_id' => 18,
            'name' => 'Cliente Com Zap',
            'email' => 'zap@teste.com',
            'whatsapp' => '(11) 99999-8888',
            'status' => 'ativo',
        ]);

        Reservation::create([
            'company_id' => 18,
            'customer_id' => $customer->id,
            'checkin' => '2026-10-15',
            'checkout' => '2026-10-18',
            'adultos' => 2,
            'criancas' => 0,
            'codigo' => 'RESERVAZAP',
            'status' => 'pendente',
        ]);

        $admin = $this->makeUser('admin', 18);

        $this->actingAs($admin)
            ->get(route('reservas.index'))
            ->assertOk()
            ->assertSee('wa.me/5511999998888', false);
    }

    public function test_whatsapp_button_hidden_without_number(): void
    {
        $this->makeReserva(18, 'RESERVAA18'); // cliente sem whatsapp

        $admin = $this->makeUser('admin', 18);

        $this->actingAs($admin)
            ->get(route('reservas.index'))
            ->assertOk()
            ->assertDontSee('wa.me');
    }
}
