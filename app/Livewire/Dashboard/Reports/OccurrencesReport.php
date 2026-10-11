<?php

namespace App\Livewire\Dashboard\Reports;

use App\Models\Company;
use App\Models\Ocorrencia;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class OccurrencesReport extends Component
{
    /** Período: semana | mes | ano | tudo */
    public string $filterPeriod = 'mes';

    /** Empresa no filtro (apenas admin/super-admin) */
    public string $filterCompany = '';

    public array $companyOptions = [];

    /** Total de ocorrências no período */
    public int $totalPeriod = 0;

    /** Passagens de turno no período */
    public int $totalTurnos = 0;

    /** 🍩 Doughnut: por tipo */
    public array $chartByType = ['labels' => [], 'values' => []];

    /** 📈 Linha: últimos 6 meses (independe do período) */
    public array $chartTrend = ['labels' => [], 'values' => []];

    /** Última passagem de turno */
    public bool $hasTurno = false;

    public string $lastTurnoLabel = '';

    public ?string $lastTurnoDate = null;

    public array $lastTurno = [];

    public function mount(): void
    {
        // 🔒 Colaboradores não acessam relatórios (rota já protegida por role — defesa em profundidade)
        abort_if(auth()->user()?->isEmployee(), 403);

        $user = auth()->user();

        // 🏢 Somente admin/super escolhem a empresa
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $this->companyOptions = Company::query()
                ->orderBy('alias_name')
                ->get(['id', 'alias_name'])
                ->mapWithKeys(fn ($c) => [$c->id => $c->alias_name])
                ->toArray();
        }
    }

    public function updatedFilterPeriod(): void
    {
        $this->dispatch('filters-updated');
    }

    public function updatedFilterCompany(): void
    {
        $this->dispatch('filters-updated');
    }

    public function render()
    {
        $this->loadReport();

        return view('livewire.dashboard.reports.occurrences-report', [
            'title' => 'Relatórios de Ocorrências',
        ]);
    }

    /**
     * 🔹 Empresa efetiva do relatório.
     *    Manager: SEMPRE a própria empresa (nunca escolhe).
     *    Admin/super: empresa escolhida no filtro (ou null = todas).
     */
    protected function companyId(): ?int
    {
        $user = auth()->user();

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return $this->filterCompany !== '' ? (int) $this->filterCompany : null;
        }

        return $user->company_id ? (int) $user->company_id : null;
    }

    /**
     * 🛡️ Filtros de empresa + período. Além do Global Scope de tenant
     *    (que já cobre manager/employee), aplicamos where explícito de empresa.
     */
    protected function applyFilters(Builder $query, bool $withPeriod = true): Builder
    {
        $companyId = $this->companyId();

        if ($companyId !== null) {
            $query->where('ocorrencias.company_id', $companyId);
        }

        if ($withPeriod) {
            match ($this->filterPeriod) {
                'semana' => $query->whereBetween('ocorrencias.created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                'mes' => $query->whereMonth('ocorrencias.created_at', now()->month)
                    ->whereYear('ocorrencias.created_at', now()->year),
                'ano' => $query->whereYear('ocorrencias.created_at', now()->year),
                default => null,
            };
        }

        return $query;
    }

    protected function loadReport(): void
    {
        // 📊 Totais no período
        $this->totalPeriod = $this->applyFilters(Ocorrencia::query())->count();

        $this->totalTurnos = $this->applyFilters(Ocorrencia::query())
            ->whereIn('type', ['passagem-de-turno', 'passagem-de-turno-cavalo'])
            ->count();

        // 🍩 Por tipo (mescla os dois tipos de turno em um rótulo só)
        $byType = $this->applyFilters(Ocorrencia::query())
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $merged = [];
        foreach ($byType as $type => $total) {
            $label = $this->mapTypeLabel($type);
            $merged[$label] = ($merged[$label] ?? 0) + (int) $total;
        }

        $this->chartByType = [
            'labels' => array_keys($merged),
            'values' => array_values($merged),
        ];

        // 📈 Tendência dos últimos 6 meses (só empresa — ignora o período)
        $monthLabels = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
        $labels = [];
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $monthLabels[$month->month - 1];
            $values[] = $this->applyFilters(Ocorrencia::query(), withPeriod: false)
                ->whereMonth('ocorrencias.created_at', $month->month)
                ->whereYear('ocorrencias.created_at', $month->year)
                ->count();
        }

        $this->chartTrend = ['labels' => $labels, 'values' => $values];

        // 🔁 Última passagem de turno (independe do período)
        $this->loadLastTurno();
    }

    protected function loadLastTurno(): void
    {
        $this->hasTurno = false;
        $this->lastTurno = [];
        $this->lastTurnoDate = null;
        $this->lastTurnoLabel = '';

        // 🔹 Tipo de turno por empresa selecionada (regra de negócio: 18 → turno, 21 → cavalo)
        $types = match ($this->companyId()) {
            18 => ['passagem-de-turno'],
            21 => ['passagem-de-turno-cavalo'],
            default => ['passagem-de-turno', 'passagem-de-turno-cavalo'],
        };

        $turno = $this->applyFilters(Ocorrencia::query(), withPeriod: false)
            ->whereIn('type', $types)
            ->latest('created_at')
            ->first();

        if (! $turno || ! is_array($turno->form)) {
            return;
        }

        $this->hasTurno = true;
        $this->lastTurnoLabel = $this->turnoTypeLabel($turno->type);
        $this->lastTurnoDate = $turno->created_at->format('d/m/Y H:i');

        $this->lastTurno = [
            'hospedes' => (int) data_get($turno->form, 'turno.hospedes', 0),
            'aptos' => (int) data_get($turno->form, 'turno.apto_ocupados', 0),
            'reservas' => (int) data_get($turno->form, 'turno.reservas', 0),
            'checkouts' => (int) data_get($turno->form, 'turno.checkouts', 0),
            'cartoes' => (int) data_get($turno->form, 'turno.cartoes_emprestados', 0),

            'caixa_dinheiro' => (float) data_get($turno->form, 'turno.caixa_dinheiro', 0),
            'caixa_cartao' => (float) data_get($turno->form, 'turno.caixa_cartoes', 0),
            'caixa_total' => (float) (data_get($turno->form, 'turno.caixa_dinheiro', 0) + data_get($turno->form, 'turno.caixa_cartoes', 0)),
        ];
    }

    protected function mapTypeLabel(string $type): string
    {
        return match ($type) {
            'branco' => 'Em branco',
            'passagem-de-turno', 'passagem-de-turno-cavalo' => 'Passagem de Turno',
            'ocorrencias-diarias' => 'Ocorrências Diárias',
            'varreduras-fichas-sistemas' => 'Varreduras',
            default => ucfirst($type),
        };
    }

    protected function turnoTypeLabel(string $type): string
    {
        return 'Passagem de Turno';
    }
}
