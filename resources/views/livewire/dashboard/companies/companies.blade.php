<div>    
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-search mr-2"></i> Empresas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Empresas</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>   {{-- 
    @if ($updateMode)
        @livewire('dashboard.companys.form')
    @endif  --}} 
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
                    <a wire:navigate href="{{route('companies.create')}}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Novo</a>
                </div>
            </div>
        </div>        
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-12">                
                    @if(session()->exists('message'))
                        @message(['color' => session()->get('color')])
                            {{ session()->get('mensagem') }}
                        @endmessage
                    @endif
                </div>            
            </div>
            @if(!empty($companies) && $companies->count() > 0)
                <table class="table table-bordered table-striped projects">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('alias_name')">Nome Fantasia <i class="expandable-table-caret fas fa-caret-down fa-fw"></i></th>
                            <th>Manifestos</th>
                            <th>Faturas</th>
                            <th class="text-center">Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($companies as $company)                    
                        <tr style="{{ ($company->status == true ? '' : 'background: #fffed8 !important;')  }}">                            
                            <td>{{$company->alias_name}}</td>
                            <td>{{$company->manifest->count()}}</td>
                            <td></td>
                            <td class="text-center">
                                <label class="switch" wire:model="active">
                                    <input type="checkbox" value="{{$company->status}}"  wire:change="toggleStatus({{$company->id}})" wire:loading.attr="disabled" {{$company->status ? 'checked': ''}}>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>                                
                                @if($company->whatsapp)
                                    <a target="_blank" href="{{\App\Helpers\WhatsApp::getNumZap($company->whatsapp)}}" class="btn btn-xs btn-success text-white"><i class="fab fa-whatsapp"></i></a>
                                @endif
                                
                                <form class="btn btn-xs" action="{{--route('email.send')--}}" method="post">
                                    @csrf
                                    <input type="hidden" name="nome" value="{{ $company->name }}">
                                    <input type="hidden" name="email" value="{{ $company->email }}">
                                    <button title="Enviar Email" type="submit" class="btn btn-xs text-white bg-teal"><i class="fas fa-envelope"></i></button>
                                </form> 
                                <a wire:navigate href="visualizar-empresa/{{$company->id}}" class="btn btn-xs btn-info text-white"><i class="fas fa-search"></i></a>
                                <a wire:navigate href="{{ route('companies.edit', [ 'company' => $company->id ]) }}" class="btn btn-xs btn-default"><i class="fas fa-pen"></i></a>
                                <button type="button" class="btn btn-xs btn-danger text-white" wire:click="setDeleteId({{$company->id}})" title="Excluir">
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
        <div class="card-footer clearfix">  
            {{ $companies->links() }}  
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
                text: 'Você tem certeza que deseja excluir este Cliente?',
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