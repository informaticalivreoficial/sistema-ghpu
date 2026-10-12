<?php

namespace App\Livewire\Dashboard\Reservas;

use App\Models\Company;
use App\Models\Reservation;
use Livewire\Component;
use Livewire\WithPagination;

class Reservas extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // 🔹 Filtros da listagem
    public string $search = '';

    public string $filterStatus = '';

    public string $filterCompany = '';

    public string $filterDateFrom = '';

    public string $filterDateTo = '';

    /** Empresas no select (apenas admin/super-admin) */
    public array $companyOptions = [];

    /** Modal de detalhes */
    public bool $showModal = false;

    public ?Reservation $reservationSelected = null;

    /** Status selecionado no modal */
    public string $modalStatus = '';

    public function mount(): void
    {
        $user = auth()->user();

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $this->companyOptions = Company::query()
                ->orderBy('alias_name')
                ->get(['id', 'alias_name'])
                ->mapWithKeys(fn ($c) => [$c->id => $c->alias_name])
                ->toArray();
        }
    }

    /** Reseta a paginação ao mexer em qualquer filtro */
    public function updating(string $property): void
    {
        if (in_array($property, ['search', 'filterStatus', 'filterCompany', 'filterDateFrom', 'filterDateTo'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'filterStatus',
            'filterCompany',
            'filterDateFrom',
            'filterDateTo',
        ]);

        $this->resetPage();
    }

    public function viewReserva(int $id): void
    {
        // Global Scope do tenant garante que cada um só abre reservas da sua empresa
        $reservation = Reservation::with(['customer', 'apartment'])->findOrFail($id);

        $this->reservationSelected = $reservation;
        $this->modalStatus = $reservation->status;
        $this->showModal = true;
    }

    /** Altera o status da reserva (manager+, admin e super) */
    public function saveStatus(): void
    {
        $user = auth()->user();

        // 🔥 Guard na action: employee não altera nem acessando a action direto
        if ($user->isEmployee()) {
            $this->dispatch('swal', [
                'title' => 'Acesso negado',
                'text' => 'Você não tem permissão para alterar pré-reservas.',
                'icon' => 'error',
            ]);

            return;
        }

        if (! array_key_exists($this->modalStatus, Reservation::STATUSES)) {
            $this->dispatch('swal', [
                'title' => 'Status inválido',
                'text' => 'Selecione um status válido.',
                'icon' => 'error',
            ]);

            return;
        }

        // Global Scope garante que manager só alcança reservas da própria empresa
        if (! $this->reservationSelected) {
            $this->dispatch('swal', [
                'title' => 'Pré-reserva não encontrada',
                'text' => 'Abra uma pré-reserva antes de alterar o status.',
                'icon' => 'error',
            ]);

            return;
        }

        $reservation = Reservation::findOrFail($this->reservationSelected->id);

        $reservation->status = $this->modalStatus;
        $reservation->save();

        $this->dispatch('swal', [
            'title' => 'Atualizado!',
            'text' => 'Status da pré-reserva alterado com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function render()
    {
        $title = 'Pré-reservas';
        $user = auth()->user();

        $reservas = Reservation::query()
            ->with(['customer', 'apartment'])
            // 🔹 Escopo de tenant: exceto admin/super (Global Scope já garante,
            // aqui o filtro de empresa do select só vale para esses perfis)
            ->when($this->filterCompany !== '' && ($user->isSuperAdmin() || $user->isAdmin()), function ($query) {
                $query->where('company_id', (int) $this->filterCompany);
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('status', $this->filterStatus);
            })
            // 🔹 Busca por cliente, código ou apartamento
            ->when($this->search !== '', function ($query) {
                $term = "%{$this->search}%";

                $query->where(function ($q) use ($term) {
                    $q->where('codigo', 'LIKE', $term)
                        ->orWhere('apartamento_texto', 'LIKE', $term)
                        ->orWhereHas('customer', function ($cq) use ($term) {
                            $cq->where('name', 'LIKE', $term)
                                ->orWhere('cpf', 'LIKE', $term);
                        });
                });
            })
            // 🔹 Período sobre o check-in
            ->when($this->filterDateFrom !== '', function ($query) {
                $query->whereDate('checkin', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo !== '', function ($query) {
                $query->whereDate('checkin', '<=', $this->filterDateTo);
            })
            ->orderByDesc('checkin')
            ->orderByDesc('id')
            ->paginate(35);

        return view('livewire.dashboard.reservas.reservas', [
            'title' => $title,
            'reservas' => $reservas,
            'statusOptions' => Reservation::STATUSES,
        ]);
    }
}
