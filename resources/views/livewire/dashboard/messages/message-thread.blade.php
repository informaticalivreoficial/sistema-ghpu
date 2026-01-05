<div>   

    <div class="h-[80vh]">
        <div class="flex flex-col h-full bg-gray-100">

            {{-- Mensagens --}}
            <div
                id="chat-messages"
                class="flex-1 overflow-y-auto space-y-3 px-4 py-2
           bg-[#efeae2]
           bg-[radial-gradient(circle_at_1px_1px,rgba(0,0,0,0.04)_1px,transparent_0)]
           bg-[length:20px_20px]"
                wire:poll.7s="refreshMessages"
                x-data="{ 
                    shouldScroll: true,
                    checkScroll() {
                        const el = this.$el;
                        const isNearBottom = el.scrollHeight - el.scrollTop - el.clientHeight < 100;
                        this.shouldScroll = isNearBottom;
                    },
                    scrollToBottom() {
                        if (this.shouldScroll) {
                            this.$el.scrollTop = this.$el.scrollHeight;
                        }
                    }
                }"
                x-init="
                    scrollToBottom();
                "
                @scroll="checkScroll()"
            >
                @foreach($messages as $item)
                    <div class="flex {{ $item->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="
                            max-w-[70%] px-4 py-2 rounded-2xl shadow
                            {{ $item->sender_id === auth()->id()
                                ? 'bg-green-500 text-white rounded-br-sm'
                                : 'bg-white text-gray-800 rounded-bl-sm'
                            }}
                        ">
                            <p class="text-sm whitespace-pre-line">{{ $item->body }}</p>

                            <span class="block text-[10px] mt-1 text-right opacity-70">
                                {{ $item->created_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Input --}}
            <div class="bg-white border-t px-4 py-3">
                <div class="flex gap-3 items-end">
                    <textarea
                        wire:model.defer="body"
                        rows="1"
                        placeholder="Digite uma mensagem"
                        class="flex-1 resize-none border rounded-full px-4 py-2 text-sm focus:outline-none focus:ring"
                        @keydown.enter.prevent="$wire.send(); $nextTick(() => document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight)"
                    ></textarea>

                    <button
                        wire:click="send"
                        class="bg-green-500 hover:bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center"
                        @click="$nextTick(() => document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight)"
                    >
                        ➤
                    </button>
                </div>
            </div>

        </div>
    </div>
        
</div>

