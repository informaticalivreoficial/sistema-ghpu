<?php

namespace App\Livewire\Dashboard\Ocorrencias;

use App\Models\Company;
use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Ocorrencias extends Component
{
    use WithPagination;

    public int $perPage = 24;

    protected $paginationTheme = 'bootstrap';

    protected $updatesQueryString = [
        'filterType',
        'filterStatus',
        'filterCompany',
        'filterDateFrom',
        'filterDateTo',
        'filterAuthor',
    ];

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    // 🔹 Filtros da listagem
    public string $filterType = '';

    public string $filterStatus = '';

    public string $filterCompany = '';

    public string $filterDateFrom = '';

    public string $filterDateTo = '';

    public string $filterAuthor = '';

    /** Tipos que o usuário logado pode filtrar (mesma regra do form) */
    public array $typeOptions = [];

    /** Empresas no select (apenas admin/super-admin) */
    public array $companyOptions = [];

    public function mount(): void
    {
        $user = auth()->user();

        // 🔹 Tipos por empresa (mesma regra do OcorrenciaForm::mount)
        $this->typeOptions = ['branco' => 'Em Branco'];

        if ((int) $user->company_id === 21 || $user->isAdmin() || $user->isSuperAdmin()) {
            $this->typeOptions['passagem-de-turno-cavalo'] = 'Passagem de Turno';
        }

        if ((int) $user->company_id === 18 || $user->isAdmin() || $user->isSuperAdmin()) {
            $this->typeOptions += [
                'varreduras-fichas-sistemas' => 'Varreduras de fichas x sistemas',
                'ocorrencias-diarias' => 'Ocorrências Diárias',
                'passagem-de-turno' => 'Passagem de Turno',
            ];
        }

        // 🔹 Empresas apenas para admin/super-admin
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $this->companyOptions = Company::query()
                ->orderBy('alias_name')
                ->get(['id', 'alias_name'])
                ->mapWithKeys(fn ($c) => [$c->id => $c->alias_name])
                ->toArray();
        }
    }

    // {Url}
    /** Reseta a paginação ao mexer em qualquer filtro */
    public function updating(string $property): void
    {
        if (str_starts_with($property, 'filter')) {
            $this->resetPage();
        }
    }

    /** Ao trocar a empresa, o autor filtrado pode não pertencer mais a ela */
    public function updatedFilterCompany(): void
    {
        $this->filterAuthor = '';
    }

    public function clearFilters(): void
    {
        $this->reset([
            'filterType',
            'filterStatus',
            'filterCompany',
            'filterDateFrom',
            'filterDateTo',
            'filterAuthor',
        ]);

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
        $ocorrencia->status = ! $ocorrencia->status;
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
                'text' => 'Você não tem permissão para excluir esta ocorrência.',
                'icon' => 'error',
            ]);

            return;
        }

        $ocorrencia->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text' => 'Ocorrência excluída com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function render()
    {
        $title = 'Ocorrências';
        $user = auth()->user();

        $ocorrencias = Ocorrencia::query()
            // Filtro por empresa (exceto para Super Admin e Admin)
            ->when(! $user->isSuperAdmin() && ! $user->isAdmin(), function ($query) use ($user) {
                $query->where('company_id', $user->company_id);
            })
            // Colaborador NÃO vê ocorrências com status null ou 0
            ->when($user->isEmployee(), function ($query) {
                $query->where(function ($q) {
                    $q->whereNotNull('status')
                        ->where('status', '!=', 0);
                });
            })
            // 🔹 Filtro por tipo (sanitizado: só tipos da empresa do usuário)
            ->when($this->filterType !== '' && array_key_exists($this->filterType, $this->typeOptions), function ($query) {
                $query->where('type', $this->filterType);
            })
            // 🔹 Filtro por status (employee só enxerga visíveis, então o filtro não se aplica)
            ->when($this->filterStatus !== '' && ! $user->isEmployee(), function ($query) {
                if ($this->filterStatus === '1') {
                    $query->where('status', 1);
                } else {
                    $query->where(function ($q) {
                        $q->whereNull('status')->orWhere('status', 0);
                    });
                }
            })
            // 🔹 Filtro por empresa (apenas admin/super-admin)
            ->when($this->filterCompany !== '' && ($user->isSuperAdmin() || $user->isAdmin()), function ($query) {
                $query->where('company_id', (int) $this->filterCompany);
            })
            // 🔹 Filtro por autor (sanitizado: só da empresa visível)
            ->when($this->filterAuthor !== '', function ($query) use ($user) {
                $query->where('user_id', (int) $this->filterAuthor)
                    ->when(! $user->isSuperAdmin() && ! $user->isAdmin(), function ($q) use ($user) {
                        $q->where('company_id', $user->company_id);
                    });
            })
            // 🔹 Filtro por período (criação)
            ->when($this->filterDateFrom !== '', function ($query) {
                $query->whereDate('created_at', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo !== '', function ($query) {
                $query->whereDate('created_at', '<=', $this->filterDateTo);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // 🔹 Autores possíveis: mesma empresa do usuário (ou da empresa filtrada p/ admin)
        $authorQuery = User::query()->orderBy('name');

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            if ($this->filterCompany !== '') {
                $authorQuery->where('company_id', (int) $this->filterCompany);
            }
        } else {
            $authorQuery->where('company_id', $user->company_id);
        }

        $authorOptions = $authorQuery->get(['id', 'name'])
            ->mapWithKeys(fn ($u) => [$u->id => $u->name])
            ->toArray();

        $view = $user->isEmployee()
            ? 'livewire.dashboard.ocorrencias.ocorrencias-colaborador'
            : 'livewire.dashboard.ocorrencias.ocorrencias';

        return view($view, [
            'ocorrencias' => $ocorrencias,
            'title' => $title,
            'authorOptions' => $authorOptions,
            'statusOptions' => ['1' => 'Visível', '0' => 'Oculto'],
        ]);
    }
}
