<?php

namespace Tests\Feature;

use App\Livewire\Dashboard\Ocorrencias\Ocorrencias;
use App\Models\Company;
use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OcorrenciasFiltrosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['employee', 'manager', 'admin', 'super-admin'] as $role) {
            Role::create(['name' => $role]);
        }

        // 🔹 Empresas usadas pelas regras de tipo (18 e 21) precisam existir (FK)
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

    public function test_employee_company_18_only_sees_own_company_types(): void
    {
        $user = $this->makeUser('employee', 18);

        Livewire::actingAs($user)
            ->test(Ocorrencias::class)
            ->assertSet('typeOptions', [
                'branco' => 'Em Branco',
                'varreduras-fichas-sistemas' => 'Varreduras de fichas x sistemas',
                'ocorrencias-diarias' => 'Ocorrências Diárias',
                'passagem-de-turno' => 'Passagem de Turno',
            ]);
    }

    public function test_employee_company_21_only_sees_cavalo_type(): void
    {
        $user = $this->makeUser('employee', 21);

        Livewire::actingAs($user)
            ->test(Ocorrencias::class)
            ->assertSet('typeOptions', [
                'branco' => 'Em Branco',
                'passagem-de-turno-cavalo' => 'Passagem de Turno',
            ]);
    }

    public function test_admin_sees_all_types(): void
    {
        $user = $this->makeUser('admin', 1);

        Livewire::actingAs($user)
            ->test(Ocorrencias::class)
            ->assertSet('typeOptions', [
                'branco' => 'Em Branco',
                'passagem-de-turno-cavalo' => 'Passagem de Turno',
                'varreduras-fichas-sistemas' => 'Varreduras de fichas x sistemas',
                'ocorrencias-diarias' => 'Ocorrências Diárias',
                'passagem-de-turno' => 'Passagem de Turno',
            ]);
    }

    public function test_admin_gets_company_options_and_manager_does_not(): void
    {
        Company::factory()->create(['alias_name' => 'Pousada Teste']);

        $admin = $this->makeUser('admin', 1);
        $component = Livewire::actingAs($admin)->test(Ocorrencias::class);
        $this->assertNotEmpty($component->get('companyOptions'));

        $manager = $this->makeUser('manager', 18);
        $component = Livewire::actingAs($manager)->test(Ocorrencias::class);
        $this->assertEmpty($component->get('companyOptions'));
    }

    public function test_type_filter_is_ignored_when_not_allowed_for_user_company(): void
    {
        $user = $this->makeUser('employee', 21);
        $author = $this->makeUser('employee', 21);

        Ocorrencia::factory()->create([
            'company_id' => 21,
            'user_id' => $author->id,
            'type' => 'passagem-de-turno', // tipo da empresa 18 — não permitido p/ empresa 21
            'title' => 'Turno ilegal IGNOREME',
            'status' => 1,
        ]);

        // filterType fora do permitido → ignorado pelo sanitizador; lista tudo da empresa
        Livewire::actingAs($user)
            ->test(Ocorrencias::class)
            ->set('filterType', 'passagem-de-turno')
            ->assertSee('Turno ilegal IGNOREME');
    }

    public function test_type_filter_applies_when_allowed(): void
    {
        $user = $this->makeUser('employee', 18);
        $author = $this->makeUser('employee', 18);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $author->id,
            'type' => 'passagem-de-turno',
            'title' => 'Turno filtrado XYZ',
            'status' => 1,
        ]);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $author->id,
            'type' => 'ocorrencias-diarias',
            'title' => 'Diária ABC',
            'status' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(Ocorrencias::class)
            ->set('filterType', 'passagem-de-turno')
            ->assertSee('Turno filtrado XYZ')
            ->assertDontSee('Diária ABC');
    }

    public function test_date_filter_applies(): void
    {
        $user = $this->makeUser('manager', 18);
        $author = $this->makeUser('employee', 18);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $author->id,
            'type' => 'branco',
            'title' => 'Antiga FILTEROLD',
            'created_at' => now()->subDays(10),
        ]);

        Ocorrencia::factory()->create([
            'company_id' => 18,
            'user_id' => $author->id,
            'type' => 'branco',
            'title' => 'Recente FILTERNEW',
            'created_at' => now(),
        ]);

        Livewire::actingAs($user)
            ->test(Ocorrencias::class)
            ->set('filterDateFrom', now()->subDay()->format('Y-m-d'))
            ->assertSee('Recente FILTERNEW')
            ->assertDontSee('Antiga FILTEROLD');
    }

    public function test_employee_does_not_get_status_filter_in_view(): void
    {
        $user = $this->makeUser('employee', 18);

        Livewire::actingAs($user)
            ->test(Ocorrencias::class)
            ->assertDontSee('filterStatus');
    }

    public function test_clear_filters_resets_all(): void
    {
        $user = $this->makeUser('admin', 1);

        Livewire::actingAs($user)
            ->test(Ocorrencias::class)
            ->set('filterType', 'branco')
            ->set('filterStatus', '1')
            ->set('filterDateFrom', '2026-01-01')
            ->call('clearFilters')
            ->assertSet('filterType', '')
            ->assertSet('filterStatus', '')
            ->assertSet('filterDateFrom', '');
    }
}
