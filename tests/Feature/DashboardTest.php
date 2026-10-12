<?php

namespace Tests\Feature;

use App\Livewire\Dashboard\Dashboard;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Ocorrencia;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['employee', 'manager', 'admin', 'super-admin'] as $role) {
            Role::create(['name' => $role]);
        }

        Company::factory()->create(['id' => 1]);
        Company::factory()->create(['id' => 18]);
        Company::factory()->create(['id' => 21]);
    }

    protected function makeUser(string $role, int $companyId): User
    {
        $user = User::factory()->create(['company_id' => $companyId]);
        $user->assignRole($role);

        return $user;
    }

    private function makeReserva(int $companyId, string $codigo): Reservation
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
            'checkin' => '2026-11-15',
            'checkout' => '2026-11-18',
            'adultos' => 2,
            'criancas' => 0,
            'codigo' => $codigo,
            'status' => 'pendente',
        ]);
    }

    public function test_manager_company_18_sees_normal_turno_not_cavalo(): void
    {
        $manager = $this->makeUser('manager', 18);
        $author = $this->makeUser('employee', 18);

        // Tipo cavalo NÃO deve ser escolhido para a empresa 18
        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $author->id,
            'type' => 'passagem-de-turno-cavalo',
            'status' => 1,
            'form' => ['turno' => ['hospedes' => 99]],
            'created_at' => now()->subHour(),
        ]);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $author->id,
            'type' => 'passagem-de-turno',
            'status' => 1,
            'form' => ['turno' => ['hospedes' => 10]],
            'created_at' => now(),
        ]);

        Livewire::actingAs($manager)
            ->test(Dashboard::class)
            ->assertSet('lastTurnoLabel', 'Passagem de Turno')
            ->assertSet('lastTurno.hospedes', 10)
            ->assertSee('Dados da última passagem');
    }

    public function test_manager_company_21_sees_cavalo_turno(): void
    {
        $manager = $this->makeUser('manager', 21);
        $author = $this->makeUser('employee', 21);

        Ocorrencia::factory()->create([
            'company_id' => 21,
            'user_id' => $author->id,
            'type' => 'passagem-de-turno-cavalo',
            'status' => 1,
            'form' => ['turno' => ['hospedes' => 7]],
        ]);

        Livewire::actingAs($manager)
            ->test(Dashboard::class)
            ->assertSet('lastTurnoLabel', 'Passagem de Turno')
            ->assertSet('lastTurno.hospedes', 7);
    }

    public function test_manager_kpis_and_list_only_count_own_company(): void
    {
        Company::find(18)->update(['alias_name' => 'Hotel Teste 18']);

        $manager = $this->makeUser('manager', 18);
        $author18 = $this->makeUser('employee', 18);
        $author21 = $this->makeUser('employee', 21);

        Ocorrencia::factory()->create(['company_id' => 18, 'user_id' => $author18->id, 'type' => 'branco', 'status' => 1, 'title' => 'Da minha empresa XYZ']);
        Ocorrencia::factory()->create(['company_id' => 21, 'user_id' => $author21->id, 'type' => 'branco', 'status' => 1, 'title' => 'Outra empresa ABC']);

        Livewire::actingAs($manager)
            ->test(Dashboard::class)
            ->assertSet('kpiHoje', 1)
            ->assertSee('Da minha empresa XYZ')
            ->assertDontSee('Outra empresa ABC')
            ->assertSet('userCompany', 'Hotel Teste 18');
    }

    public function test_admin_sees_all_companies_and_no_company_badge(): void
    {
        $admin = $this->makeUser('admin', 1);
        $author18 = $this->makeUser('employee', 18);
        $author21 = $this->makeUser('employee', 21);

        Ocorrencia::factory()->create(['company_id' => 18, 'user_id' => $author18->id, 'type' => 'branco', 'status' => 1]);
        Ocorrencia::factory()->create(['company_id' => 21, 'user_id' => $author21->id, 'type' => 'branco', 'status' => 1]);

        Livewire::actingAs($admin)
            ->test(Dashboard::class)
            ->assertSet('kpiHoje', 2)
            ->assertSet('userCompany', null)
            ->assertSee('Todas as empresas');
    }

    public function test_super_admin_sees_latest_turno_of_either_type(): void
    {
        $super = $this->makeUser('super-admin', 1);
        $author18 = $this->makeUser('employee', 18);
        $author21 = $this->makeUser('employee', 21);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $author18->id,
            'type' => 'passagem-de-turno',
            'status' => 1,
            'form' => ['turno' => ['hospedes' => 5]],
            'created_at' => now()->subDay(),
        ]);

        // A mais recente entre os dois tipos
        Ocorrencia::factory()->create([
            'company_id' => 21,
            'user_id' => $author21->id,
            'type' => 'passagem-de-turno-cavalo',
            'status' => 1,
            'form' => ['turno' => ['hospedes' => 42]],
            'created_at' => now(),
        ]);

        Livewire::actingAs($super)
            ->test(Dashboard::class)
            ->assertSet('lastTurnoLabel', 'Passagem de Turno')
            ->assertSet('lastTurno.hospedes', 42);
    }

    public function test_type_badges_are_rendered_in_list(): void
    {
        $manager = $this->makeUser('manager', 18);
        $author = $this->makeUser('employee', 18);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $author->id,
            'type' => 'ocorrencias-diarias',
            'status' => 1,
            'title' => 'Diária com badge XYZ',
        ]);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $author->id,
            'type' => 'varreduras-fichas-sistemas',
            'status' => 1,
            'title' => 'Varredura com badge ABC',
        ]);

        Livewire::actingAs($manager)
            ->test(Dashboard::class)
            ->assertSee('Diárias')
            ->assertSee('Varreduras');
    }

    public function test_dashboard_shows_last_five_reservas_newest_first(): void
    {
        $admin = $this->makeUser('admin', 1);

        // 🔹 6 reservas → só as 5 mais recentes aparecem no card
        for ($i = 1; $i <= 6; $i++) {
            $reserva = $this->makeReserva(18, sprintf('PAINEL%03d', $i));
            $reserva->created_at = now()->subMinutes(60 - $i);
            $reserva->save();
        }

        Livewire::actingAs($admin)
            ->test(Dashboard::class)
            ->assertSee('Últimas Pré-reservas')
            ->assertSeeInOrder(['PAINEL006', 'PAINEL002'])
            ->assertDontSee('PAINEL001');
    }

    public function test_manager_sees_only_own_company_reservas_on_card(): void
    {
        $manager = $this->makeUser('manager', 18);

        $this->makeReserva(18, 'RESVDASH18');
        $this->makeReserva(21, 'RESVDASH21');

        Livewire::actingAs($manager)
            ->test(Dashboard::class)
            ->assertSee('RESVDASH18')
            ->assertDontSee('RESVDASH21');
    }

    public function test_employee_sees_improved_ocorrencias_and_reservas_cards(): void
    {
        $employee = $this->makeUser('employee', 18);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $employee->id,
            'type' => 'branco',
            'status' => 1,
            'title' => 'Ocorrência do painel XYZ',
        ]);

        $this->makeReserva(18, 'RESVEMP18');
        $this->makeReserva(21, 'RESVEMP21');

        Livewire::actingAs($employee)
            ->test(Dashboard::class)
            // 🔹 Card de ocorrências melhorado: autor + badge de tipo
            ->assertSee('Últimas Ocorrências')
            ->assertSee('Ocorrência do painel XYZ')
            ->assertSee('Em branco')
            // 🔹 Card de pré-reservas também no painel do colaborador
            ->assertSee('Últimas Pré-reservas')
            ->assertSee('RESVEMP18')
            ->assertDontSee('RESVEMP21');
    }

    public function test_employee_does_not_see_kpis_section(): void
    {
        $employee = $this->makeUser('employee', 18);

        Livewire::actingAs($employee)
            ->test(Dashboard::class)
            ->assertDontSee('Dados da última passagem');
    }
}
