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

            if (! $this->ocorrencia->canBeDeletedBy($user)) {
                abort(403, 'Você não pode excluir esta ocorrência após 6 horas.');
            }
            
            $ocorrencia = Ocorrencia::findOrFail($this->delete_id);            

            $ocorrencia->delete();

            $this->delete_id = null;

            $this->dispatch('swal', [
                'title' => 'Sucesso!',
                'icon'  => 'success',
                'text'  => 'Ocorrência removida!',
            ]);
        } catch (\Exception $e) {
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

        $searchableFields = ['title','content'];
        
        $ocorrencias = Ocorrencia::query()
        ->when($this->search, function ($query) use ($searchableFields) {
            $query->where(function ($q) use ($searchableFields) {
                // Busca nos campos do modelo
                foreach ($searchableFields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$this->search}%");
                }

                // Busca pelo nome do colaborador relacionado
                $q->orWhereHas('user', function ($qUser) {
                    $qUser->where('name', 'LIKE', "%{$this->search}%");
                });
            });
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage);

        $view = auth()->user()->hasRole('employee')
        ? 'livewire.dashboard.ocorrencias.ocorrencias-colaborador'
        : 'livewire.dashboard.ocorrencias.ocorrencias';

        return view($view, [
            'ocorrencias' => $ocorrencias,
            'title' => $title,
        ]);    

        // return view('livewire.dashboard.ocorrencias.ocorrencias',[
        //     'ocorrencias' => $ocorrencias,
        // ])->with('title', $title);
    }
}
