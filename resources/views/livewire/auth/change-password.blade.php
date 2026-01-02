<div>
    <div class="max-w-2xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-6">Alterar Senha</h1>

            <form wire:submit="updatePassword" class="space-y-6">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">
                        Senha Atual
                    </label>
                    <input 
                        wire:model="current_password"
                        id="current_password" 
                        type="password" 
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('current_password') border-red-500 @enderror"
                    >
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Nova Senha
                    </label>
                    <input 
                        wire:model="password"
                        id="password" 
                        type="password" 
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-500 @enderror"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Mínimo de 8 caracteres</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirmar Nova Senha
                    </label>
                    <input 
                        wire:model="password_confirmation"
                        id="password_confirmation" 
                        type="password" 
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                </div>

                <div class="flex gap-4">
                    <button 
                        type="submit"
                        wire:loading.attr="disabled"
                        class="flex-1 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="updatePassword">Alterar Senha</span>
                        <span wire:loading wire:target="updatePassword">Processando...</span>
                    </button>
                    <a 
                        href="{{ url()->previous() }}"
                        class="flex-1 text-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
