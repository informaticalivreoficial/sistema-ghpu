<?php

namespace Tests\Feature;

use App\Livewire\Dashboard\Reports\ColaboradoresReport;
use App\Models\Company;
use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ColaboradoresReportTest extends TestCase
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

    public function test_lists_only_colaboradores_with_activity(): void
    {
        $manager = $this->makeUser('manager', 18);
        $active = $this->makeUser('employee', 18);
        $inactive = $this->makeUser('employee', 18);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $active->id,
            'type' => 'branco',
            'status' => 1,
        ]);

        Livewire::actingAs($manager)
            ->test(ColaboradoresReport::class)
            ->assertSee($active->name)
            ->assertDontSee($inactive->name);
    }

    public function test_manager_only_sees_own_company(): void
    {
        $manager = $this->makeUser('manager', 18);
        $colab18 = $this->makeUser('employee', 18);
        $colab21 = $this->makeUser('employee', 21);

        Ocorrencia::factory()->create(['company_id' => 18, 'user_id' => $colab18->id, 'type' => 'branco', 'status' => 1]);
        Ocorrencia::factory()->create(['company_id' => 21, 'user_id' => $colab21->id, 'type' => 'branco', 'status' => 1]);

        Livewire::actingAs($manager)
            ->test(ColaboradoresReport::class)
            ->assertSee($colab18->name)
            ->assertDontSee($colab21->name);
    }

    public function test_admin_sees_all_and_can_filter_by_company(): void
    {
        $admin = $this->makeUser('admin', 1);
        $colab18 = $this->makeUser('employee', 18);
        $colab21 = $this->makeUser('employee', 21);

        Ocorrencia::factory()->create(['company_id' => 18, 'user_id' => $colab18->id, 'type' => 'branco', 'status' => 1]);
        Ocorrencia::factory()->create(['company_id' => 21, 'user_id' => $colab21->id, 'type' => 'branco', 'status' => 1]);

        // Sem filtro: vê os dois
        Livewire::actingAs($admin)
            ->test(ColaboradoresReport::class)
            ->assertSee($colab18->name)
            ->assertSee($colab21->name)
            ->assertSet('filterCompany', '');

        // Filtro por empresa 18: só o da 18
        Livewire::actingAs($admin)
            ->test(ColaboradoresReport::class)
            ->set('filterCompany', '18')
            ->assertSee($colab18->name)
            ->assertDontSee($colab21->name);
    }

    public function test_period_filter_excludes_old_records(): void
    {
        $manager = $this->makeUser('manager', 18);
        $colab = $this->makeUser('employee', 18);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $colab->id,
            'type' => 'branco',
            'title' => 'Antiga RELOLD',
            'created_at' => now()->subMonths(2),
        ]);

        // Mês atual: não deve aparecer o registro antigo (colab sem atividade no mês)
        Livewire::actingAs($manager)
            ->test(ColaboradoresReport::class)
            ->set('filterPeriod', 'mes')
            ->assertDontSee($colab->name);

        // Tudo: aparece
        Livewire::actingAs($manager)
            ->test(ColaboradoresReport::class)
            ->set('filterPeriod', 'tudo')
            ->assertSee($colab->name);
    }

    public function test_empty_state_when_no_activity(): void
    {
        $manager = $this->makeUser('manager', 18);

        Livewire::actingAs($manager)
            ->test(ColaboradoresReport::class)
            ->assertSee('Nenhuma atividade encontrada');
    }

    public function test_employee_cannot_access_route(): void
    {
        $employee = $this->makeUser('employee', 18);

        $this->actingAs($employee)
            ->get(route('reports.colaboradores'))
            ->assertForbidden();
    }

    public function test_super_admin_can_access_report_and_filter_by_company(): void
    {
        $super = $this->makeUser('super-admin', 1);
        $colab18 = $this->makeUser('employee', 18);

        Ocorrencia::factory()->create(['company_id' => 18, 'user_id' => $colab18->id, 'type' => 'branco', 'status' => 1]);

        $component = Livewire::actingAs($super)->test(ColaboradoresReport::class);
        $this->assertNotEmpty($component->get('companyOptions'));

        // Pode filtrar por empresa
        Livewire::actingAs($super)
            ->test(ColaboradoresReport::class)
            ->set('filterCompany', '18')
            ->assertSee($colab18->name);
    }

    public function test_super_admin_is_never_listed_in_report(): void
    {
        $super = $this->makeUser('super-admin', 1);
        $manager = $this->makeUser('manager', 18);
        $colab = $this->makeUser('employee', 18);

        // Ocorrências do super-admin NÃO devem aparecer na listagem
        Ocorrencia::factory()->create(['company_id' => 18, 'user_id' => $super->id, 'type' => 'branco', 'status' => 1]);
        Ocorrencia::factory()->create(['company_id' => 18, 'user_id' => $super->id, 'type' => 'branco', 'status' => 1]);
        Ocorrencia::factory()->create(['company_id' => 18, 'user_id' => $colab->id, 'type' => 'branco', 'status' => 1]);

        // Manager vê apenas o colaborador (super-admin fora da listagem e dos totais)
        Livewire::actingAs($manager)
            ->test(ColaboradoresReport::class)
            ->assertSee($colab->name)
            ->assertDontSee($super->name)
            ->assertSet('report.0.total', 1);

        // Super-admin logado também não se vê na listagem
        Livewire::actingAs($super)
            ->test(ColaboradoresReport::class)
            ->assertSee($colab->name)
            ->assertDontSee($super->name);
    }
}
