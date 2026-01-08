<div>
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1><i class="fas fa-chart-bar mr-2"></i> Dados da última passagem - {{ $lastTurnoDate }}</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Relatórios de Ocorrências</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

    <div class="card">
        <div class="card-body">
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

                    <div class="col-lg-4 col-12">
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

                    <div class="col-lg-4 col-12">
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

                    <div class="col-lg-4 col-12">
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


            <div class="row">
                {{-- 📊 Por tipo --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Ocorrências por tipo</h3>
                        </div>

                        <div class="card-body">
                            <canvas id="occurrencesByType"></canvas>
                        </div>
                    </div>
                </div>

                {{-- 📅 Por período --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Ocorrências por período</h3>
                        </div>

                        <div class="card-body">
                            <canvas id="occurrencesByPeriod"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // 📊 Por tipo
        new Chart(document.getElementById('occurrencesByType'), {
            type: 'pie',
            data: {
                labels: @json(array_keys($chartByType)),
                datasets: [{
                    data: @json(array_values($chartByType)),
                    backgroundColor: ['#28a745', '#17a2b8', '#ffc107'],
                }]
            }
        });

        // 📅 Por período
        new Chart(document.getElementById('occurrencesByPeriod'), {
            type: 'bar',
            data: {
                labels: @json(array_keys($chartByPeriod)),
                datasets: [{
                    label: 'Ocorrências',
                    data: @json(array_values($chartByPeriod)),
                    backgroundColor: '#007bff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });

    });
</script>
