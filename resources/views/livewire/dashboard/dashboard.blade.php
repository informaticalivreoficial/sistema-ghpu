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
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Painel de Controle</h1>
                </div>
                <div class="col-sm-6">
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
            <div class="row mt-3 mb-3">
                <div class="col-lg-7">
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-2 border-b">
                            <h3 class="text-sm font-semibold text-gray-800">Últimas Ocorrências</h3>
                        </div>

                        <div wire:poll.5s="refreshOcorrencias" class="divide-y">
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
                                    <div class="flex items-center text-gray-500 text-sm">
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
            </div>

            {{-- Stats Reports 
            <livewire:dashboard.reports.dashboard-stats />  
            --}}

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