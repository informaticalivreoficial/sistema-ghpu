<div
    x-data="{
        openLightbox(src) {
            basicLightbox.create(`<img src='${src}' style='max-width:90vw; max-height:90vh;'>`).show()
        }
    }"
>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8">
                    <h2 class="m-0 text-dark">Painel de Controle</h2>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Início</a></li>
                        <li class="breadcrumb-item active">Painel de Controle</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            {{-- 🔹 KPIs --}}
            <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
                <div class="rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 p-3 text-white shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-medium uppercase tracking-wide text-blue-100">Hoje</p>
                            <p class="mt-0.5 text-2xl font-bold leading-tight">{{ $kpiHoje }}</p>
                        </div>
                        <div class="rounded-lg bg-white/20 p-2">
                            <i class="fas fa-calendar-day text-sm"></i>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-3 text-white shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-medium uppercase tracking-wide text-emerald-100">Semana</p>
                            <p class="mt-0.5 text-2xl font-bold leading-tight">{{ $kpiSemana }}</p>
                        </div>
                        <div class="rounded-lg bg-white/20 p-2">
                            <i class="fas fa-calendar-week text-sm"></i>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 p-3 text-white shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-medium uppercase tracking-wide text-violet-100">Mês</p>
                            <p class="mt-0.5 text-2xl font-bold leading-tight">{{ $kpiMes }}</p>
                        </div>
                        <div class="rounded-lg bg-white/20 p-2">
                            <i class="fas fa-calendar text-sm"></i>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-gradient-to-br from-slate-500 to-slate-600 p-3 text-white shadow-md">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-[11px] font-medium uppercase tracking-wide text-slate-100">
                                {{ $userCompany ? 'Empresa' : 'Visão' }}
                            </p>
                            <p class="mt-0.5 truncate text-sm font-bold leading-tight">
                                {{ $userCompany ?? 'Todas as empresas' }}
                            </p>
                        </div>
                        <div class="ml-2 shrink-0 rounded-lg bg-white/20 p-2">
                            <i class="fas fa-building text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 🔁 Última passagem de turno --}}
            @if ($lastTurno)
                <div class="mb-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
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

            {{-- 📋 Últimas ocorrências --}}
            <div class="mb-4 rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h3 class="flex items-center text-sm font-bold uppercase tracking-wide text-slate-500">
                        <i class="fas fa-clipboard-list mr-2 text-blue-500"></i> Últimas Ocorrências
                    </h3>
                    <a href="{{ route('reports.occurrences') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                        Ver relatório <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div wire:poll.5s="refreshOcorrencias" class="divide-y divide-slate-100">
                    @forelse($lastOcorrencias as $ocorrencia)
                        <div class="flex items-center gap-3 px-5 py-3 transition-colors duration-150 hover:bg-blue-50/50">

                            <!-- AVATAR -->
                            <img
                                src="{{ $ocorrencia->user->avatar
                                    ? asset('storage/'.$ocorrencia->user->avatar)
                                    : asset('theme/images/image.jpg') }}"
                                class="h-10 w-10 flex-shrink-0 rounded-full object-cover ring-2 ring-white shadow"
                                alt="{{ $ocorrencia->user->name }}"
                            >

                            <!-- CONTEÚDO -->
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="text-sm font-semibold text-slate-800">{{ $ocorrencia->user->name }}</p>
                                    @if ($ocorrencia->user->cargo)
                                        <p class="text-xs text-slate-400">{{ $ocorrencia->user->cargo }}</p>
                                    @endif
                                    @if ($ocorrencia->type)
                                        @php
                                            $badge = $__livewire->typeBadge($ocorrencia->type);
                                        @endphp
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ $badge['class'] }}">
                                            {{ $badge['label'] }}
                                        </span>
                                    @endif
                                </div>
                                <p class="truncate text-sm text-slate-600">{{ $ocorrencia->title ?? 'Ocorrência' }}</p>
                            </div>

                            <!-- DATA -->
                            <div class="hidden whitespace-nowrap text-right text-xs text-slate-500 sm:block">
                                <i class="far fa-clock mr-1 text-slate-400"></i>{{ \Carbon\Carbon::parse($ocorrencia->created_at)->format('d/m/Y H:i') }}
                            </div>

                            <!-- AÇÕES -->
                            <div class="flex flex-shrink-0 items-center gap-2">
                                @if(\Carbon\Carbon::parse($ocorrencia->created_at)->gt(now()->subDay()))
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700">
                                        Nova
                                    </span>
                                @endif
                                <a target="_blank"
                                    href="{{ route('ocorrencia.pdf', $ocorrencia->id) }}"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                                    <i class="fas fa-search"></i>
                                </a>
                            </div>

                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                                <i class="fas fa-clipboard text-slate-400"></i>
                            </div>
                            <p class="text-sm font-semibold text-slate-600">Nenhuma ocorrência encontrada</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @role(['super-admin'])
            <livewire:dashboard.reports.dashboard-stats />
            @endrole

            <div class="row">
                @role(['super-admin', 'admin'])
                <livewire:dashboard.github-updates />
                @endrole

                <div class="col-lg-8">
                    @role(['super-admin'])
                        <div class="card">
                            <div class="card-header border-transparent">
                                <h3 class="card-title">Top 5 Posts mais visitados</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>Imagem</th>
                                                <th>Título</th>
                                                <th>Status</th>
                                                <th>Visitas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topposts as $post)
                                                <tr>
                                                    <td>
                                                        <img src="{{ $post->cover() }}"
                                                            width="60"
                                                            style="cursor:pointer; border-radius:4px"
                                                            @click="openLightbox('{{ $post->cover() }}')"
                                                            >
                                                    </td>
                                                    <td>{{ $post->title }}</td>
                                                    <td>
                                                        @php
                                                            $badge = [
                                                                1 => 'success',
                                                                0 => 'warning'
                                                            ][$post->status] ?? 'secondary';
                                                            $status = [
                                                                1 => 'Ativo',
                                                                0 => 'Inativo'
                                                            ][$post->status] ?? '';
                                                        @endphp

                                                        <span class="badge badge-{{ $badge }}">
                                                            {{ $status }}
                                                        </span>
                                                    </td>

                                                    <td>{{ $post->views }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-3">
                                                        Nenhum post encontrado.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer clearfix">
                                <a href="{{route('posts.create')}}" class="btn btn-sm btn-info float-left">Cadastrar Novo</a>
                                <a href="{{route('posts.index')}}" class="btn btn-sm btn-secondary float-right">Ver Todos</a>
                            </div>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>

</div>
