<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Meu Painel</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Início</a></li>
                        <li class="breadcrumb-item active">Meu Painel</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7">
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-2 border-b">
                            <h3 class="text-sm font-semibold text-gray-800">Últimas Ocorrências</h3>
                        </div>

                        <div class="divide-y">
                            @forelse($lastOcorrencias as $ocorrencia)
                            <div class="flex items-center justify-between px-6 py-2 hover:bg-gray-50 transition">
                                
                                <!-- Ocorrência e avatar -->
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $ocorrencia['user']['avatar']
                                            ? asset('storage/'.$ocorrencia['user']['avatar'])
                                            : asset('theme/images/image.jpg') }}" 
                                        alt="Foto Colaborador" 
                                        class="w-10 h-10 rounded-full object-cover">
                                    <span class="text-gray-800 font-medium">{{ Str::limit($ocorrencia['title'] ?? 'Ocorrência', 40) }}</span>
                                </div>

                                <!-- Data -->
                                <div class="text-gray-500 text-sm">
                                    {{ \Carbon\Carbon::parse($ocorrencia['created_at'])->format('d/m/Y H:i') }}
                                </div>

                                <!-- Ações -->
                                <div class="flex items-center space-x-2">
                                    <a target="_blank" href="{{ route('ocorrencia.pdf', $ocorrencia['id']) }}" class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-search"></i>
                                    </a>

                                    @if(\Carbon\Carbon::parse($ocorrencia['created_at'])->gt(now()->subDay()))
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Nova
                                        </span>
                                    @endif
                                </div>

                            </div>
                            @empty
                            <div class="px-6 py-8 text-center text-gray-400">
                                Nenhuma ocorrência encontrada
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>            
                <div class="col-lg-5">

                </div>            
            </div>        
        </div>      
    </div>
</div>