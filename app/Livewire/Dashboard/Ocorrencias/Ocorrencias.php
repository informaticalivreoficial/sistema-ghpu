<?php

namespace App\Livewire\Dashboard\Ocorrencias;

use App\Models\Ocorrencia;
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

    public bool $active;

    public ?int $delete_id = null;    

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
        $this->delete_id = $id;
        $this->dispatch('delete-prompt');        
    }

    #[On('goOn-Delete')]
    public function delete(): void
    {
        try {
            $user = auth()->user();

            // Busca a ocorrência PRIMEIRO
            $ocorrencia = Ocorrencia::findOrFail($this->delete_id);

            // Verifica permissão
            if (!$ocorrencia->canBeDeletedBy($user)) {
                $this->dispatch('swal', [
                    'title' => 'Acesso Negado',
                    'icon'  => 'error',
                    'text'  => 'Você não tem permissão para excluir esta ocorrência.',
                ]);
                return;
            }
            
            // Deleta
            $ocorrencia->delete();

            // Limpa o ID
            $this->delete_id = null;

            // Recarrega a lista (se tiver)
            // $this->loadOcorrencias(); // descomente se necessário

            $this->dispatch('swal', [
                'title' => 'Sucesso!',
                'icon'  => 'success',
                'text'  => 'Ocorrência removida!',
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->dispatch('swal', [
                'title' => 'Erro!',
                'icon'  => 'error',
                'text'  => 'Ocorrência não encontrada.',
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao excluir ocorrência', [
                'delete_id' => $this->delete_id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            $this->dispatch('swal', [
                'title' => 'Erro!',
                'icon'  => 'error',
                'text'  => 'Não foi possível excluir a ocorrência.',
            ]);
        }
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
