<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-calendar-check mr-2"></i> Pré-reservas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Pré-reservas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{-- Filtros em linha única (mesmo padrão da listagem de ocorrências) --}}
            <div class="d-flex flex-wrap align-items-center gap-2">
                <div class="input-group input-group-sm" style="width: 230px;">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control"
                        placeholder="Cliente, código ou apto">
                </div>

                <select wire:model.live="filterStatus" class="form-control form-control-sm" style="width: auto; min-width: 130px;">
                    <option value="">Status: todos</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>

                @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                    <select wire:model.live="filterCompany" class="form-control form-control-sm" style="width: auto; min-width: 150px;">
                        <option value="">Empresa: todas</option>
                        @foreach ($companyOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                @endif

                <div class="input-group input-group-sm" style="width: 155px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Check-in de</span>
                    </div>
                    <input type="date" wire:model.live="filterDateFrom" class="form-control">
                </div>

                <div class="input-group input-group-sm" style="width: 155px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Até</span>
                    </div>
                    <input type="date" wire:model.live="filterDateTo" class="form-control">
                </div>

                @if ($search !== '' || $filterStatus !== '' || $filterCompany !== '' || $filterDateFrom !== '' || $filterDateTo !== '')
                    <button type="button" wire:click="clearFilters" class="btn btn-sm btn-outline-secondary" title="Limpar filtros">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>
        </div>
        <!-- /.card-header -->

        <div class="card-body">
            @if (!empty($reservas) && $reservas->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table table-bordered table-striped projects">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th class="text-center">Apto</th>
                                <th class="text-center">Check-in</th>
                                <th class="text-center">Checkout</th>
                                <th class="text-center">Hóspedes</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservas as $reserva)
                                <tr>
                                    <td><code>{{ $reserva->codigo }}</code></td>
                                    <td>{{ $reserva->customer->name ?? '—' }}</td>
                                    <td class="text-center">{{ $reserva->apartment->name ?? $reserva->apartamento_texto ?? '—' }}</td>
                                    <td class="text-center">{{ optional($reserva->checkin)->format('d/m/Y') }}</td>
                                    <td class="text-center">{{ optional($reserva->checkout)->format('d/m/Y') }}</td>
                                    <td class="text-center">{{ $reserva->adultos }} adulto(s) / {{ $reserva->criancas }} criança(s)</td>
                                    <td class="text-center">
                                        @php
                                            $badgeClass = match ($reserva->status) {
                                                'confirmada' => 'badge-success',
                                                'hospedada' => 'badge-info',
                                                'finalizada' => 'badge-secondary',
                                                'cancelada' => 'badge-danger',
                                                default => 'badge-warning',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ \App\Models\Reservation::STATUSES[$reserva->status] ?? $reserva->status }}</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center gap-2">
                                            @if ($reserva->customer?->whatsapp)
                                                <a href="{{ $reserva->customer->whatsappLink() }}" target="_blank" rel="noopener"
                                                    class="btn btn-xs bg-green-600 text-white" title="Chamar no WhatsApp">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            @endif
                                            <button class="btn btn-xs btn-info" title="Visualizar"
                                                wire:click="viewReserva({{ $reserva->id }})">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info p-3">
                            Não foram encontrados registros!
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if ($hasMore)
            {{-- Paginação infinita: carrega o próximo lote ao rolar até o fim --}}
            <div class="card-footer clearfix" id="pre-reservas-sentinel"
                x-data
                x-on:scroll.window.throttle.300ms="
                    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 400) {
                        $wire.loadMore();
                    }
                ">
                <div class="text-center">
                    <button type="button" wire:click="loadMore" wire:loading.remove wire:target="loadMore"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Carregar mais
                    </button>
                </div>
                <div wire:loading wire:target="loadMore" class="flex justify-center items-center mt-2">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <span class="ml-2 text-gray-600">Carregando...</span>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal de detalhes da reserva --}}
    @if ($showModal && $reservationSelected)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]" x-data>
            <div class="bg-white w-[640px] max-h-[85vh] overflow-y-auto rounded-lg shadow-lg p-6 relative animate-fade">
                <button type="button" class="absolute top-2 right-2 text-xl"
                    wire:click="$set('showModal', false)">
                    ✕
                </button>

                <h2 class="text-xl font-bold mb-4">Detalhes da Pré-reserva <code>{{ $reservationSelected->codigo }}</code></h2>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <h3 class="font-semibold text-gray-700">Cliente</h3>
                        <p><strong>Nome:</strong> {{ $reservationSelected->customer->name ?? '—' }}</p>
                        <p><strong>CPF:</strong> {{ $reservationSelected->customer->cpf ?: '—' }}</p>
                        <p><strong>E-mail:</strong> {{ $reservationSelected->customer->email ?: '—' }}</p>
                        <p><strong>WhatsApp:</strong> {{ $reservationSelected->customer->whatsapp ?: '—' }}</p>
                        <p><strong>Cidade:</strong> {{ trim(($reservationSelected->customer->city ?? '') . ' ' . ($reservationSelected->customer->estate ?? '')) ?: '—' }}</p>
                        @if ($reservationSelected->customer?->whatsapp)
                            <a href="{{ $reservationSelected->customer->whatsappLink() }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-1 px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                <i class="fab fa-whatsapp"></i> Enviar mensagem
                            </a>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-semibold text-gray-700">Estadia</h3>
                        <p><strong>Apto:</strong> {{ $reservationSelected->apartment->name ?? $reservationSelected->apartamento_texto ?? '—' }}</p>
                        <p><strong>Check-in:</strong> {{ optional($reservationSelected->checkin)->format('d/m/Y') }}</p>
                        <p><strong>Checkout:</strong> {{ optional($reservationSelected->checkout)->format('d/m/Y') }}</p>
                        <p><strong>Hóspedes:</strong> {{ $reservationSelected->adultos }} adulto(s) / {{ $reservationSelected->criancas }} criança(s)</p>
                    </div>
                </div>

                @if ($reservationSelected->information)
                    <p class="mt-3"><strong>Observações:</strong> {{ $reservationSelected->information }}</p>
                @endif

                @if (!auth()->user()->isEmployee())
                    <div class="mt-4 border-t pt-4">
                        <label class="font-semibold text-gray-700 block mb-2">Alterar status</label>
                        <div class="flex gap-2">
                            <select wire:model="modalStatus" class="form-control" style="max-width: 200px;">
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="button" wire:click="saveStatus" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Salvar
                            </button>
                        </div>
                    </div>
                @endif

                <div class="mt-4 text-right">
                    <button type="button" class="px-4 py-2 bg-gray-600 text-white rounded"
                        wire:click="$set('showModal', false)">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
