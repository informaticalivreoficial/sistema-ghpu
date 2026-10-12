<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Meu Painel</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Início</a></li>
                        <li class="breadcrumb-item active">Meu Painel</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7">
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-3 border-b border-slate-100 flex items-center">
                            <h3 class="flex items-center text-sm font-semibold text-slate-800">
                                <i class="fas fa-clipboard-list mr-2 text-blue-500"></i> Últimas Ocorrências
                            </h3>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse($lastOcorrencias as $ocorrencia)
                            <div class="flex items-center gap-3 px-6 py-3 transition-colors duration-150 hover:bg-blue-50/50">

                                <!-- AVATAR -->
                                <img src="{{ $ocorrencia->user?->avatar
                                        ? asset('storage/'.$ocorrencia->user->avatar)
                                        : asset('theme/images/image.jpg') }}"
                                    class="h-10 w-10 flex-shrink-0 rounded-full object-cover ring-2 ring-white shadow"
                                    alt="{{ $ocorrencia->user?->name ?? 'Colaborador' }}">

                                <!-- CONTEÚDO -->
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-sm font-semibold text-slate-800">{{ $ocorrencia->user?->name ?? '—' }}</p>
                                        @if ($ocorrencia->type)
                                            @php
                                                $badge = $__livewire->typeBadge($ocorrencia->type);
                                            @endphp
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ $badge['class'] }}">
                                                {{ $badge['label'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="truncate text-sm text-slate-600">{{ Str::limit($ocorrencia->title ?? 'Ocorrência', 60) }}</p>
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
                                    <a target="_blank" href="{{ route('ocorrencia.pdf', $ocorrencia->id) }}"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                                        <i class="fas fa-search"></i>
                                    </a>
                                </div>

                            </div>
                            @empty
                            <div class="flex flex-col items-center justify-center px-6 py-10 text-center">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                                    <i class="fas fa-clipboard text-slate-400"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Nenhuma ocorrência encontrada</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    {{-- 📅 Últimas pré-reservas --}}
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-3 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="flex items-center text-sm font-semibold text-slate-800">
                                <i class="fas fa-calendar-check mr-2 text-amber-500"></i> Últimas Pré-reservas
                            </h3>
                            <a href="{{ route('reservas.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                Ver todas <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse($lastReservas as $reserva)
                                <div class="flex items-center gap-3 px-6 py-3 transition-colors duration-150 hover:bg-amber-50/50">
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600 shadow ring-2 ring-white">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="text-sm font-semibold text-slate-800"><code>{{ $reserva->codigo }}</code></p>
                                            @php
                                                $badgeClass = match ($reserva->status) {
                                                    'confirmada' => 'bg-emerald-100 text-emerald-700',
                                                    'hospedada' => 'bg-blue-100 text-blue-700',
                                                    'finalizada' => 'bg-slate-100 text-slate-700',
                                                    'cancelada' => 'bg-rose-100 text-rose-700',
                                                    default => 'bg-amber-100 text-amber-700',
                                                };
                                            @endphp
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ $badgeClass }}">
                                                {{ \App\Models\Reservation::STATUSES[$reserva->status] ?? $reserva->status }}
                                            </span>
                                        </div>
                                        <p class="truncate text-xs text-slate-600">
                                            {{ $reserva->customer->name ?? '—' }}
                                        </p>
                                    </div>

                                    <div class="hidden whitespace-nowrap text-right text-xs text-slate-500 sm:block">
                                        <i class="far fa-calendar mr-1 text-slate-400"></i>{{ optional($reserva->checkin)->format('d/m/Y') }}
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center px-6 py-10 text-center">
                                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                                        <i class="fas fa-calendar text-slate-400"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-600">Nenhuma pré-reserva encontrada</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
