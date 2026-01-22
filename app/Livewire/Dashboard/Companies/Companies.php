<?php

namespace App\Livewire\Dashboard\Companies;

use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Companies extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    public string $sortField = 'social_name';

    public string $sortDirection = 'asc';

    public $showCompanyModal = false;
    public $companySelected;

    public function viewCompany($id)
    {
        $this->companySelected = Company::with(['users', 'ocorrencias'])->find($id);
        $this->showCompanyModal = true;
    }

    #{Url}
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function render()
    {
        $title = 'Lista de Empresas';
        $companies = Company::query()->when($this->search, function($query){
            $query->orWhere('social_name', 'LIKE', "%{$this->search}%");
            $query->orWhere('email', 'LIKE', "%{$this->search}%");
        })->orderBy($this->sortField, $this->sortDirection)->paginate(35);
        return view('livewire.dashboard.companies.companies',[
            'companies' => $companies
        ])->with('title', $title);
    }

    public function toggleStatus($id)
    {              
        $company = Company::findOrFail($id);
        $company->status = !$company->status;        
        $company->save();
    }

    public function setDeleteId($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Empresa?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteEmpresa',
            'confirmParams' => [$id],
        ]);       
    }

    #[On('deleteEmpresa')]
    public function deleteEmpresa($id): void
    {
        $company = Company::findOrFail($id);

        if (Gate::denies('delete', $company)) {
            $this->dispatch('swal', [
                'title' => 'Acesso negado',
                'text'  => 'Você não tem permissão para excluir esta empresa.',
                'icon'  => 'error',
            ]);
            return;
        }

        if ($company->users()->exists()) {
            $this->dispatch('swal', [
                'title' => 'Não é possível excluir',
                'text'  => 'Esta empresa possui colaboradores e administradores vinculados.',
                'icon'  => 'error',
            ]);
            return;
        }

        $logoPath = $company->logo;
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            Storage::disk('public')->delete($logoPath);
        }

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text'  => 'Empresa excluída com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }
}
