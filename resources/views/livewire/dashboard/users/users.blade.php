<div>     
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-search mr-2"></i> Colaboradores</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Colaboradores</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>   {{-- 
    @if ($updateMode)
        @livewire('dashboard.users.form')
    @endif  --}} 
    <div class="card" x-data="{ openImage: false, imageSrc: '' }">
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
                    <a href="{{ route('users.create') }}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Novo</a>
                </div>
            </div>
        </div>        
        <!-- /.card-header -->
        <div class="card-body">
            @if(!empty($users) && $users->count() > 0)
                <table class="table table-bordered table-striped projects">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th wire:click="sortBy('name')">Nome <i class="expandable-table-caret fas fa-caret-down fa-fw"></i></th>
                            @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                <th>Empresa</th>                                
                            @endif     
                            @if (auth()->user()->isManager())
                                <th>RG</th>
                            @endif                       
                            <th>Cargo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)                    
                        <tr style="{{ ($user->status == true ? '' : 'background: #fffed8 !important;')  }}">
                            @php
                                if(!empty($user->avatar) && Storage::exists($user->avatar)){
                                    $cover = Storage::url($user->avatar);
                                } else {
                                    if($user->gender == 'masculino'){
                                        $cover = url(asset('theme/images/avatar5.png'));
                                    }elseif($user->gender == 'feminino'){
                                        $cover = url(asset('theme/images/avatar3.png'));
                                    }else{
                                        $cover = url(asset('theme/images/image.jpg'));
                                    }
                                }
                            @endphp
                            <td class="text-center">
                                <img 
                                    src="{{ $cover }}" 
                                    alt="{{ $user->name }}" 
                                    class="w-12 h-12 rounded-full object-cover mx-auto cursor-pointer"
                                    @click="openImage = true; imageSrc = '{{ $cover }}'"
                                >
                            </td>                            
                            <td>{{$user->name}}</td>
                            @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                <td>{{$user->company->alias_name}}</td>
                            @endif                            
                            @if (auth()->user()->isManager())
                                <td>{{$user->rg}}</td>
                            @endif                            
                            <td>{{$user->cargo}}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    @can('update', $user)
                                        <x-forms.switch-toggle
                                            wire:key="safe-switch-{{ $user->id }}"
                                            wire:click="toggleStatus({{ $user->id }})"
                                            :checked="$user->status"
                                            size="sm"
                                            color="green"
                                        />
                                    @else
                                        <x-forms.switch-toggle
                                            :checked="$user->status"
                                            size="sm"
                                            color="gray"
                                            disabled
                                        />
                                    @endcan

                                    <a class="btn btn-xs btn-info" 
                                    href="{{ route('users.profile', ['user' => $user->id]) }}" 
                                    target="_blank" 
                                    title="Visualizar Perfil">
                                        <i class="fas fa-search"></i>
                                    </a>                                                                   

                                    <a href="{{ route('users.edit', ['userId' => $user->id ]) }}" 
                                    class="btn btn-xs btn-default" 
                                    title="Editar Colaborador">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    @can('delete', $user)
                                        <button type="button" 
                                            class="btn btn-xs bg-danger text-white" 
                                            title="Excluir Colaborador"
                                            wire:click="setDeleteId({{ $user->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>                
                </table>

                <!-- Modal -->
                <div 
                    x-show="openImage"
                    x-transition.opacity
                    class="fixed inset-0 bg-black/70 flex items-center justify-center z-[9999]"
                    @click.self="openImage = false"
                >
                    <div class="relative">
                        
                        <!-- Botão fechar -->
                        <button 
                            class="absolute top-2 right-2 text-white text-3xl font-bold"
                            @click="openImage = false"
                        >
                            ×
                        </button>

                        <!-- Imagem grande -->
                        <img 
                            :src="imageSrc" 
                            class="max-w-[90vw] max-h-[90vh] rounded shadow-lg"
                        >
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12">                                                        
                        <div class="alert alert-info p-3">
                            Não foram encontrados registros!
                        </div>                                                        
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer clearfix">  
            {{ $users->links() }}  
        </div>
    </div>

</div>