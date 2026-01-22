<?php

namespace App\Livewire\Dashboard\Ocorrencias;

use App\Models\Ocorrencia;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Ocorrencias extends Component
{
    use WithPagination;

    public int $perPage = 24;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    protected $updatesQueryString = ['search'];

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    #{Url}
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 24; 
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

    public function toggleStatus($id)
    {              
        $ocorrencia = Ocorrencia::findOrFail($id);
        $ocorrencia->status = !$ocorrencia->status;        
        $ocorrencia->save();
    }

    public function setDeleteId($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Ocorrência?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteOcorrencia',
            'confirmParams' => [$id],
        ]);        
    }

    #[On('deleteOcorrencia')]
    public function deleteOcorrencia($id): void
    {
        $ocorrencia = Ocorrencia::findOrFail($id);

        if (Gate::denies('delete', $ocorrencia)) {
            $this->dispatch('swal', [
                'title' => 'Acesso negado',
                'text'  => 'Você não tem permissão para excluir esta ocorrência.',
                'icon'  => 'error',
            ]);
            return;
        }

        $ocorrencia->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text'  => 'Ocorrência excluída com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);        
    }

    public function render()
    {
        $title = 'Ocorrências';
        $user = auth()->user();
        $searchableFields = ['title', 'content'];
        
        $ocorrencias = Ocorrencia::query()
            // Filtro por empresa (exceto para Super Admin e Admin)
            ->when(!$user->isSuperAdmin() && !$user->isAdmin(), function($query) use ($user) {
                $query->where('company_id', $user->company_id);
            })
            // Colaborador NÃO vê ocorrências com status null ou 0
            ->when($user->isEmployee(), function($query) {
                $query->where(function($q) {
                    $q->whereNotNull('status')
                    ->where('status', '!=', 0);
                });
            })
            // Busca
            ->when($this->search, function ($query) use ($searchableFields) {
                $query->where(function ($q) use ($searchableFields) {
                    foreach ($searchableFields as $field) {
                        $q->orWhere($field, 'LIKE', "%{$this->search}%");
                    }

                    $q->orWhereHas('user', function ($qUser) {
                        $qUser->where('name', 'LIKE', "%{$this->search}%");
                    });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $view = $user->isEmployee()
            ? 'livewire.dashboard.ocorrencias.ocorrencias-colaborador'
            : 'livewire.dashboard.ocorrencias.ocorrencias';

        return view($view, [
            'ocorrencias' => $ocorrencias,
            'title' => $title,
        ]); 
    }
}
