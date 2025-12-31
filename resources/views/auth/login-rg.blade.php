<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Colaborador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg border border-gray-200">

        {{-- HEADER --}}
        <div class="px-8 pt-8 pb-6 text-center border-b">
            <img width="{{env('LOGOMARCA_GERENCIADOR_WIDTH')}}" height="{{env('LOGOMARCA_GERENCIADOR_HEIGHT')}}" 
                src="{{ $config->getlogoadmin() }}" alt="{{$config->app_name}}"
                    class="mx-auto d-block mb-4 cursor-pointer" />

            <h1 class="text-xl font-semibold text-gray-900">
                Acesso do Colaborador
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Informe seu RG para continuar
            </p>
        </div>

        {{-- FORM --}}
        <div class="px-8 py-6">
            <form method="POST" action="{{ route('web.login.rg') }}" class="space-y-6">
                @csrf

                {{-- RG --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        RG do colaborador
                    </label>

                    <div class="relative">
                        <input
                            type="text"
                            name="rg"
                            value="{{ old('rg') }}"
                            autofocus
                            placeholder="Somente números"
                            class="
                                w-full rounded-lg border px-4 py-3
                                text-gray-900
                                focus:outline-none focus:ring-2 focus:ring-black focus:border-black
                                @error('rg') border-red-500 focus:ring-red-500 @enderror
                            "
                        >

                        <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                            🪪
                        </div>
                    </div>

                    @error('rg')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- BOTÃO --}}
                <button
                    type="submit"
                    class="
                        w-full py-3 rounded-lg
                        bg-black text-white font-medium
                        hover:bg-gray-900 transition
                        focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black
                    "
                >
                    Entrar
                </button>
            </form>
        </div>

        {{-- FOOTER --}}
        <div class="px-8 py-4 bg-gray-50 rounded-b-xl text-center text-xs text-gray-500">
            Acesso restrito • Uso interno
        </div>

    </div>

</body>
</html>