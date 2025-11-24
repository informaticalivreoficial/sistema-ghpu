<div>
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-search mr-2"></i> Imóveis</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Imóveis</li>
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
                    <a wire:navigate href="{{route('properties.create')}}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Novo</a>
                </div>
            </div>
        </div>

        <div class="card-body"> 
            @if ($properties->count())
                <div class="row d-flex align-items-stretch" x-data="{ showModal: false, imageUrl: '' }">
                    @foreach($properties as $property)  
                        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                            <div class="card card-widget widget-user" style="{{ ($property->status == true ? '' : 'background: #fffed8 !important;')  }}">
                                <div class="cursor-pointer" @click="showModal = true; imageUrl = '{{ url($property->nocover()) }}'">
                                    <div class="rounded-t h-[175px] p-4 text-center text-white" 
                                        style="background: url('{{url($property->cover())}}') center center;background-size: cover;">
                                        <h3 class="widget-user-username text-right">{{$property->title}}</h3>
                                        <h5 class="widget-user-desc text-right">{{$property->category}} - {{$property->type}}</h5>
                                    </div>       
                                </div>        
                                <div class="py-3 px-3">
                                    <div class="row">
                                        <div class="col-12 text-center mb-2">
                                            @if($property->sale && !$property->location)
                                                {{ $property->formatted_sale_value ? 'Venda ' . $property->formatted_sale_value : '-----' }}
                                            @elseif($property->sale && $property->location)
                                                {{ $property->formatted_sale_value ? 'Venda ' . $property->formatted_sale_value : '' }}
                                                {{ $property->formatted_rental_value ? '/ Locação ' . $property->formatted_rental_value : '' }}
                                            @else
                                                {{ $property->formatted_rental_value ? 'Locação ' . $property->formatted_rental_value : '-----' }}
                                            @endif
                                        </div>
                                        <div class="col-12 text-center mb-2">                                            
                                            <label class="switch" wire:model="active">
                                                <input type="checkbox" value="{{$property->status}}"  wire:change="toggleStatus({{$property->id}})" wire:loading.attr="disabled" {{$property->status ? 'checked': ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                            <div x-data="{ open: false }" class="d-inline-block ml-2 relative">
                                                <button 
                                                    wire:click="toggleHighlight({{ $property->id }})"
                                                    @mouseenter="open = true" 
                                                    @mouseleave="open = false"
                                                    class="btn btn-xs {{ $property->highlight ? 'btn-warning' : 'btn-secondary' }} icon-notext"
                                                >
                                                    <i class="fas fa-award"></i>
                                                </button>

                                                <div 
                                                    x-show="open" 
                                                    class="absolute bottom-full mb-2 px-2 py-1 text-xs text-white bg-gray-700 rounded shadow"
                                                >
                                                    {{ $property->highlight ? 'Remover destaque' : 'Marcar como destaque' }}
                                                </div>
                                            </div>
                                            <button 
                                                type="button" 
                                                wire:click="applyWatermark({{ $property->id }})"
                                                class="btn btn-xs {{ $property->display_marked_water ? 'btn-warning' : 'btn-secondary' }}"
                                                title="Inserir Marca d'água"
                                                @if($property->display_marked_water) disabled @endif
                                            >
                                                <i class="fas fa-copyright"></i>
                                            </button>
                                            @if ($property->slug)
                                                <a target="_blank" data-toggle="tooltip" data-placement="top" title="Visualizar Imóvel" class="btn btn-xs btn-info text-white" href="{{ route('web.property', ['slug' => $property->slug]) }}" title="{{$property->title}}"><i class="fas fa-search"></i></a>
                                            @endif                            
                                            <a wire:navigate title="Editar Imóvel" href="{{ route('property.edit', [ 'property' => $property->id ]) }}" class="btn btn-xs btn-default"><i class="fas fa-pen"></i></a>
                                            <button type="button" class="btn btn-xs btn-danger text-white p-0" wire:click="setDeleteId({{$property->id}})" title="Excluir" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <div class="col-sm-4 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header">{{$property->reference}}</h5>
                                                <span>Referência</span>
                                            </div>                    
                                        </div>
                                        
                                        <div class="col-sm-4 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header">{{$property->views}}</h5>
                                                <span>Views</span>
                                            </div>                    
                                        </div>
                                        
                                        <div class="col-sm-4">
                                            <div class="description-block">
                                                <h5 class="description-header">{{$property->images()->count()}}</h5>
                                                <span>Imagens</span>
                                            </div>                    
                                        </div>
                                    
                                    </div>                        
                                </div>
                            </div>
                        </div>
                    @endforeach  
                    
                    <!-- Modal de imagem -->
                    <div x-show="showModal" x-cloak
                        class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-[9999]"
                        x-transition>
                        <div class="relative">
                            <img :src="imageUrl" class="max-w-[70vw] max-h-[70vh] object-contain mx-auto rounded shadow-lg">
                            <button type="button" @click="showModal = false"
                                    class="absolute top-2 right-2 text-white text-xl bg-black bg-opacity-50 rounded-full px-2 py-1">
                                ✕
                            </button>
                        </div>
                    </div>
                </div>

                @if($properties->hasMorePages())
                    <div class="text-center mt-4">
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
                text: 'Você tem certeza que deseja excluir este Imóvel?',
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