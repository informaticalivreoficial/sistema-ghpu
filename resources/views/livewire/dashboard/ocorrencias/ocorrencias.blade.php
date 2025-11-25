<div>
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-search mr-2"></i> Ocorrências</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Ocorrências</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-sm-6 my-2">
                    <div class="card-tools">
                        <div style="width: 250px;">
                            <form class="input-group input-group-sm" action="" method="post">
                                <input type="text" wire:model.live="search" class="form-control float-right" placeholder="Pesquisar">               
                                
                            </form>
                        </div>
                      </div>
                </div>
                <div class="col-12 col-sm-6 my-2 text-right">
                    <a wire:navigate href="{{route('ocorrencia.create')}}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Novo</a>
                </div>
            </div>
        </div>        
        <!-- /.card-header -->
        <div class="card-body">
            @if(!empty($ocorrencias) && $ocorrencias->count() > 0)
                <table class="table table-bordered table-striped projects">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Colaborador</th>
                            <th class="text-center">Data/Hora</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ocorrencias as $ocorrencia)                    
                        <tr style="{{ ($ocorrencia->status == true ? '' : 'background: #fffed8 !important;')  }}">                            
                            <td>{{$ocorrencia->title}}</td>
                            <td>{{$ocorrencia->user->name}}</td>
                            <td>{{\Carbon\Carbon::parse($ocorrencia->created_at)->format('d/m/Y - H:i')}}</td>                            
                            <td class="px-4 py-4 flex items-center justify-center gap-2 h-full"> 
                                
                                <label style="top:4px !important;" class="switch flex-shrink-0">
                                    <input type="checkbox" 
                                        value="{{ $ocorrencia->status }}"  
                                        wire:change="toggleStatus({{ $ocorrencia->id }})" 
                                        wire:loading.attr="disabled" 
                                        {{ $ocorrencia->status ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label> 
                                <a wire:navigate href="visualizar-empresa/" class="btn btn-xs btn-info text-white"><i class="fas fa-search"></i></a>
                                <a wire:navigate href="{{ route('ocorrencia.edit', $ocorrencia->id) }}" 
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:text-blue-800 transition flex-shrink-0">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button type="button" 
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-800 transition flex-shrink-0"
                                        wire:click="setDeleteId({{ $ocorrencia->id }})" 
                                        title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>                
                </table>
            @else
                <div class="row mb-4">
                    <div class="col-12">                                                        
                        <div class="alert alert-info p-3">
                            Não foram encontrados registros!
                        </div>                                                        
                    </div>
                </div>
            @endif
        </div>
        @if($ocorrencias->hasMorePages())
            <div class="text-center mt-4 mb-4">
                <!-- Botão só aparece quando NÃO está carregando -->
                <button 
                    wire:click="loadMore" 
                    wire:loading.remove
                    wire:target="loadMore"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                    Carregar mais
                </button>

                <!-- Spinner enquanto carrega -->
                <div wire:loading wire:target="loadMore" class="flex justify-center items-center mt-2">
                    <svg class="animate-spin h-6 w-6 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    Carregando...
                </div>
            </div>
        @endif
    </div>
</div>
