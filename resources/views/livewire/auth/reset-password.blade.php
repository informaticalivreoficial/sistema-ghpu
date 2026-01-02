<div>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-4 rounded shadow-lg max-w-md w-full space-y-8">
            <div>
                @if($config && $config->logo)
                    <img class="mx-auto h-20 w-auto" src="{{ asset('storage/' . $config->logo) }}" alt="Logo">
                @endif
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Redefinir senha
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Crie uma nova senha para sua conta
                </p>
            </div>

            <form wire:submit="resetPassword" class="mt-8 space-y-6">
                <input type="hidden" wire:model="token">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        E-mail
                    </label>
                    <input 
                        wire:model="email"
                        id="email" 
                        type="email" 
                        required
                        readonly
                        class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 sm:text-sm"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Nova senha
                    </label>
                    <input 
                        wire:model="password"
                        id="password" 
                        type="password" 
                        required
                        class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-500 @enderror"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirmar nova senha
                    </label>
                    <input 
                        wire:model="password_confirmation"
                        id="password_confirmation" 
                        type="password" 
                        required
                        class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                </div>

                <div>
                    <button 
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove>Redefinir senha</span>
                        <span wire:loading>Processando...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
