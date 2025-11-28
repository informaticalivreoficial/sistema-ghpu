<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-industry mr-2"></i> {{ $ocorrencia ? 'Editar' : 'Cadastrar' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('ocorrencias.index') }}">Ocorrências</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $ocorrencia ? 'Editar' : 'Cadastrar' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-teal card-outline">
        <div class="card-body text-muted">
            <div class="row mb-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="form-group">
                        <label class="labelforms text-muted"><b>*Selecione o Tipo de Ocorrência</b></label>
                        <select name="type" class="form-control @error('type') is-invalid @enderror" wire:model.live="type">
                            <option value="">Selecione...</option>
                            <option value="branco">Em Branco</option>
                            <option value="varreduras-fichas-sistemas">Varreduras de fichas x sistemas</option>
                            <option value="ocorrencias-diarias">Ocorrências Diárias</option>
                            <option value="passagem-de-turno">Passagem de Turno</option>
                        </select>    
                        @error('type')
                            <span class="error erro-feedback">{{ $message }}</span>
                        @enderror                    
                    </div>
                </div> 
            </div>
            {{-- Renderização dinâmica --}}
            <form wire:submit.prevent="save" autocomplete="off">
                @if($type === 'branco')
                    @include('livewire.dashboard.ocorrencias.forms.branco')

                @elseif($type === 'varreduras-fichas-sistemas')
                    @include('livewire.dashboard.ocorrencias.forms.varreduras-fichas-sistemas')

                @elseif($type === 'ocorrencias-diarias')
                    @include('livewire.dashboard.ocorrencias.forms.ocorrencias-diarias')

                @elseif($type === 'passagem-de-turno')
                    @include('livewire.dashboard.ocorrencias.forms.passagem-de-turno')
                @endif
                <div class="row text-right mt-3">
                    <div class="col-12 mb-4">
                        <button wire:click="save" wire class="btn btn-lg btn-success p-3">
                            <i class="nav-icon fas fa-check mr-2"></i> {{ $ocorrencia ? 'Atualizar Agora' : 'Cadastrar Agora' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        // Configurações do Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "4000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "preventDuplicates": false,
            "newestOnTop": true
        };

        Livewire.on('toast', (event) => {
            toastr[event.type](event.message);
            
            // Só faz scroll para o topo se for erro (validação)
            if (event.type === 'error') {
                setTimeout(() => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }, 100);
            }
        });
        
    });

    
    document.addEventListener('swal-redirect', function(event) {
        const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
        console.log(data);
        Swal.fire({
            title: data.title,
            text: data.text,
            icon: data.icon,
            showConfirmButton: false,
            timer: 3000 // fecha sozinho
        }).then(() => {
            if (data.redirect) { // ← Só redireciona se tiver URL
                window.location.href = data.redirect;
            }
        });
    });
</script>
@endpush

