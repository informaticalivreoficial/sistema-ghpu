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

                    <div wire:poll.5s="refreshThreads">
                        @foreach($threads as $thread)
                            @php
                                $other = $thread['model']->otherUser(auth()->id());
                                $last  = $thread['lastItem'];
                            @endphp

                            <div
                                wire:click="openThread({{ $thread['id'] }})"
                                role="button"
                                class="group w-full flex gap-3 px-4 py-2 cursor-pointer
                                    hover:bg-gray-100
                                    {{ $activeThreadId === $thread['id'] ? 'bg-gray-100' : '' }}">

                                <img src="{{ $other->avatarUrl() }}"
                                    class="w-10 h-10 rounded-full object-cover flex-shrink-0">

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-semibold truncate flex items-center gap-2">
                                            {{ $other->name }}

                                            @if($thread['hasNewMessages'])
                                                <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
                                            @endif
                                        </span>

                                        <span class="text-xs text-gray-500 flex-shrink-0">
                                            {{ $last?->created_at?->diffForHumans() }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <p class="text-sm text-gray-500 truncate flex-1">
                                            {{ $last?->body }}
                                        </p>

                                        @if(auth()->user()->canDeleteMessages())
                                            <button
                                                wire:click.stop="confirmDelete({{ $thread['id'] }})"
                                                class="ml-2 text-red-500 hover:text-red-700 text-sm opacity-0 group-hover:opacity-100 transition"
                                                title="Excluir conversa"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

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

        
        document.addEventListener('livewire:initialized', () => {

            @this.on('delete-prompt', () => {
                swal.fire({
                    icon: 'warning',
                    title: 'Atenção',
                    text: 'Você tem certeza que deseja excluir esta conversa?',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#d33',
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.dispatch('goOn-Delete')
                    }
                })
            })

        })

        window.addEventListener('thread-deleted', () => {
            Swal.fire({
                icon: 'success',
                title: 'Conversa excluída',
                timer: 1500,
                showConfirmButton: false,
            });
        });
    
    </script>
@endpush