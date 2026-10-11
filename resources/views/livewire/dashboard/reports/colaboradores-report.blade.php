<div>
    @section('title', $title)

    {{-- Header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-users-cog mr-2"></i> Relatório de Colaboradores</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.occurrences') }}">Relatórios</a></li>
                        <li class="breadcrumb-item active">Colaboradores</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-body">

            {{-- 🔹 Filtros --}}
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-medium text-slate-500">Período:</span>
                    <div class="inline-flex rounded-xl bg-slate-100 p-1">
                        @foreach (['semana' => 'Semana', 'mes' => 'Mês', 'ano' => 'Ano', 'tudo' => 'Tudo'] as $value => $label)
                            <button
                                type="button"
                                wire:click="$set('filterPeriod', '{{ $value }}')"
                                class="rounded-lg px-4 py-1.5 text-sm font-semibold transition-all duration-200
                                    {{ $filterPeriod === $value
                                        ? 'bg-white text-blue-600 shadow-sm ring-1 ring-blue-200'
                                        : 'text-slate-500 hover:text-slate-700' }}"
                            >
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-slate-500">Empresa:</span>
                        <select wire:model.live="filterCompany" class="min-w-[13rem] rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">Todas as empresas</option>
                            @foreach ($companyOptions as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            @if (count($report))
                {{-- 🔹 Cards resumo --}}
                <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-5 text-white shadow-lg shadow-blue-500/25">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-blue-100">Colaboradores ativos</p>
                                <p class="mt-1 text-3xl font-bold">{{ count($report) }}</p>
                            </div>
                            <div class="rounded-xl bg-white/20 p-3">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-5 text-white shadow-lg shadow-emerald-500/25">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-emerald-100">Total de ocorrências</p>
                                <p class="mt-1 text-3xl font-bold">{{ $totalOcorrencias }}</p>
                            </div>
                            <div class="rounded-xl bg-white/20 p-3">
                                <i class="fas fa-clipboard-list text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 p-5 text-white shadow-lg shadow-violet-500/25">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-violet-100">Média por colaborador</p>
                                <p class="mt-1 text-3xl font-bold">{{ number_format($avgPerColaborador, 1, ',', '') }}</p>
                            </div>
                            <div class="rounded-xl bg-white/20 p-3">
                                <i class="fas fa-chart-line text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 🔹 Gráficos --}}
                <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-5">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-3">
                        <h3 class="mb-4 flex items-center text-sm font-bold uppercase tracking-wide text-slate-500">
                            <i class="fas fa-trophy mr-2 text-amber-500"></i> Ranking de atividade
                        </h3>
                        <div class="relative h-72" wire:ignore>
                            <canvas id="chartRanking"></canvas>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
                        <h3 class="mb-4 flex items-center text-sm font-bold uppercase tracking-wide text-slate-500">
                            <i class="fas fa-chart-pie mr-2 text-blue-500"></i> Ocorrências por tipo
                        </h3>
                        <div class="relative mx-auto h-72 max-w-xs" wire:ignore>
                            <canvas id="chartTypes"></canvas>
                        </div>
                    </div>
                </div>

                {{-- 🔹 Tabela --}}
                <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">#</th>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Colaborador</th>
                                @if ($showCompany)
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Empresa</th>
                                @endif
                                <th class="px-5 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Em branco</th>
                                <th class="px-5 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Turno</th>
                                <th class="px-5 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Diárias</th>
                                <th class="px-5 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Varreduras</th>
                                <th class="px-5 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Total</th>
                                <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Último registro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($report as $index => $row)
                                <tr class="transition-colors duration-150 hover:bg-blue-50/50">
                                    <td class="px-5 py-3">
                                        @if ($index === 0)
                                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-amber-100 text-sm font-bold text-amber-600"><i class="fas fa-crown text-xs"></i></span>
                                        @elseif ($index === 1)
                                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-200 text-sm font-bold text-slate-600">2</span>
                                        @elseif ($index === 2)
                                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-orange-100 text-sm font-bold text-orange-600">3</span>
                                        @else
                                            <span class="text-sm font-medium text-slate-400">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            <img class="h-9 w-9 rounded-full object-cover ring-2 ring-white shadow" src="{{ $row['avatar_url'] }}" alt="{{ $row['name'] }}">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-800">{{ $row['name'] }}</p>
                                                @if ($row['cargo'])
                                                    <p class="text-xs text-slate-400">{{ $row['cargo'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    @if ($showCompany)
                                        <td class="px-5 py-3 text-sm text-slate-600">{{ $row['company'] ?? '—' }}</td>
                                    @endif
                                    <td class="px-5 py-3 text-center">
                                        <span class="inline-flex min-w-[2rem] justify-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $row['branco'] > 0 ? 'bg-slate-100 text-slate-700' : 'text-slate-300' }}">
                                            {{ $row['branco'] ?: '—' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="inline-flex min-w-[2rem] justify-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $row['passagem-de-turno'] > 0 ? 'bg-blue-100 text-blue-700' : 'text-slate-300' }}">
                                            {{ $row['passagem-de-turno'] ?: '—' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="inline-flex min-w-[2rem] justify-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $row['ocorrencias-diarias'] > 0 ? 'bg-amber-100 text-amber-700' : 'text-slate-300' }}">
                                            {{ $row['ocorrencias-diarias'] ?: '—' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="inline-flex min-w-[2rem] justify-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $row['varreduras-fichas-sistemas'] > 0 ? 'bg-emerald-100 text-emerald-700' : 'text-slate-300' }}">
                                            {{ $row['varreduras-fichas-sistemas'] ?: '—' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="inline-flex items-center justify-center rounded-full bg-blue-600 px-3 py-1 text-xs font-bold text-white">
                                            {{ $row['total'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-right text-sm text-slate-500">
                                        @if ($row['last_at'])
                                            <i class="far fa-clock mr-1 text-slate-400"></i>{{ $row['last_at'] }}
                                        @else
                                            <span class="text-slate-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                {{-- 🔹 Vazio --}}
                <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 py-16 text-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                        <i class="fas fa-user-slash text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-base font-semibold text-slate-600">Nenhuma atividade encontrada</p>
                    <p class="mt-1 text-sm text-slate-400">Não há ocorrências registradas no período selecionado.</p>
                </div>
            @endif

        </div>
    </div>

    @script
    <script>
        let chartRanking = null;
        let chartTypes = null;

        const palette = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f97316', '#ec4899', '#14b8a6', '#6366f1'];

        function renderCharts() {
            // 🔹 Lê dados frescos via $wire — a diretiva script não re-executa em updates do Livewire
            const rankingData = $wire.chartTop;
            const typesData = $wire.typeTotals;

            // 🔹 Ranking (barras horizontais)
            const rankEl = document.getElementById('chartRanking');
            if (rankEl) {
                chartRanking?.destroy();
                chartRanking = new Chart(rankEl, {
                    type: 'bar',
                    data: {
                        labels: rankingData.labels,
                        datasets: [{
                            label: 'Ocorrências',
                            data: rankingData.totals,
                            backgroundColor: rankingData.labels.map((_, i) => palette[i % palette.length]),
                            borderRadius: 8,
                            borderSkipped: false,
                        }],
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { precision: 0 } },
                            y: { grid: { display: false } },
                        },
                    },
                });
            }

            // 🔹 Tipos (doughnut)
            const typeEl = document.getElementById('chartTypes');
            if (typeEl) {
                chartTypes?.destroy();
                const labels = Object.keys(typesData).filter((k) => typesData[k] > 0);
                const values = labels.map((k) => typesData[k]);

                chartTypes = new Chart(typeEl, {
                    type: 'doughnut',
                    data: {
                        labels: labels.length ? labels : ['Sem dados'],
                        datasets: [{
                            data: values.length ? values : [1],
                            backgroundColor: values.length ? ['#64748b', '#3b82f6', '#f59e0b', '#10b981'] : ['#e2e8f0'],
                            borderWidth: 0,
                            hoverOffset: 6,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { boxWidth: 12, padding: 16, usePointStyle: true },
                            },
                        },
                    },
                });
            }
        }

        renderCharts();
        on('filters-updated', () => renderCharts());
    </script>
    @endscript
</div>
