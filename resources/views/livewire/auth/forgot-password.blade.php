<div>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-4 rounded shadow-lg max-w-md w-full space-y-8">
            <div>
                @if($config && $config->logo)
                    <img class="mx-auto h-20 w-auto" src="{{ asset('storage/' . $config->logo) }}" alt="Logo">
                @endif
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Esqueci minha senha
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Informe seu e-mail para receber o link de recuperação
                </p>
            </div>

            @if($emailSent)
                <div class="rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                Link enviado! Verifique sua caixa de entrada e spam.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit="sendResetLink" class="mt-8 space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        E-mail
                    </label>
                    <input 
                        wire:model="email"
                        id="email" 
                        type="email" 
                        required
                        class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 @enderror"
                        placeholder="seu@email.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button 
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove>Enviar link de recuperação</span>
                        <span wire:loading>Enviando...</span>
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                        Voltar para o login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
