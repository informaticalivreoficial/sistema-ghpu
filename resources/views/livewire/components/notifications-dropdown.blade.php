<div wire:poll.30s="loadNotifications">
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
            <i class="far fa-bell"></i>

            @if($unreadNotificationsCount > 0)
                <span class="badge badge-warning navbar-badge">
                    {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                </span>
            @endif
        </a>

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            @if($unreadNotificationsCount === 0)
                <span class="dropdown-item dropdown-header text-muted">
                    <i class="fas fa-info-circle mr-2"></i>
                    Nenhuma notificação
                </span>
            @else
                <div class="d-flex justify-content-between align-items-center px-3 py-2">
                    <span class="dropdown-header p-0">
                        {{ $unreadNotificationsCount }} {{ $unreadNotificationsCount === 1 ? 'Notificação' : 'Notificações' }}
                    </span>
                    
                    @if($unreadNotificationsCount > 0)
                        <button 
                            wire:click="markAllAsRead" 
                            class="btn btn-sm btn-link p-0 text-primary"
                            title="Marcar todas como lidas"
                        >
                            <i class="fas fa-check-double"></i>
                        </button>
                    @endif
                </div>

                <div class="dropdown-divider"></div>

                @foreach($notifications as $notification)
                    <a 
                        href="#" 
                        wire:click.prevent="markAsRead('{{ $notification->id }}')"
                        class="dropdown-item"
                    >
                        <div class="d-flex align-items-start">
                            <i class="fas fa-bell text-warning mr-2 mt-1"></i>
                            <div class="flex-grow-1">
                                <p class="mb-1 text-sm">
                                    {{ $notification->data['message'] ?? 'Nova notificação' }}
                                </p>
                                <span class="text-muted text-xs">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </a>

                    <div class="dropdown-divider"></div>
                @endforeach
            @endif

            <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer text-center">
                Ver todas as notificações
            </a>
        </div>
    </li>
</div>