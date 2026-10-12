<?php

namespace App\Livewire\Dashboard\Reservas;

use App\Models\Company;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class Clientes extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    /** Paginação infinita: registros por lote */
    public int $perPage = 24;

    /** Se há mais registros além dos carregados (controla o auto-scroll) */
    public bool $hasMore = false;

    // 🔹 Filtros da listagem
    public string $search = '';

    public string $filterStatus = '';

    public string $filterCompany = '';

    /** Empresas no select (apenas admin/super-admin) */
    public array $companyOptions = [];

    /** Modal de detalhes */
    public bool $showModal = false;

    public ?Customer $customerSelected = null;

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
        if (in_array($property, ['search', 'filterStatus', 'filterCompany'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'filterStatus', 'filterCompany']);
        $this->resetPage();
    }

    /** Paginação infinita: anexa o próximo lote de 24 registros */
    public function loadMore(): void
    {
        if (! $this->hasMore) {
            return;
        }

        $this->perPage += 24;
    }

    public function viewCustomer(int $id): void
    {
        // Global Scope do tenant garante que cada um só abre clientes da sua empresa
        $customer = Customer::with('reservations')->findOrFail($id);

        $this->customerSelected = $customer;
        $this->showModal = true;
    }

    public function render()
    {
        $title = 'Clientes';
        $user = auth()->user();

        $clientes = Customer::query()
            ->withCount('reservations')
            // 🔹 Filtro de empresa do select: só vale para admin/super
            // (demais perfis já são cortados pelo Global Scope do tenant)
            ->when($this->filterCompany !== '' && ($user->isSuperAdmin() || $user->isAdmin()), function ($query) {
                $query->where('company_id', (int) $this->filterCompany);
            })
            ->when($this->filterStatus === 'ativo', function ($query) {
                $query->whereIn('status', ['ativo', 'active']);
            })
            ->when($this->filterStatus === 'inativo', function ($query) {
                $query->whereNotIn('status', ['ativo', 'active']);
            })
            // 🔹 Busca por nome, email, CPF ou whatsapp
            ->when($this->search !== '', function ($query) {
                $term = "%{$this->search}%";

                $query->where(function ($q) use ($term) {
                    $q->where('name', 'LIKE', $term)
                        ->orWhere('email', 'LIKE', $term)
                        ->orWhere('cpf', 'LIKE', $term)
                        ->orWhere('whatsapp', 'LIKE', $term);
                });
            })
            // 🔹 Ordenação estável (nome + id de desempate para paginação infinita)
            ->orderBy('name')
            ->orderBy('id')
            ->paginate($this->perPage);

        $this->hasMore = $clientes->hasMorePages();

        return view('livewire.dashboard.reservas.clientes', [
            'title' => $title,
            'clientes' => $clientes,
            'statusOptions' => ['ativo' => 'Ativo', 'inativo' => 'Inativo'],
        ]);
    }
}
