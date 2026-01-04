<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-comments mr-2"></i> Mensagens</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Mensagens</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

    <div class="card">
        
            <div class="flex h-[calc(100vh-120px)] bg-white rounded-xl shadow overflow-hidden">

                <!-- Lista -->
                <div class="w-1/3 border-r overflow-y-auto">

                    <div class="px-4 py-3 border-b flex justify-between items-center font-semibold text-lg">
                        <span>Conversas</span>
                        <a href="{{ route('messages.compose') }}"
                        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm flex items-center gap-1">
                            <i class="fas fa-plus"></i> Nova
                        </a>
                    </div>

                    @foreach($threads as $thread)
                        @php
                            $other = $thread->otherUser(auth()->id());
                            $last  = $thread->lastItem;
                        @endphp

                        <button
                            wire:click="openThread({{ $thread->id }})"
                            class="w-full flex gap-3 px-4 py-3 text-left hover:bg-gray-100
                                {{ $activeThreadId === $thread->id ? 'bg-gray-100' : '' }}">

                            <img
                                src="{{ $other->avatarUrl() }}"
                                class="w-12 h-12 rounded-full object-cover"
                            >

                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between">
                                    <span class="font-semibold truncate">
                                        {{ $other->name }}
                                    </span>

                                    <span class="text-xs text-gray-500">
                                        {{ $last?->created_at?->diffForHumans() }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-500 truncate">
                                    {{ $last?->body }}
                                </p>
                            </div>
                        </button>
                    @endforeach

                </div>

                <!-- Placeholder -->
                <div class="flex-1">
                    @if($activeThreadId)
                        <livewire:dashboard.messages.message-thread
                            :message="\App\Models\Message::find($activeThreadId)"
                            :key="$activeThreadId"
                        />
                    @else
                        <div class="h-full flex items-center justify-center text-gray-400">
                            Selecione uma conversa
                        </div>
                    @endif
                </div>
            </div>
        
    </div>
</div>

@push('scripts')
    <script>
        // Aguarda o DOM estar completamente carregado
        const initChatScroll = () => {
            
            const chatMessages = document.getElementById('chat-messages');
            
            if (!chatMessages) {
                setTimeout(initChatScroll, 100);
                return;
            }            
            
            let wasAtBottom = true;
            
            const checkIfAtBottom = () => {
                const scrollTop = chatMessages.scrollTop;
                const scrollHeight = chatMessages.scrollHeight;
                const clientHeight = chatMessages.clientHeight;
                const distanceFromBottom = scrollHeight - scrollTop - clientHeight;
                wasAtBottom = distanceFromBottom < 100;
            };
            
            const scrollToBottom = () => {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            };
            
            // Monitora scroll do usuário
            chatMessages.addEventListener('scroll', checkIfAtBottom);
            
            // Scroll inicial
            setTimeout(scrollToBottom, 200);
            
            // Observa mudanças no DOM
            const observer = new MutationObserver(() => {
                if (wasAtBottom) {
                    setTimeout(scrollToBottom, 50);
                }
            });
            
            observer.observe(chatMessages, {
                childList: true,
                subtree: true
            });
        };
        
        // Inicia quando Livewire estiver pronto
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('commit', ({ component, respond }) => {
                respond(() => {
                    initChatScroll();
                });
            });
        }
        
        // Também tenta iniciar no DOMContentLoaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initChatScroll);
        } else {
            initChatScroll();
        }
    </script>
@endpush