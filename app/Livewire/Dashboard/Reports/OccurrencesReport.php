<?php

namespace App\Livewire\Dashboard\Reports;

use App\Models\Ocorrencia;
use Livewire\Component;

class OccurrencesReport extends Component
{
    public array $chartByType = [];
    public array $chartByPeriod = [];
    public array $lastTurno = [];
    public ?string $lastTurnoDate = null;

    public function mount()
    {
        $user = auth()->user();

        // 📊 Ocorrências por tipo
        $this->chartByType = Ocorrencia::query()
            ->where('company_id', $user->company_id)
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->get()
            ->mapWithKeys(fn ($row) => [
                $this->mapTypeLabel($row->type) => $row->total
            ])
            ->toArray();

        // 📅 Períodos
        $this->chartByPeriod = [
            'Semana' => Ocorrencia::where('company_id', $user->company_id)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),

            'Mês' => Ocorrencia::where('company_id', $user->company_id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),

            'Ano' => Ocorrencia::where('company_id', $user->company_id)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        $turno = Ocorrencia::query()
            ->where('company_id', $user->company_id)
            ->where('type', 'passagem-de-turno')
            ->latest('created_at')
            ->first();

        if (! $turno || ! is_array($turno->form)) {
            return;
        }

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

    protected function mapTypeLabel(string $type): string
    {
        return match ($type) {
            'passagem-de-turno' => 'Passagem de Turno',
            'ocorrencias-diarias' => 'Ocorrências Diárias',
            'varreduras-fichas-sistemas' => 'Varreduras',
            default => ucfirst($type),
        };
    }

    public function render()
    {
        $title = 'Relatórios de Ocorrências';
        return view('livewire.dashboard.reports.occurrences-report')->with('title', $title);
    }
}
