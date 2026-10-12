<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-address-book mr-2"></i> Clientes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Clientes</li>
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
                        placeholder="Nome, e-mail, CPF ou WhatsApp">
                </div>

                <select wire:model.live="filterStatus" class="form-control form-control-sm" style="width: auto; min-width: 120px;">
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

                @if ($search !== '' || $filterStatus !== '' || $filterCompany !== '')
                    <button type="button" wire:click="clearFilters" class="btn btn-sm btn-outline-secondary" title="Limpar filtros">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>
        </div>
        <!-- /.card-header -->

        <div class="card-body">
            @if (!empty($clientes) && $clientes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table table-bordered table-striped projects">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>E-mail</th>
                                <th>WhatsApp</th>
                                <th class="text-center">Pré-reservas</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientes as $cliente)
                                <tr>
                                    <td>{{ $cliente->name }}</td>
                                    <td>{{ $cliente->cpf ?: '—' }}</td>
                                    <td>{{ $cliente->email ?: '—' }}</td>
                                    <td>{{ $cliente->whatsapp ?: '—' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $cliente->reservations_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($cliente->isAtivo())
                                            <span class="badge badge-success">Ativo</span>
                                        @else
                                            <span class="badge badge-secondary">Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center gap-2">
                                            @if ($cliente->whatsapp)
                                                <a href="{{ $cliente->whatsappLink() }}" target="_blank" rel="noopener"
                                                    class="btn btn-xs bg-green-600 text-white" title="Chamar no WhatsApp">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            @endif
                                            <button class="btn btn-xs btn-info" title="Visualizar"
                                                wire:click="viewCustomer({{ $cliente->id }})">
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

        <div class="card-footer clearfix">
            {{ $clientes->links() }}
        </div>
    </div>

    {{-- Modal de detalhes do cliente --}}
    @if ($showModal && $customerSelected)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]" x-data>
            <div class="bg-white w-[640px] max-h-[85vh] overflow-y-auto rounded-lg shadow-lg p-6 relative animate-fade">
                <button type="button" class="absolute top-2 right-2 text-xl"
                    wire:click="$set('showModal', false)">
                    ✕
                </button>

                <h2 class="text-xl font-bold mb-4">Detalhes do Cliente</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <p><strong>Nome:</strong> {{ $customerSelected->name }}</p>
                        <p><strong>CPF:</strong> {{ $customerSelected->cpf ?: '—' }}</p>
                        <p><strong>RG:</strong> {{ $customerSelected->rg ?: '—' }}</p>
                        <p><strong>Nascimento:</strong> {{ $customerSelected->birthday ? $customerSelected->birthday->format('d/m/Y') : '—' }}</p>
                        <p><strong>Status:</strong> {{ $customerSelected->isAtivo() ? 'Ativo' : 'Inativo' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p><strong>E-mail:</strong> {{ $customerSelected->email ?: '—' }}</p>
                        <p><strong>WhatsApp:</strong> {{ $customerSelected->whatsapp ?: '—' }}</p>
                        @if ($customerSelected->whatsapp)
                            <a href="{{ $customerSelected->whatsappLink() }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-1 px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                <i class="fab fa-whatsapp"></i> Enviar mensagem
                            </a>
                        @endif
                        <p><strong>Endereço:</strong>
                            {{ trim(($customerSelected->street ?? '') . ', ' . ($customerSelected->number ?? '')) ?: '—' }}
                        </p>
                        <p><strong>Bairro:</strong> {{ $customerSelected->neighborhood ?: '—' }}</p>
                        <p><strong>Cidade:</strong> {{ trim(($customerSelected->city ?? '') . ' ' . ($customerSelected->estate ?? '')) ?: '—' }}</p>
                    </div>
                </div>

                <div class="mt-4 border-t pt-4">
                    <h3 class="font-semibold text-gray-700 mb-2">
                        Pré-reservas ({{ $customerSelected->reservations->count() }})
                    </h3>

                    @if ($customerSelected->reservations->count() > 0)
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th class="text-center">Check-in</th>
                                    <th class="text-center">Checkout</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customerSelected->reservations->sortByDesc('checkin') as $reserva)
                                    <tr>
                                        <td><code>{{ $reserva->codigo }}</code></td>
                                        <td class="text-center">{{ optional($reserva->checkin)->format('d/m/Y') }}</td>
                                        <td class="text-center">{{ optional($reserva->checkout)->format('d/m/Y') }}</td>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500">Este cliente ainda não possui pré-reservas.</p>
                    @endif
                </div>

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
