<?php

namespace App\Livewire\Dashboard\Apartments;

use App\Models\Apartments;
use App\Models\Company;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class ApartmentIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'all';
    public ?int $companyId = null; 

    public bool $active = true;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin()) {
            $this->companyId = auth()->user()->company_id;
        }
    }

    public function render()
    {
        return view('livewire.dashboard.apartments.apartment-index', [
            'apartments' => $this->apartments,
            'companies'  => auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()
                ? Company::orderBy('alias_name')->get()
                : collect(),
        ])->with('title', 'Gerenciar Apartmentos');
    }

    public function getApartmentsProperty()
    {
        return Apartments::query()
            ->when(
                !auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin(),
                fn ($q) => $q->where('company_id', auth()->user()->company_id)
            )
            ->when(
                $this->companyId,
                fn ($q) => $q->where('company_id', $this->companyId)
            )
            ->when(
                $this->search,
                fn ($q) => $q->where('name', 'like', "%{$this->search}%")
            )
            ->when(
                $this->status !== 'all',
                fn ($q) => $q->where('status', $this->status === 'ativo')
            )
            ->orderBy('name')
            ->get();
    }

    public function toggleStatus($id): void
    {
        $apartment = Apartments::findOrFail($id);
        $this->authorize('update', $apartment);
        $apartment->update([
            'status' => ! $apartment->status,
        ]);
    }

    public function setDeleteId($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Apartamento?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteApartamento',
            'confirmParams' => [$id],
        ]);
    }

    #[On('deleteApartamento')]
    public function deleteApartamento(int $id): void
    {
        $apartment = Apartments::findOrFail($id);

        if (Gate::denies('delete', $apartment)) {
            $this->dispatch('swal', [
                'title' => 'Acesso negado',
                'text'  => 'Você não tem permissão para excluir este apartamento.',
                'icon'  => 'error',
            ]);
            return;
        }

        if ($apartment->reservations()->exists()) {
            $this->dispatch('swal', [
                'title' => 'Não é possível excluir',
                'text'  => 'Este apartamento possui reservas vinculadas.',
                'icon'  => 'error',
            ]);
            return;
        }

        $apartment->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text'  => 'Apartamento excluído com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }
}
