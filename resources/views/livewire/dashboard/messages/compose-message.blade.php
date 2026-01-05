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
        <div class="card-header">
            <div class="px-6 py-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold">
                    <i class="fas fa-comment-dots"></i>
                </div>

                <div>
                    <h2 class="text-lg font-semibold">Nova mensagem</h2>
                    <p class="text-sm text-gray-500">Envio privado e individual</p>
                </div>
            </div>
        </div>

        <div class="card-body p-6 space-y-6 bg-gray-50">
            <!-- Assunto -->
            <div>
                <label class="block text-sm font-semibold mb-1">Assunto</label>
                <input
                    type="text"
                    wire:model.defer="subject"
                    placeholder="Ex: Solicitação, Aviso importante..."
                    class="w-full rounded-lg border border-gray-300
                        px-4 py-3
                        text-base
                        focus:ring-2 focus:ring-green-500 focus:border-green-500">
                @error('subject') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Destinatários -->
            <div>
            <label class="block text-sm font-semibold mb-2">Destinatários</label>

                <div class="space-y-2 max-h-44 overflow-y-auto border rounded-lg p-3">
                    @foreach($availableUsers as $user)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" value="{{ $user->id }}" wire:model.defer="recipients"
                                class="h-4 w-4 text-green-500 border-gray-300 rounded">
                            <span class="text-sm">{{ $user->name }}</span>
                        </label>
                    @endforeach
                </div>

                @error('recipients') 
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Mensagem -->
            <div>
                <label class="block text-sm font-semibold mb-1">Mensagem</label>

                <div class="relative">
                    <textarea
                        wire:model.defer="body"
                        rows="6"
                        placeholder="Digite sua mensagem..."
                        class="w-full rounded-xl border border-gray-300
                            px-4 py-4
                            text-base
                            resize-none
                            "></textarea>

                    <!-- Botão de envio flutuante -->
                    <button wire:click="send"
                        class="absolute bottom-4 right-4
                            w-12 h-12
                            bg-green-500 hover:bg-green-600
                            text-white rounded-full
                            flex items-center justify-center
                            shadow-lg transition">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>

                @error('body') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>