<?php

namespace App\Livewire\Dashboard\Reports;

use App\Models\Manifest;
use Livewire\Component;

class Manifests extends Component
{

    public array $chartCargoRepositionManifests = [];

    public $chartStatusData = [];

    public array $chartMonthlyStacked = [];

    public function mount()
    {
        $this->chartCargoRepositionManifests = $this->getChartCargoRepositionManifests();
        $this->chartStatusData = $this->getChartStatusData();
        $this->chartMonthlyStacked = $this->getMonthlyStackedChartData();
    }

    public function render()
    {
        $title = 'Relatório de Manifestos de carga e reposição';
        return view('livewire.dashboard.reports.manifests')->with('title', $title);
    }

    public function getChartCargoRepositionManifests()
    {
        $year = now()->year;

        $cargoCount = Manifest::where('object', 'carga')->whereYear('created_at', $year)->count();
        $repositionCount = Manifest::where('object', 'reposição')->whereYear('created_at', $year)->count();

        return [
            'labels' => ['Carga', 'Reposição'],
            'datasets' => [
                [
                    'label' => 'Manifestos',
                    'data' => [$cargoCount, $repositionCount],
                    'backgroundColor' => ['#3b82f6', '#ef4444'],
                ]
            ]
        ];
    }

    public function getChartStatusData()
    {
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;

        return Manifest::selectRaw('YEAR(created_at) as year, status, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)
            ->orWhereYear('created_at', $previousYear)
            ->groupBy('year', 'status')
            ->orderBy('year')
            ->get()
            ->groupBy('status')
            ->map(fn ($items) => $items->pluck('total', 'year')->toArray())
            ->toArray();
    }

    public function getMonthlyStackedChartData()
    {
        $year = now()->year;

        $data = Manifest::selectRaw('MONTH(created_at) as month, object, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->whereIn('object', ['carga', 'reposição'])
            ->groupBy('month', 'object')
            ->orderBy('month')
            ->get();

        // Inicializa dados zerados para cada mês
        $months = collect(range(1, 12))->mapWithKeys(fn($m) => [$m => ['carga' => 0, 'reposição' => 0]]);

        foreach ($data as $row) {
            $current = $months->get($row->month);
            $current[$row->object] = $row->total;
            $months->put($row->month, $current);
        }

        return [
            'labels' => $months->keys()->map(fn($m) => now()->setMonth($m)->format('M'))->values(),
            'datasets' => [
                [
                    'label' => 'Carga',
                    'backgroundColor' => '#14b8a6',
                    'data' => $months->pluck('carga')->values(),
                ],
                [
                    'label' => 'Reposição',
                    'backgroundColor' => '#f59e0b',
                    'data' => $months->pluck('reposição')->values(),
                ],
            ],
        ];
    }
}
