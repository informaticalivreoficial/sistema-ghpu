<?php

namespace Tests\Feature;

use App\Livewire\Dashboard\Reports\OccurrencesReport;
use App\Models\Company;
use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OccurrencesReportTest extends TestCase
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

    public function test_manager_only_counts_own_company(): void
    {
        $manager = $this->makeUser('manager', 18);
        $author18 = $this->makeUser('employee', 18);
        $author21 = $this->makeUser('employee', 21);

        Ocorrencia::factory()->create(['company_id' => 18, 'user_id' => $author18->id, 'type' => 'branco', 'status' => 1]);
        Ocorrencia::factory()->create(['company_id' => 18, 'user_id' => $author18->id, 'type' => 'branco', 'status' => 1]);
        Ocorrencia::factory()->create(['company_id' => 21, 'user_id' => $author21->id, 'type' => 'branco', 'status' => 1]);

        Livewire::actingAs($manager)
            ->test(OccurrencesReport::class)
            ->assertSet('totalPeriod', 2);
    }

    public function test_manager_never_gets_company_options(): void
    {
        $manager = $this->makeUser('manager', 18);

        $component = Livewire::actingAs($manager)->test(OccurrencesReport::class);

        $this->assertEmpty($component->get('companyOptions'));
    }

    public function test_admin_gets_company_options_and_can_filter(): void
    {
        $admin = $this->makeUser('admin', 1);
        $author18 = $this->makeUser('employee', 18);
        $author21 = $this->makeUser('employee', 21);

        Ocorrencia::factory()->create(['company_id' => 18, 'user_id' => $author18->id, 'type' => 'branco', 'status' => 1]);
        Ocorrencia::factory()->create(['company_id' => 21, 'user_id' => $author21->id, 'type' => 'branco', 'status' => 1]);

        // Sem filtro: vê as duas
        Livewire::actingAs($admin)
            ->test(OccurrencesReport::class)
            ->assertSet('filterCompany', '')
            ->assertSet('totalPeriod', 2);

        // Filtro empresa 18: só a da 18
        Livewire::actingAs($admin)
            ->test(OccurrencesReport::class)
            ->set('filterCompany', '18')
            ->assertSet('totalPeriod', 1);
    }

    public function test_turno_type_follows_selected_company(): void
    {
        $admin = $this->makeUser('admin', 1);
        $author18 = $this->makeUser('employee', 18);
        $author21 = $this->makeUser('employee', 21);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $author18->id,
            'type' => 'passagem-de-turno',
            'status' => 1,
            'form' => ['turno' => ['hospedes' => 10]],
        ]);

        Ocorrencia::factory()->create([
            'company_id' => 21,
            'user_id' => $author21->id,
            'type' => 'passagem-de-turno-cavalo',
            'status' => 1,
            'form' => ['turno' => ['hospedes' => 99]],
        ]);

        // Empresa 18 → turno normal
        Livewire::actingAs($admin)
            ->test(OccurrencesReport::class)
            ->set('filterCompany', '18')
            ->assertSet('hasTurno', true)
            ->assertSet('lastTurnoLabel', 'Passagem de Turno')
            ->assertSet('lastTurno.hospedes', 10);

        // Empresa 21 → cavalo
        Livewire::actingAs($admin)
            ->test(OccurrencesReport::class)
            ->set('filterCompany', '21')
            ->assertSet('hasTurno', true)
            ->assertSet('lastTurnoLabel', 'Passagem de Turno')
            ->assertSet('lastTurno.hospedes', 99);
    }

    public function test_manager_company_18_sees_own_turno_type(): void
    {
        $manager = $this->makeUser('manager', 18);
        $author = $this->makeUser('employee', 18);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $author->id,
            'type' => 'passagem-de-turno',
            'status' => 1,
            'form' => ['turno' => ['hospedes' => 7]],
        ]);

        Livewire::actingAs($manager)
            ->test(OccurrencesReport::class)
            ->assertSet('hasTurno', true)
            ->assertSet('lastTurnoLabel', 'Passagem de Turno')
            ->assertSet('lastTurno.hospedes', 7);
    }

    public function test_period_filter_excludes_old_records(): void
    {
        $manager = $this->makeUser('manager', 18);
        $author = $this->makeUser('employee', 18);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $author->id,
            'type' => 'branco',
            'created_at' => now()->subMonths(2),
        ]);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $author->id,
            'type' => 'branco',
            'created_at' => now(),
        ]);

        // Mês atual: só o registro recente
        Livewire::actingAs($manager)
            ->test(OccurrencesReport::class)
            ->set('filterPeriod', 'mes')
            ->assertSet('totalPeriod', 1);

        // Tudo: os dois
        Livewire::actingAs($manager)
            ->test(OccurrencesReport::class)
            ->set('filterPeriod', 'tudo')
            ->assertSet('totalPeriod', 2);
    }

    public function test_empty_state_when_no_occurrences(): void
    {
        $manager = $this->makeUser('manager', 18);

        Livewire::actingAs($manager)
            ->test(OccurrencesReport::class)
            ->assertSee('Nenhuma ocorrência encontrada');
    }

    public function test_employee_cannot_access_report(): void
    {
        $employee = $this->makeUser('employee', 18);

        $this->actingAs($employee)
            ->get(route('reports.occurrences'))
            ->assertForbidden();
    }

    public function test_employee_aborts_on_mount_even_if_route_bypassed(): void
    {
        $employee = $this->makeUser('employee', 18);

        Livewire::actingAs($employee)
            ->test(OccurrencesReport::class)
            ->assertForbidden();
    }
}
