<div 
    x-data="{
        openLightbox(src) {
            basicLightbox.create(`<img src='${src}' style='max-width:90vw; max-height:90vh;'>`).show()
        }
    }"    
>   
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8">
                    <h2 class="m-0 text-dark">
                        @if ($lastTurnoDate)
                            Dados da última passagem - {{ $lastTurnoDate }}
                        @else
                            Painel de Controle
                        @endif
                    </h2>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Início</a></li>
                        <li class="breadcrumb-item active">Painel de Controle</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            @if($lastTurno)
                    <div class="row">

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $lastTurno['hospedes'] }}</h3>
                                    <p>Hóspedes</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $lastTurno['aptos'] }}</h3>
                                    <p>Aptos ocupados</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-bed"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $lastTurno['reservas'] }}</h3>
                                    <p>Reservas</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $lastTurno['checkouts'] }}</h3>
                                    <p>Check-outs</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-door-open"></i>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-4 col-12 col-md-4">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3>R$ {{ number_format($lastTurno['caixa_cartao'], 2, ',', '.') }}</h3>
                                    <p>Caixa (Cartões)</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-12 col-md-4">
                            <div class="small-box bg-teal">
                                <div class="inner">
                                    <h3>R$ {{ number_format($lastTurno['caixa_dinheiro'], 2, ',', '.') }}</h3>
                                    <p>Caixa (Dinheiro)</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-12 col-md-4">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>R$ {{ number_format($lastTurno['caixa_total'], 2, ',', '.') }}</h3>
                                    <p>Caixa Total</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-cash-register"></i>
                                </div>
                            </div>
                        </div>

                    </div>
                @endif

            <div class="row mt-3 mb-3">
                <div class="col-lg-12">
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-2 border-b">
                            <h3 class="text-sm font-semibold text-gray-800">Últimas Ocorrências</h3>
                        </div>

                        <div wire:poll.5s="refreshOcorrencias" class="divide-y">
                            @forelse($lastOcorrencias as $ocorrencia)
                                <div class="flex items-center px-6 py-2 hover:bg-gray-50 transition">

                                    <!-- ESQUERDA -->
                                    <div class="flex items-center space-x-4 flex-1 min-w-0">
                                        <img
                                            src="{{ $ocorrencia->user->avatar
                                                ? asset('storage/'.$ocorrencia->user->avatar)
                                                : asset('theme/images/image.jpg') }}"
                                            class="w-10 h-10 rounded-full object-cover flex-shrink-0"
                                        >

                                        <span class="text-gray-800 font-medium truncate">
                                            <strong>{{ $ocorrencia->user->name }}</strong> -> 
                                            {{ $ocorrencia->title ?? 'Ocorrência' }}
                                        </span>
                                    </div>

                                    <!-- DATA (LARGURA FIXA) -->
                                    <div class="w-36 text-right text-sm text-gray-500 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($ocorrencia->created_at)->format('d/m/Y H:i') }}
                                    </div>

                                    <!-- AÇÕES (LARGURA FIXA) -->
                                    <div class="w-20 flex items-center justify-end space-x-2">
                                        <a target="_blank"
                                        href="{{ route('ocorrencia.pdf', $ocorrencia->id) }}"
                                        class="text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-search"></i>
                                        </a>

                                        @if(\Carbon\Carbon::parse($ocorrencia->created_at)->gt(now()->subDay()))
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">
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
            </div>

            @role(['super-admin'])
            <livewire:dashboard.reports.dashboard-stats />  
            @endrole

            <div class="row">
                @role(['super-admin', 'admin'])
                    <livewire:dashboard.github-updates />
                @endrole
                
                <div class="col-lg-8">
                    @role(['super-admin'])
                        <div class="card">
                            <div class="card-header border-transparent">
                                <h3 class="card-title">Top 5 Posts mais visitados</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>Imagem</th>
                                                <th>Título</th>
                                                <th>Status</th>
                                                <th>Visitas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topposts as $post)
                                                <tr>
                                                    <td>
                                                        <img src="{{ $post->cover() }}"
                                                            width="60"
                                                            style="cursor:pointer; border-radius:4px"
                                                            @click="openLightbox('{{ $post->cover() }}')"
                                                            >
                                                    </td>
                                                    <td>{{ $post->title }}</td>
                                                    <td>
                                                        @php
                                                            $badge = [
                                                                1 => 'success',
                                                                0 => 'warning'
                                                            ][$post->status] ?? 'secondary';
                                                            $status = [
                                                                1 => 'Ativo',
                                                                0 => 'Inativo'
                                                            ][$post->status] ?? '';
                                                        @endphp

                                                        <span class="badge badge-{{ $badge }}">
                                                            {{ $status }}
                                                        </span>
                                                    </td>

                                                    <td>{{ $post->views }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-3">
                                                        Nenhum post encontrado.
                                                    </td>
                                                </tr>
                                            @endforelse                                    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer clearfix">
                                <a href="{{route('posts.create')}}" class="btn btn-sm btn-info float-left">Cadastrar Novo</a>
                                <a href="{{route('posts.index')}}" class="btn btn-sm btn-secondary float-right">Ver Todos</a>
                            </div>
                        </div>
                    @endrole
                </div>
            </div>        
        </div>
    </div>
    
</div>

{{--
@push('scripts')  
    @if(session()->has('toastr'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                toastr["{{ session('toastr.type') }}"](
                    "{{ session('toastr.message') }}",
                    "{{ session('toastr.title') }}"
                );
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                };
            });
        </script>
    @endif
@endpush
--}}