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
                    <a wire:navigate href="{{route('ocorrencia.create')}}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Ocorrência</a>
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
                            <td> 
                                <div class="flex items-center gap-2">
                                    @can('update', $ocorrencia)
                                        <x-forms.switch-toggle
                                            wire:key="safe-switch-{{ $ocorrencia->id }}"
                                            wire:click="toggleStatus({{ $ocorrencia->id }})"
                                            :checked="$ocorrencia->status"
                                            size="sm"
                                            color="green"
                                        />
                                    @else
                                        <x-forms.switch-toggle
                                            :checked="$ocorrencia->status"
                                            size="sm"
                                            color="gray"
                                            disabled
                                        />
                                    @endcan 
                                    <a target="_blank" href="{{ route('ocorrencia.pdf', $ocorrencia->id) }}" 
                                        class="btn btn-xs btn-info" 
                                        title="Visualizar">
                                        <i class="fas fa-search"></i>
                                    </a>
                                    <a href="{{ route('ocorrencia.edit', $ocorrencia->id) }}" 
                                        class="btn btn-xs btn-default" 
                                        title="Editar">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    @can ('delete', $ocorrencia)
                                        <button type="button" 
                                            class="btn btn-xs bg-danger text-white" 
                                            title="Excluir Ocorrência"
                                            wire:click="setDeleteId({{ $ocorrencia->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endcan
                                </div>                                
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
