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

    <div class="card card-primary card-outline">
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

        <div class="card-body">
            @if(!empty($ocorrencias) && $ocorrencias->count() > 0)
                <div class="row">
                    @foreach($ocorrencias as $ocorrencia)                        
                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3">
                            <div class="card {{ $ocorrencia->user_id === auth()->id() ? 'bg-blue-100' : '' }}">
                                <div class="card-body">
                                    <div class="post">
                                        <div class="user-block">
                                            @php
                                                if(!empty($ocorrencia->user->avatar) && \Illuminate\Support\Facades\Storage::exists($ocorrencia->user->avatar)){
                                                    $cover = \Illuminate\Support\Facades\Storage::url($ocorrencia->user->avatar);
                                                } else {
                                                    if($ocorrencia->user->gender == 'masculino'){
                                                        $cover = url(asset('theme/images/avatar5.png'));
                                                    } elseif($ocorrencia->user->gender == 'feminino'){
                                                        $cover = url(asset('theme/images/avatar3.png'));
                                                    } else {
                                                        $cover = url(asset('theme/images/image.jpg'));
                                                    }
                                                }
                                            @endphp
                                            <img class="img-circle img-bordered-sm" src="{{$cover}}" alt="{{$ocorrencia->user->name}}">
                                            <span class="username">
                                                {{$ocorrencia->user->name}}
                                                @if ($ocorrencia->canBeDeletedBy(auth()->user()) && $ocorrencia->user_id === auth()->id())
                                                    <a wire:click="setDeleteId({{ $ocorrencia->id }})" class="float-right btn-tool cursor-pointer" title="Excluir"><i class="fas fa-times"></i></a>
                                                @endif                                                                                               
                                            </span>
                                            <span class="description">
                                                Data de publicação - {{ \Carbon\Carbon::parse($ocorrencia->created_at)->format('d/m/Y - H:i') }}
                                            </span>
                                        </div>

                                        <p>{{ $ocorrencia->title }}</p>

                                        <p class="mt-3">
                                            <a href="{{ route('ocorrencia.edit', $ocorrencia->id) }}" target="_blank" class="link-black text-sm mr-2 hover:text-gray-400" title="Visualizar">
                                                <i class="fas fa-search"></i> Visualizar
                                            </a>
                                            @if ($ocorrencia->user_id === auth()->id() && $ocorrencia->canBeEditedBy(auth()->user()))
                                                <a href="{{ route('ocorrencia.edit', $ocorrencia->id) }}" class="link-black text-sm hover:text-gray-400" title="Editar">
                                                    <i class="fas fa-pen"></i> Editar
                                                </a>
                                            @endif                                            
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($ocorrencias->hasMorePages())
                    <div class="text-center mt-3">
                        <button 
                            wire:click="loadMore" 
                            class="btn btn-primary" 
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>
                                Carregar mais Ocorrências
                            </span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Carregando...
                            </span>
                        </button>
                    </div>
                @endif
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

    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('swal', (event) => {
                const data = event
                swal.fire({
                    icon:data[0]['icon'],
                    title:data[0]['title'],
                    text:data[0]['text'],
                })
            })

            @this.on('delete-prompt', (event) => {
                swal.fire({
                    icon: 'warning',
                    title: 'Atenção',
                    text: 'Você tem certeza que deseja excluir esta Ocorrência?',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, excluir!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.dispatch('goOn-Delete')
                    }
                })
            })
        });
    </script>
@endpush