<?php

namespace Tests\Feature;

use App\Livewire\Dashboard\Companies\Companies;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CompaniesPageTest extends TestCase
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
    }

    protected function makeUser(string $role, int $companyId): User
    {
        $user = User::factory()->create(['company_id' => $companyId]);
        $user->assignRole($role);

        return $user;
    }

    public function test_admin_can_list_companies(): void
    {
        $admin = $this->makeUser('admin', 1);

        $this->actingAs($admin)
            ->get(route('companies.index'))
            ->assertOk();
    }

    public function test_super_admin_can_list_companies(): void
    {
        $super = $this->makeUser('super-admin', 1);

        $this->actingAs($super)
            ->get(route('companies.index'))
            ->assertOk();
    }

    public function test_list_companies_with_logo_and_inactive(): void
    {
        Storage::fake('public');

        $admin = $this->makeUser('admin', 1);

        Storage::disk('public')->put('company/logo-real.png', 'fake-image');
        Company::factory()->create(['logo' => 'company/logo-real.png', 'status' => 1]);
        Company::factory()->create(['logo' => 'company/nao-existe.png', 'status' => 0]);
        Company::factory()->create(['logo' => null, 'status' => 1]);

        $this->actingAs($admin)
            ->get(route('companies.index'))
            ->assertOk()
            // logo que existe no disco público é exibida
            ->assertSee('storage/company/logo-real.png', false)
            // logo inexistente/sem arquivo cai na imagem padrão, sem quebrar
            ->assertSee('theme/images/image.jpg', false);
    }

    public function test_view_company_modal_renders_correct_fields(): void
    {
        Storage::fake('public');

        $admin = $this->makeUser('admin', 1);
        $company = Company::factory()->create([
            'social_name' => 'Empresa Teste Ltda',
            'alias_name' => 'Empresa Teste',
            'document_company' => '11222333000181',
        ]);

        $this->actingAs($admin);

        Livewire::test(Companies::class)
            ->call('viewCompany', $company->id)
            ->assertSet('showCompanyModal', true)
            ->assertSee('Empresa Teste Ltda')
            ->assertSee('11222333000181');
    }

    public function test_tampered_sort_field_does_not_break_query(): void
    {
        $admin = $this->makeUser('admin', 1);

        $this->actingAs($admin);

        // sortField é manipulável pelo cliente; coluna inválida não pode virar 500
        Livewire::test(Companies::class)
            ->set('sortField', 'coluna_inexistente; DROP TABLE companies')
            ->set('search', 'empresa') // força novo render com o campo adulterado
            ->assertSet('search', 'empresa')
            ->assertSee('Empresas');
    }
}
