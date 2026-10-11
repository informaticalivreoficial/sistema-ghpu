<div>
    @section('title', $title)

    {{-- Header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-chart-pie mr-2"></i> Relatório de Ocorrências</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Relatórios de Ocorrências</li>
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
                    <span wire:loading.delay class="text-xs text-slate-400">
                        <i class="fas fa-circle-notch fa-spin mr-1"></i>Atualizando...
                    </span>
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
                @else
                    {{-- 🔒 Manager: sempre a própria empresa --}}
                    @if (auth()->user()->company?->alias_name)
                        <div class="flex items-center gap-2 rounded-xl bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-600">
                            <i class="fas fa-building text-slate-400"></i> {{ auth()->user()->company->alias_name }}
                        </div>
                    @endif
                @endif
            </div>

            @if ($totalPeriod > 0 || $hasTurno)
                {{-- 🔹 Cards resumo --}}
                <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-5 text-white shadow-lg shadow-blue-500/25">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-blue-100">Ocorrências no período</p>
                                <p class="mt-1 text-3xl font-bold">{{ $totalPeriod }}</p>
                            </div>
                            <div class="rounded-xl bg-white/20 p-3">
                                <i class="fas fa-clipboard-list text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-5 text-white shadow-lg shadow-emerald-500/25">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-emerald-100">Passagens de turno</p>
                                <p class="mt-1 text-3xl font-bold">{{ $totalTurnos }}</p>
                            </div>
                            <div class="rounded-xl bg-white/20 p-3">
                                <i class="fas fa-exchange-alt text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 p-5 text-white shadow-lg shadow-violet-500/25">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-violet-100">Última passagem</p>
                                <p class="mt-1 text-2xl font-bold">{{ $lastTurnoDate ?? '—' }}</p>
                            </div>
                            <div class="rounded-xl bg-white/20 p-3">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 🔹 Gráficos --}}
                <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-5">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
                        <h3 class="mb-4 flex items-center text-sm font-bold uppercase tracking-wide text-slate-500">
                            <i class="fas fa-chart-pie mr-2 text-blue-500"></i> Ocorrências por tipo
                        </h3>
                        <div class="relative mx-auto h-72 max-w-xs" wire:ignore>
                            <canvas id="chartByType"></canvas>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-3">
                        <h3 class="mb-4 flex items-center text-sm font-bold uppercase tracking-wide text-slate-500">
                            <i class="fas fa-chart-line mr-2 text-emerald-500"></i> Últimos 6 meses
                        </h3>
                        <div class="relative h-72" wire:ignore>
                            <canvas id="chartTrend"></canvas>
                        </div>
                    </div>
                </div>

                {{-- 🔹 Última passagem de turno --}}
                @if ($hasTurno)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                            <h3 class="flex items-center text-sm font-bold uppercase tracking-wide text-slate-500">
                                <i class="fas fa-exchange-alt mr-2 text-violet-500"></i> Dados da última passagem
                            </h3>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700">
                                    {{ $lastTurnoLabel }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    <i class="far fa-clock mr-1"></i>{{ $lastTurnoDate }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                            <div class="flex items-center gap-3 rounded-xl bg-blue-50 p-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-slate-800">{{ $lastTurno['hospedes'] }}</p>
                                    <p class="text-xs font-medium text-slate-500">Hóspedes</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 rounded-xl bg-emerald-50 p-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                    <i class="fas fa-bed"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-slate-800">{{ $lastTurno['aptos'] }}</p>
                                    <p class="text-xs font-medium text-slate-500">Aptos ocupados</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 rounded-xl bg-amber-50 p-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-slate-800">{{ $lastTurno['reservas'] }}</p>
                                    <p class="text-xs font-medium text-slate-500">Reservas</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 rounded-xl bg-rose-50 p-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-slate-800">{{ $lastTurno['checkouts'] }}</p>
                                    <p class="text-xs font-medium text-slate-500">Check-outs</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 rounded-xl bg-violet-50 p-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-slate-800">{{ $lastTurno['cartoes'] }}</p>
                                    <p class="text-xs font-medium text-slate-500">Cartões emprestados</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 rounded-xl bg-teal-50 p-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-100 text-teal-600">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-slate-800">R$ {{ number_format($lastTurno['caixa_dinheiro'], 2, ',', '.') }}</p>
                                    <p class="text-xs font-medium text-slate-500">Caixa (dinheiro)</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 rounded-xl bg-slate-100 p-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-200 text-slate-600">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-slate-800">R$ {{ number_format($lastTurno['caixa_cartao'], 2, ',', '.') }}</p>
                                    <p class="text-xs font-medium text-slate-500">Caixa (cartões)</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 rounded-xl bg-indigo-50 p-4 ring-2 ring-indigo-200">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                                    <i class="fas fa-cash-register"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-slate-800">R$ {{ number_format($lastTurno['caixa_total'], 2, ',', '.') }}</p>
                                    <p class="text-xs font-medium text-slate-500">Caixa total</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                {{-- 🔹 Vazio --}}
                <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 py-16 text-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                        <i class="fas fa-chart-pie text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-base font-semibold text-slate-600">Nenhuma ocorrência encontrada</p>
                    <p class="mt-1 text-sm text-slate-400">Não há ocorrências registradas no período selecionado.</p>
                </div>
            @endif

        </div>
    </div>

    @script
    <script>
        let chartByType = null;
        let chartTrend = null;

        const trendPalette = {
            line: '#3b82f6',
            fill: 'rgba(59, 130, 246, 0.10)',
        };

        function renderCharts() {
            // 🔹 Lê dados frescos via $wire — a diretiva script não re-executa em updates do Livewire
            const typesData = $wire.chartByType;
            const trendData = $wire.chartTrend;

            // 🔹 Por tipo (doughnut)
            const typeEl = document.getElementById('chartByType');
            if (typeEl) {
                chartByType?.destroy();

                const labels = (typesData.labels || []).filter((_, i) => typesData.values[i] > 0);
                const values = labels.map((l) => typesData.values[typesData.labels.indexOf(l)]);

                chartByType = new Chart(typeEl, {
                    type: 'doughnut',
                    data: {
                        labels: labels.length ? labels : ['Sem dados'],
                        datasets: [{
                            data: values.length ? values : [1],
                            backgroundColor: values.length ? ['#3b82f6', '#10b981', '#f59e0b', '#64748b', '#8b5cf6'] : ['#e2e8f0'],
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

            // 🔹 Tendência 6 meses (linha)
            const trendEl = document.getElementById('chartTrend');
            if (trendEl) {
                chartTrend?.destroy();

                chartTrend = new Chart(trendEl, {
                    type: 'line',
                    data: {
                        labels: trendData.labels || [],
                        datasets: [{
                            label: 'Ocorrências',
                            data: trendData.values || [],
                            borderColor: trendPalette.line,
                            backgroundColor: trendPalette.fill,
                            borderWidth: 3,
                            tension: 0.35,
                            fill: true,
                            pointBackgroundColor: trendPalette.line,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false } },
                            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0 } },
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
