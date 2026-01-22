<div>
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-search mr-2"></i> Apartamentos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Apartamentos</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

    <div class="card">
        <div class="card-header text-right">
            <div class="row">
                <div class="col-12 col-sm-8 my-2">                    
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: auto;">
                            <!-- Busca -->
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="search"
                                class="form-control"
                                placeholder="Buscar apartamento..."
                            >

                            <!-- Status -->
                            <select wire:model.live="status" class="form-control ml-2">
                                <option value="all">Todos</option>
                                <option value="ativo">Ativos</option>
                                <option value="inativo">Inativos</option>
                            </select>

                            <!-- Empresa (Admin / Super) -->
                            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                <select wire:model.live="companyId" class="form-control ml-2">
                                    <option value="">Todas as empresas</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">
                                            {{ $company->alias_name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>                
                </div>  
                <div class="col-12 col-sm-4 my-2 text-right">
                    @can('create', App\Models\Apartments::class)
                        <a href="{{route('apartments.create')}}" class="btn btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Novo</a>
                    @endcan
                </div> 
            </div>             
        </div>        
        
        <div class="card-body">
            @if(!empty($apartments) && $apartments->count() > 0)
                <table class="table table-bordered table-striped projects">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th class="text-center">Capacidade</th>
                            <th>Reservas</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($apartments as $apartment)                    
                        <tr style="{{ ($apartment->status == '1' ? '' : 'background: #fffed8 !important;')  }}">                            
                            <td>{{$apartment->name}}</td>
                            <td class="text-center">Adultos: {{$apartment->capacidade_adultos}} / Crianças: {{$apartment->capacidade_criancas}}</td>
                            <td class="text-center">--------</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    @can('update', $apartment)
                                        <x-forms.switch-toggle
                                            wire:key="safe-switch-{{ $apartment->id }}"
                                            wire:click="toggleStatus({{ $apartment->id }})"
                                            :checked="$apartment->status"
                                            size="sm"
                                            color="green"
                                        />
                                    @else
                                        <x-forms.switch-toggle
                                            :checked="$apartment->status"
                                            size="sm"
                                            color="gray"
                                            disabled
                                        />
                                    @endcan
                                    <a 
                                        title="Editar Apartamento" 
                                        href="{{route('apartments.edit', $apartment->id)}}" 
                                        class="btn btn-xs btn-default"><i class="fas fa-pen"></i>
                                    </a>
                                    @can ('delete', $apartment)
                                        <button type="button" 
                                            class="btn btn-xs bg-danger text-white" 
                                            title="Excluir Apartamento"
                                            wire:click="setDeleteId({{ $apartment->id }})">
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
        <div class="card-footer paginacao">  
            {{--  --}}
        </div>
    </div>
</div>
