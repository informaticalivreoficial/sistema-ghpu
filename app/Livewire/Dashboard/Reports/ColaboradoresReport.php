<?php

namespace App\Livewire\Dashboard\Reports;

use App\Models\Company;
use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ColaboradoresReport extends Component
{
    /** Período: semana | mes | ano | tudo */
    public string $filterPeriod = 'mes';

    /** Empresa no filtro (apenas admin/super-admin) */
    public string $filterCompany = '';

    public array $companyOptions = [];

    /** Linhas do relatório: somente colaboradores com atividade (super-admin nunca aparece) */
    public array $report = [];

    /** 📊 Dados dos gráficos (públicos para o JS ler frescos via $wire) */
    public array $chartTop = ['labels' => [], 'totals' => []];

    public array $typeTotals = [];

    public function mount(): void
    {
        // 🔒 Colaborador não acessa este relatório (rota já protegida por role)
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
        // 🔹 loadReport roda no render — aqui só dispara a atualização dos charts no JS
        $this->dispatch('filters-updated');
    }

    public function updatedFilterCompany(): void
    {
        $this->dispatch('filters-updated');
    }

    public function render()
    {
        $this->loadReport();

        $user = auth()->user();

        // 📊 Dados prontos para os gráficos (públicos → JS lê via $wire)
        $top = collect($this->report)->take(10);

        $this->chartTop = [
            'labels' => $top->pluck('name')->all(),
            'totals' => $top->pluck('total')->all(),
        ];

        $this->typeTotals = [
            'Em branco' => array_sum(array_column($this->report, 'branco')),
            'Passagem de turno' => array_sum(array_column($this->report, 'passagem-de-turno')),
            'Ocorrências diárias' => array_sum(array_column($this->report, 'ocorrencias-diarias')),
            'Varreduras' => array_sum(array_column($this->report, 'varreduras-fichas-sistemas')),
        ];

        // Coluna de empresa só quando admin/super sem filtro de empresa
        $showCompany = ($user->isSuperAdmin() || $user->isAdmin()) && $this->filterCompany === '';

        $totalOcorrencias = array_sum(array_column($this->report, 'total'));

        return view('livewire.dashboard.reports.colaboradores-report', [
            'title' => 'Relatório de Colaboradores',
            'showCompany' => $showCompany,
            'totalOcorrencias' => $totalOcorrencias,
            'avgPerColaborador' => count($this->report) > 0
                ? round($totalOcorrencias / count($this->report), 1)
                : 0,
        ]);
    }

    /**
     * 🔹 Somente colaboradores com atividade no período.
     *    Manager: sempre a própria empresa (Global Scope + filtro).
     *    Admin/super: empresa escolhida no filtro (ou todas).
     *    🔒 Super-admin pode ver o relatório, mas NUNCA aparece na listagem.
     */
    protected function loadReport(): void
    {
        $user = auth()->user();
        $period = $this->filterPeriod;
        $companyId = $this->filterCompany;

        $base = fn () => Ocorrencia::query()
            ->when($companyId !== '' && ($user->isSuperAdmin() || $user->isAdmin()), function ($query) use ($companyId) {
                $query->where('company_id', (int) $companyId);
            })
            ->when($period !== 'tudo', function ($query) use ($period) {
                match ($period) {
                    'semana' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                    'mes' => $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year),
                    'ano' => $query->whereYear('created_at', now()->year),
                };
            });

        // Totais por colaborador
        $totals = $base()
            ->selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->pluck('total', 'user_id');

        if ($totals->isEmpty()) {
            $this->report = [];

            return;
        }

        // Quebra por tipo
        $byType = $base()
            ->selectRaw('user_id, type, COUNT(*) as total')
            ->groupBy('user_id', 'type')
            ->get()
            ->groupBy('user_id');

        // Último registro
        $lastDates = $base()
            ->selectRaw('user_id, MAX(created_at) as last_at')
            ->groupBy('user_id')
            ->pluck('last_at', 'user_id');

        $users = User::query()
            ->whereIn('id', $totals->keys())
            ->with('company:id,alias_name')
            ->get()
            ->keyBy('id');

        // 🔒 Super-admin não entra na listagem (nem nos totais por colaborador)
        $totals = $totals->reject(fn ($total, $userId) => $users->get($userId)?->isSuperAdmin());

        if ($totals->isEmpty()) {
            $this->report = [];

            return;
        }

        $this->report = $totals->map(function ($total, $userId) use ($users, $byType, $lastDates) {
            $u = $users->get($userId);

            if (! $u) {
                return null;
            }

            $types = $byType->get($userId, collect())
                ->mapWithKeys(fn ($row) => [$row->type => (int) $row->total]);

            return [
                'id' => $u->id,
                'name' => $u->name,
                'cargo' => $u->cargo,
                'avatar_url' => $this->avatarUrl($u),
                'company' => $u->company?->alias_name,
                'total' => (int) $total,
                'branco' => $types->get('branco', 0),
                'passagem-de-turno' => $types->get('passagem-de-turno', 0) + $types->get('passagem-de-turno-cavalo', 0),
                'ocorrencias-diarias' => $types->get('ocorrencias-diarias', 0),
                'varreduras-fichas-sistemas' => $types->get('varreduras-fichas-sistemas', 0),
                'last_at' => isset($lastDates[$userId])
                    ? \Carbon\Carbon::parse($lastDates[$userId])->format('d/m/Y H:i')
                    : null,
            ];
        })->filter()->values()->all();
    }

    protected function avatarUrl(User $user): string
    {
        if ($user->avatar && Storage::exists($user->avatar)) {
            return Storage::url($user->avatar);
        }

        return match ($user->gender) {
            'masculino' => asset('theme/images/avatar5.png'),
            'feminino' => asset('theme/images/avatar3.png'),
            default => asset('theme/images/image.jpg'),
        };
    }
}
