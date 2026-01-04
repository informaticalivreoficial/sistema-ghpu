<div>
    <li class="nav-item dropdown" wire:poll.10s>
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-comments"></i>

            @if($unreadCount > 0)
                <span class="badge badge-danger navbar-badge">
                    {{ $unreadCount }}
                </span>
            @endif
        </a>

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

            @forelse($conversations as $conversation)

                @php
                    $other = $conversation->otherUser(auth()->id());
                    $last = $conversation->items->first();
                @endphp

                <a href="{{ route('messages.thread', $conversation->id) }}"
                class="dropdown-item">

                    <div class="media">
                        <img src="{{ $other->avatar
                            ? asset('storage/'.$other->avatar)
                            : asset('theme/images/image.jpg') }}"
                            class="img-size-50 mr-3 img-circle">

                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                {{ $other->name }}
                            </h3>

                            <p class="text-sm">
                                {{ Str::limit($last?->body ?? 'Sem mensagens', 40) }}
                            </p>

                            <p class="text-sm text-muted">
                                <i class="far fa-clock mr-1"></i>
                                {{ optional($last)->created_at?->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>

                <div class="dropdown-divider"></div>

            @empty
                <span class="dropdown-item text-center text-muted">
                    Nenhuma mensagem
                </span>
            @endforelse

            <a href="{{ route('messages.inbox') }}"
            class="dropdown-item dropdown-footer">
                Ver todas as mensagens
            </a>
        </div>
    </li>
</div>
