<?php

namespace App\Livewire\Dashboard\Reports;

use App\Models\Company;
use Livewire\Component;

class Companies extends Component
{

    public array $chartActiveInactiveCompanies = [];

    public function mount()
    {
        $this->chartActiveInactiveCompanies = $this->getChartActiveInactiveCompanies();
    }

    public function render()
    {
        return view('livewire.dashboard.reports.companies');
    }

    public function getChartActiveInactiveCompanies()
    {
        $activeCount = Company::where('status', 1)->count();
        $inactiveCount = Company::where('status', 0)->count();

        return [
            'labels' => ['Ativas', 'Inativas'],
            'datasets' => [
                [
                    'label' => 'Empresas',
                    'data' => [$activeCount, $inactiveCount],
                    'backgroundColor' => ['#3b82f6', '#ef4444'],
                ]
            ]
        ];
    }
}
