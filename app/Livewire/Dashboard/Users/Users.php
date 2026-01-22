<?php

namespace App\Livewire\Dashboard\Users;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Users extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    public string $sortField = 'name';

    public string $sortDirection = 'desc';

    public bool $updateMode = false;

        
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

    #[Title('Colaboradores')]
    public function render()
    {
        $auth = auth()->user();

        //$user = User::find(1);
        //$user->assignRole('super-admin');
        // DEBUG - Remover depois
        // dd([
        //     'user_id' => $auth->id,
        //     'user_name' => $auth->name,
        //     'roles' => $auth->roles->pluck('name'),
        //     'hasRole_super-admin' => $auth->hasRole('super-admin'),
        //     'hasRole_admin' => $auth->hasRole('admin'),
        //     'hasRole_manager' => $auth->hasRole('manager'),
        //     'isSuperAdmin' => $auth->isSuperAdmin(),
        // ]);

        $users = User::query()
            ->role('employee') // sempre colaboradores
            ->when($this->search, function($query){
                $query->where(function($q){
                    $q->where('name', 'LIKE', "%{$this->search}%")
                    ->orWhere('email', 'LIKE', "%{$this->search}%");
                });
            });

        // Apenas MANAGER vê somente da própria empresa
        // SuperAdmin e Admin veem TODOS
        if ($auth->hasRole('manager')) {
            $users->where('company_id', $auth->company_id);
        }
        // Se for superadmin ou admin, NÃO adiciona filtro (vê todos)

        $users = $users->orderBy($this->sortField, $this->sortDirection)->paginate(35);

        return view('livewire.dashboard.users.users',[
            'users' => $users
        ]);
    }

    public function setDeleteId($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Colaborador?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteColaborador',
            'confirmParams' => [$id],
        ]);        
    }
    
    #[On('deleteColaborador')]
    public function deleteColaborador($id)
    {
        $user = User::findOrFail($id);

        if (Gate::denies('delete', $user)) {
            $this->dispatch('swal', [
                'title' => 'Acesso negado',
                'text'  => 'Você não tem permissão para excluir este colaborador.',
                'icon'  => 'error',
            ]);
            return;
        }

        $user->delete();
        
        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text'  => 'Colaborador excluído com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function toggleStatus($id)
    {              
        $user = User::findOrFail($id);
        $user->status = !$user->status;        
        $user->save();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->dispatch('userId');
        $this->updateMode = true;
    }

}
