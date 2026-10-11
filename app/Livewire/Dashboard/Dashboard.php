<?php

namespace App\Livewire\Dashboard;

use App\Models\Ocorrencia;
use App\Models\Post;
use Livewire\Component;

class Dashboard extends Component
{
    public array $topposts = [];

    public int $postsCount = 0;

    public int $postsYearCount = 0;

    public $lastOcorrencias = null;

    /** 📊 KPIs do período (manager/admin/super) */
    public int $kpiHoje = 0;

    public int $kpiSemana = 0;

    public int $kpiMes = 0;

    /** Última passagem de turno */
    public array $lastTurno = [];

    public ?string $lastTurnoDate = null;

    public string $lastTurnoLabel = '';

    /** Badge da empresa do gerente */
    public ?string $userCompany = null;

    public function mount()
    {
        $user = auth()->user();

        // 🧑‍💼 Manager / Admin / Super
        if (! $user->isEmployee()) {
            $this->postsCount = Post::count();
            $this->postsYearCount = Post::whereYear('created_at', now()->year)->count();

            $this->topposts = Post::orderByDesc('views')->limit(5)->get()->toArray();

            // Badge da empresa só para manager (admin/super veem consolidado de todas)
            $this->userCompany = $user->isManager() ? $user->company?->alias_name : null;
        }

        // Colaborador → só vê as dele
        if ($user->isEmployee()) {
            $this->lastOcorrencias = Ocorrencia::where('company_id', $user->company_id)
                ->latest()
                ->limit(5)
                ->with('user:id,name,avatar')
                ->get();

            return;
        }

        // Manager / Admin / Super → KPIs (Global Scope já limita manager à própria empresa)
        $this->loadKpis();

        // Manager → apenas da empresa
        $query = Ocorrencia::latest()->limit(5)->with('user:id,name,avatar,cargo');

        if ($user->isManager()) {
            $query->where('company_id', $user->company_id);
        }

        $this->lastOcorrencias = $query->get();

        // 🔁 Última passagem de turno por empresa (18 → turno, 21 → cavalo; admin/super → mais recente dos dois)
        $this->loadLastTurno();
    }

    /**
     * 📊 Ocorrências hoje / semana / mês.
     *    Manager: Global Scope já filtra a própria empresa.
     *    Admin/super: visão consolidada de todas as empresas.
     */
    protected function loadKpis(): void
    {
        $this->kpiHoje = Ocorrencia::whereDate('created_at', today())->count();

        $this->kpiSemana = Ocorrencia::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $this->kpiMes = Ocorrencia::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    protected function loadLastTurno(): void
    {
        $user = auth()->user();
        $companyId = $user->isManager() ? ($user->company_id ? (int) $user->company_id : null) : null;

        // 🔹 Regra de negócio: empresa 18 → turno normal; empresa 21 → cavalo; demais/admin → os dois tipos
        $types = match ($companyId) {
            18 => ['passagem-de-turno'],
            21 => ['passagem-de-turno-cavalo'],
            default => ['passagem-de-turno', 'passagem-de-turno-cavalo'],
        };

        $turno = Ocorrencia::query()
            ->when($companyId !== null, fn ($q) => $q->where('company_id', $companyId))
            ->whereIn('type', $types)
            ->latest('created_at')
            ->first();

        if (! $turno || ! is_array($turno->form)) {
            return;
        }

        $this->lastTurnoLabel = 'Passagem de Turno';

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

        $this->lastTurnoDate = $turno->created_at->format('d/m/Y H:i');
    }

    public function refreshOcorrencias()
    {
        $user = auth()->user();

        // Employees não usam polling
        if ($user->isEmployee()) {
            return;
        }

        $query = Ocorrencia::latest()->limit(5)->with('user:id,name,avatar,cargo');

        // Manager → apenas da empresa
        if ($user->isManager()) {
            $query->where('company_id', $user->company_id);
        }

        $this->lastOcorrencias = $query->get();

        // 📊 KPIs acompanham o polling
        $this->loadKpis();
    }

    /** 🏷️ Badge colorido por tipo de ocorrência */
    public function typeBadge(string $type): array
    {
        return match ($type) {
            'branco' => ['label' => 'Em branco', 'class' => 'bg-slate-100 text-slate-700'],
            'passagem-de-turno', 'passagem-de-turno-cavalo' => ['label' => 'Passagem de Turno', 'class' => 'bg-blue-100 text-blue-700'],
            'ocorrencias-diarias' => ['label' => 'Diárias', 'class' => 'bg-amber-100 text-amber-700'],
            'varreduras-fichas-sistemas' => ['label' => 'Varreduras', 'class' => 'bg-emerald-100 text-emerald-700'],
            default => ['label' => ucfirst($type), 'class' => 'bg-gray-100 text-gray-700'],
        };
    }

    public function render()
    {
        $user = auth()->user();

        if ($user->isEmployee()) {
            return view('livewire.dashboard.dashboard-employee', [
                'title' => 'Meu Painel - '.config('app.name'),
            ]);
        }

        return view('livewire.dashboard.dashboard', [
            'title' => 'Painel de Controle - '.config('app.name'),
        ]);
    }
}
