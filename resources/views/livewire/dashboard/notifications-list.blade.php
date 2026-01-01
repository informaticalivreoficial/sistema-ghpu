<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-bell mr-2"></i> Notificações</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Notificações</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">                
                <div class="col-12 my-2 text-right">
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <button
                            wire:click="markAllAsRead"
                            class="btn btn-sm btn-outline-success"
                        >
                            <i class="fas fa-check-double mr-1"></i>
                            Marcar todas como lidas
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            @forelse($notifications as $notification)
                <div
                    class="d-flex align-items-start p-3 border-bottom
                    {{ is_null($notification->read_at) ? 'bg-light' : '' }}"
                >
                    {{-- Ícone --}}
                    <div class="mr-3">
                        <span class="badge badge-warning p-2">
                            <i class="fas fa-bell"></i>
                        </span>
                    </div>

                    {{-- Conteúdo --}}
                    <div class="flex-grow-1">
                        <p class="mb-1 font-weight-bold">
                            {{ $notification->data['message'] ?? 'Nova notificação' }}
                        </p>

                        <small class="text-muted d-block">
                            Por <strong>{{ $notification->data['user_name'] ?? 'Sistema' }}</strong>
                        </small>

                        <small class="text-muted">
                            <i class="far fa-clock mr-1"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>

                    {{-- Ações --}}
                    <div class="ml-3 text-right">
                        @if(isset($notification->data['url']))
                            <a
                                href="{{ $notification->data['url'] }}"
                                target="_blank"
                                wire:click="markAsRead('{{ $notification->id }}')"
                                class="btn btn-sm btn-outline-primary mb-1"
                            >
                                Visualizar
                            </a>
                        @endif

                        @if(is_null($notification->read_at))
                            <button
                                wire:click="markAsRead('{{ $notification->id }}')"
                                class="btn btn-sm btn-link text-success p-0"
                            >
                                Marcar como lida
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="far fa-bell-slash fa-3x mb-3"></i>
                    <p>Nenhuma notificação encontrada</p>
                </div>
            @endforelse
        </div>

    </div>

    

    {{-- Lista --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            
        </div>

        {{-- Paginação --}}
        @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
