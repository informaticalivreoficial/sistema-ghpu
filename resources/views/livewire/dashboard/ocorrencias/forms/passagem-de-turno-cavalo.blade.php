<div>
    <h1 class="text-xl text-center pb-4">Passagem de Turno</h1> 
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label class="labelforms">
                            De => 
                            @if ($ocorrencia)
                                {{ $ocorrencia->user->name }}
                            @else    
                                {{ auth()->user()->name }}
                            @endif
                             para:</label>
                        <div class="position-relative w-50">
                            <input type="text"
                                wire:model.live="destinatario"
                                wire:blur="hideDestinatarioSuggestions"
                                autocomplete="off"
                                class="form-control @error('destinatario') is-invalid @enderror"
                                placeholder="Nome do funcionário que está assumindo o turno">

                            @if ($showDestinatarioSuggestions && count($destinatarioSuggestions))
                                <ul class="list-group position-absolute w-100 shadow-sm" style="z-index: 1050; max-height: 220px; overflow-y: auto;">
                                    @foreach ($destinatarioSuggestions as $suggestion)
                                        <li class="list-group-item list-group-item-action"
                                            style="cursor: pointer;"
                                            wire:click="selectDestinatario({{ $suggestion['id'] }})"
                                            onmousedown="event.preventDefault()">
                                            <div class="font-weight-bold">{{ $suggestion['name'] }}</div>
                                            @if (! empty($suggestion['cargo']))
                                                <small class="text-muted">{{ $suggestion['cargo'] }}</small>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        @error('destinatario')
                            <span class="error erro-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>                     
            </div>
        </div>            
    </div>

    <div class="card">
        <div class="border rounded p-4 space-y-3">
            <h2 class="font-semibold mb-2">{{ $tituloContent }}</h2>
            <textarea wire:model.live="content" rows="14" class="w-full p-3 m-0 border border-gray-300 resize-none"></textarea>
        </div>
    </div>

    {{-- Turno --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Dados do Turno::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            <div class="row">
                <!-- Hóspedes -->
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <label class="font-weight-bold">Quantos hóspedes na pousada agora?</label>
                    <input type="number" 
                        class="form-control @error('form.turno.hospedes') is-invalid @enderror"
                        wire:model.live="form.turno.hospedes"
                        placeholder="Informe o total de pessoas"
                        min="0">
                    @error('form.turno.hospedes')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Apos ocupados -->
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <label class="font-weight-bold">Quantos quartos ocupados agora?</label>
                    <input type="number" 
                        class="form-control @error('form.turno.apto_ocupados') is-invalid @enderror"
                        wire:model.live="form.turno.apto_ocupados"
                        placeholder="Informe o total de apartamentos"
                        min="0">
                    @error('form.turno.apto_ocupados')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Reservas -->
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <label class="font-weight-bold">Quantos Check-ins Previstos?</label>
                    <input type="number" 
                        class="form-control @error('form.turno.reservas') is-invalid @enderror"
                        wire:model.live="form.turno.reservas"
                        placeholder="Informe o total"
                        min="0">
                    @error('form.turno.reservas')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Check out -->
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <label class="font-weight-bold">Quantos Check-outs à fazer?</label>
                    <input type="number" 
                        class="form-control @error('form.turno.checkouts') is-invalid @enderror"
                        wire:model.live="form.turno.checkouts"
                        placeholder="Informe o total"
                        min="0">
                    @error('form.turno.checkouts')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <hr class="mt-4 mb-4">

            <div class="row">
                <!-- Late Check-out -->
                <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                    <label class="font-weight-bold">Late Check-out?</label>
                    <input type="number" 
                        class="form-control @error('form.turno.latecheckouts') is-invalid @enderror"
                        wire:model.live="form.turno.latecheckouts"
                        placeholder="Informe o total"
                        min="0">
                    @error('form.turno.latecheckouts')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Pães -->
                <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                    <label class="font-weight-bold">🥖 Quantidade de Pães do Dia</label>
                    <input type="text" 
                        class="form-control @error('form.turno.paes') is-invalid @enderror"
                        wire:model.live="form.turno.paes"
                        placeholder="Informe o total de pães"
                    >
                    <small class="text-info">
                        * Pedido realizado entre 00:00 e 03:00
                    </small>
                    @error('form.turno.paes')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>  
                
                <!-- Código de cores -->
                <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                    <label class="font-weight-bold">Foi batido o código de cores antes de passar o turno?</label>
                    <div class="d-flex gap-3 mt-2">
                        <div class="form-check mr-3">
                            <input type="radio" 
                                class="form-check-input"
                                id="cores_sim"
                                wire:model.live="form.turno.codigo_cores"
                                value="sim">
                            <label class="form-check-label" for="cores_sim">Sim</label>
                        </div>

                        <div class="form-check">
                            <input type="radio" 
                                class="form-check-input"
                                id="cores_nao"
                                wire:model.live="form.turno.codigo_cores"
                                value="nao">
                            <label class="form-check-label" for="cores_nao">Não</label>
                        </div>
                    </div>
                    @error('form.turno.codigo_cores')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <hr class="mt-4 mb-4">

            <div class="row">               

                <!-- Luzes da calçada -->
                <div class="col-12">
                    <label class="font-weight-bold">As luzes?</label>
                    <div class="d-flex gap-3 mt-2">
                        <div class="form-check mr-3">
                            <input type="radio" 
                                class="form-check-input"
                                id="luz_ligada"
                                wire:model.live="form.turno.luzes_calcada_cavalo"
                                value="ligada">
                            <label class="form-check-label" for="luz_ligada">conforme</label>
                        </div>

                        <div class="form-check">
                            <input type="radio" 
                                class="form-check-input"
                                id="luz_desligada"
                                wire:model.live="form.turno.luzes_calcada_cavalo"
                                value="desligada">
                            <label class="form-check-label" for="luz_desligada">Não conforme</label>
                        </div>
                    </div>
                    <small class="text-info">
                        * ⏰ Acender todas às 18h<br>
                        * 🌙 Apagar todas às 22h (exceto recepção)<br>
                        * 🕛 Se necessário, manter acesas até no máximo 00h
                    </small>
                    @error('form.turno.luzes_calcada_cavalo')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <hr class="mt-4 mb-4">

            <!-- Movimento do caixa -->
            <label class="font-weight-bold">O movimento do caixa agora é:</label>

            <div class="row mt-2">
                <div class="col-md-4">
                    <input type="number" 
                        class="form-control"
                        placeholder="Valor em dinheiro"
                        wire:model.live="form.turno.caixa_dinheiro"
                        step="0.01"
                        min="0">
                    @error('form.turno.caixa_dinheiro')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <input type="number" 
                        class="form-control"
                        placeholder="Valor em cartões"
                        wire:model.live="form.turno.caixa_cartoes"
                        step="0.01"
                        min="0">
                    @error('form.turno.caixa_cartoes')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <input type="number" 
                        class="form-control"
                        placeholder="Valor Total"
                        wire:model.live="form.turno.caixa_faturamento"
                        step="0.01"
                        min="0">
                    @error('form.turno.caixa_faturamento')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>
            </div>

        </div>
    </div> 

    {{-- Secador de cabelo --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Secador de Cabelo (Estoque Fixo: 2 unidades)::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            @for($i = 1; $i <= 2; $i++)
                <div class="form-group mb-4">
                    <label><strong>Secador {{ $i }}</strong>
                    @error('form.secadores.'.$i)
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                    </label>
                    <div class="row">

                        <!-- Está na gaveta -->
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="radio"
                                    id="secador_{{ $i }}_gaveta"
                                    class="form-check-input"
                                    wire:model="form.secadores.{{ $i }}"
                                    value="gaveta">
                                <label class="form-check-label" for="secador_{{ $i }}_gaveta">
                                    Está na gaveta
                                </label>
                            </div>
                        </div>

                        <!-- Emprestado -->
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="form-check mr-2">
                                    <input type="radio"
                                        id="secador_{{ $i }}_emprestado"
                                        class="form-check-input"
                                        wire:model="form.secadores.{{ $i }}"
                                        value="emprestado">
                                    <label class="form-check-label" for="secador_{{ $i }}_emprestado">
                                        Emprestado — Apto:
                                    </label>
                                </div>

                                <!-- Campo texto -->
                                <input type="text"
                                    class="form-control ml-2"
                                    placeholder="Nº do Apto"
                                    wire:model="form.secadores_apto.{{ $i }}"
                                    style="max-width:160px;">
                            </div>                                
                            
                            @error('form.secadores_apto.'.$i)
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>
                </div>
                <hr style="margin-top:5px !important; margin-bottom: 5px !important;">
            @endfor

        </div>
    </div>    

    {{-- Chaves Extras --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Chaves Extras (Estoque Fixo: 36 unidades)::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            @for($i = 1; $i <= 36; $i++)
                <div class="form-group mb-4">
                    <label><strong>Chave {{ $i }}</strong>
                    @error('form.chaves_cavalo.'.$i)
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                    </label>
                    <div class="row">

                        <!-- Está na disponível -->
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="radio"
                                    id="chave_{{ $i }}_disponivel"
                                    class="form-check-input"
                                    wire:model="form.chaves_cavalo.{{ $i }}"
                                    value="disponivel">
                                <label class="form-check-label" for="chave_{{ $i }}_disponivel">
                                    Disponível
                                </label>
                            </div>
                        </div>

                        <!-- Emprestado -->
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="form-check mr-2">
                                    <input type="radio"
                                        id="chave_{{ $i }}_emprestado"
                                        class="form-check-input"
                                        wire:model="form.chaves_cavalo.{{ $i }}"
                                        value="emprestado">
                                    <label class="form-check-label" for="chave_{{ $i }}_emprestado">
                                        Emprestado — Apto:
                                    </label>
                                </div>

                                <!-- Campo texto -->
                                <input type="text"
                                    class="form-control ml-2"
                                    placeholder="Nº do Apto"
                                    wire:model="form.chave_apto.{{ $i }}"
                                    style="max-width:160px;">
                            </div>                                
                            
                            @error('form.chave_apto.'.$i)
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>
                </div>
                <hr style="margin-top:5px !important; margin-bottom: 5px !important;">
            @endfor
        </div>
    </div>

    {{-- Gás - Aquecedor - Lixeira --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Aquecedores / Gás / Lixeira::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">Como está a temperatura do Aquecedor Bicicletário?</label>
                        <input type="number" name="form.temperatura_aquecedor" class="form-control w-25"
                            wire:model.live="form.temperatura_aquecedor"
                            placeholder="°C">
                        @error('form.temperatura_aquecedor') <small class="text-danger">{{ $message }}</small> @enderror
                        <small class="text-info">obs. Se ABAIXO de 56°C, notificar a Manutenção e/ou Gerência IMEDIATAMENTE</small>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">Aquecedor Ala Praia</label>
                        <div class="d-flex flex-wrap">
                            <label class="mr-3">
                                <input type="radio" value="aberto" wire:model.live="form.chama_aquecedor"> Aberto
                            </label>

                            <label>
                                <input type="radio" value="fechado" wire:model.live="form.chama_aquecedor"> Fechado
                            </label>
                        </div>
                        @error('form.chama_aquecedor') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">Aquecedor Ala Rua</label>
                        <div class="d-flex flex-wrap">
                            <label class="mr-3">
                                <input type="radio" value="aberto" wire:model.live="form.aquecedor_ala_rua"> Aberto
                            </label>

                            <label>
                                <input type="radio" value="fechado" wire:model.live="form.aquecedor_ala_rua"> Fechado
                            </label>
                        </div>
                        @error('form.aquecedor_ala_rua') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">Gás</label>
                        <div class="d-flex flex-wrap">
                            <input type="text" placeholder="0%"  wire:model.live="form.gas1" class="form-control w-20 @error('gas1') is-invalid @enderror">
                            <input type="text" placeholder="0%"  wire:model.live="form.gas2" class="form-control w-20 @error('gas2') is-invalid @enderror">
                        </div>
                        <small class="text-info">
                            → Manter apenas um cilindro aberto.<br>
                            → Ao término, fechar o registro e abrir o próximo.<br>
                            → Caso o nível esteja abaixo de 20%, notificar a gerência.<br>
                        </small>
                        @error('form.gas1') <small class="text-danger">{{ $message }}</small> @enderror
                        @error('form.gas2') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">
                            Lixeira está vazia?
                        </label>
                        <div class="d-flex flex-wrap">
                            <label class="mr-3">
                                <input type="radio" value="sim" wire:model.live="form.lixeira_cavalo"> Sim
                            </label>

                            <label>
                                <input type="radio" value="nao" wire:model.live="form.lixeira_cavalo"> Não
                            </label>
                        </div>
                        <small class="text-info">
                            → Esvaziar a lixeira assim que encerrar o movimento da rua (entre 23h e 00h), ou antes caso o caminhão antecipe o trajeto.
                        </small>
                        @error('form.lixeira_cavalo') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>                
            </div>

        </div>
    </div>

    {{-- Piscina Ofurô --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Piscina / Ofurô::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-12 col-sm-12 col-md-6 lg-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">Motor da Piscina</label>
                        <div class="d-flex flex-wrap">
                            <label class="mr-3">
                                <input type="radio" value="ligado" wire:model.live="form.motor_piscina"> Ligado
                            </label>

                            <label>
                                <input type="radio" value="desligado" wire:model.live="form.motor_piscina"> Desligado
                            </label>
                        </div>
                        <small class="text-info">
                            → Verificar se está ligado às 10h e desligar às 18h<br>
                            → LED da piscina até as 22h<br>
                        </small>
                        @error('form.motor_piscina') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 lg-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">Ofurô</label>
                        <div class="d-flex flex-wrap">
                            <label class="mr-3">
                                <input type="radio" value="ligado" wire:model.live="form.motor_ofuro"> Ligado
                            </label>

                            <label>
                                <input type="radio" value="desligado" wire:model.live="form.motor_ofuro"> Desligado
                            </label>
                        </div>
                        <small class="text-info">
                            → Verificar se está ligado às 10h e desligar às 22h<br>
                            Obs.: O motor da piscina e o ofurô são ligados pela manutenção.<br>
                            → Apenas na ausência da manutenção, a recepção deverá ligar ambos.<br>
                        </small>
                        @error('form.motor_ofuro') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div> 
        </div>
    </div>

    {{-- Chaves serviço Diário --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Chaves de Serviço Diário — Estoque Fixo (15 unidades)::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body"> 
            @foreach ($itensChavesFixasCavalo as $key => $label)
                <div class="border rounded p-3 mb-3" wire:key="chave-{{ $key }}">
                    <label class="fw-bold">{{ $key }}. {{ $label }}</label>

                    <div class="row mt-2 align-items-center">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input
                                    type="radio"
                                    class="form-check-input"
                                    id="gaveta_{{ $key }}"
                                    name="chave_status_{{ $key }}"
                                    wire:model.live="form.chaves.{{ $key }}.status"
                                    value="gaveta">
                                <label class="form-check-label" for="gaveta_{{ $key }}">
                                    Está na gaveta
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check">
                                <input
                                    type="radio"
                                    class="form-check-input"
                                    id="com_{{ $key }}"
                                    name="chave_status_{{ $key }}"
                                    wire:model.live="form.chaves.{{ $key }}.status"
                                    value="com">
                                <label class="form-check-label" for="com_{{ $key }}">
                                    Está com:
                                </label>
                            </div>
                            
                        </div>

                        <div class="col-md-4">
                            @php $status = $form['chaves'][$key]['status'] ?? null; @endphp

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Nome da pessoa"
                                wire:model.live="form.chaves.{{ $key }}.pessoa"
                                @if($status !== 'com') disabled @endif>
                            @error('form.chaves.'.$key.'.pessoa')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    @error('form.chaves.'.$key.'.status')
                        <small class="text-danger d-block mt-2">{{ $message }}</small>
                    @enderror
                </div>
            @endforeach
        </div>
    </div>

    {{-- Cartões Recepção - DataCard --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Cartões::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <label><strong>Cartão 1 Recepção</strong></label>
                    <input 
                        type="text" 
                        class="form-control @error('form.cartao_1_cavalo') is-invalid @enderror"
                        wire:model="form.cartao_1_cavalo"
                    >
                    @error('form.cartao_1_cavalo') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <label><strong>Cartão 2 Recepção</strong></label>
                    <input 
                        type="text" 
                        class="form-control @error('form.cartao_2_cavalo') is-invalid @enderror"
                        wire:model="form.cartao_2_cavalo"
                    >
                    @error('form.cartao_2_cavalo') <small class="text-danger">{{ $message }}</small> @enderror
                </div> 
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <label><strong>Data Card 1</strong></label>
                    <input 
                        type="text" 
                        class="form-control @error('form.cartao_datacard1_cavalo') is-invalid @enderror"
                        wire:model="form.cartao_datacard1_cavalo"
                    >
                    @error('form.cartao_datacard1_cavalo') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <label><strong>Data Card 2</strong></label>
                    <input 
                        type="text" 
                        class="form-control @error('form.cartao_datacard2_cavalo') is-invalid @enderror"
                        wire:model="form.cartao_datacard2_cavalo"
                    >
                    @error('form.cartao_datacard2_cavalo') <small class="text-danger">{{ $message }}</small> @enderror
                </div>               
            </div>


            <div class="row mb-3">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <label><strong>Manutenção</strong></label>
                    <input 
                        type="text" 
                        class="form-control @error('form.cartao_manutencao_cavalo') is-invalid @enderror"
                        wire:model="form.cartao_manutencao_cavalo"
                    >
                    @error('form.cartao_manutencao_cavalo') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Cartões Camareira --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Cartões Camareiras (5 unidades)::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                @for($i = 1; $i <= 5; $i++)
                    <div class="col-md-6 mb-4">
                        <label><strong>Cartão {{ $i }}</strong></label>
                        <div class="d-flex flex-column">

                            <div class="form-check">
                                <input type="radio" 
                                    class="form-check-input"
                                    id="cartoes_camareira_cavalo{{ $i }}_disponivel"
                                    wire:model.live="form.cartoes_camareira_cavalo.{{ $i }}.status"
                                    value="disponivel">
                                <label class="form-check-label" for="cartoes_camareira_cavalo{{ $i }}_disponivel">Disponível</label>
                            </div>

                            <div class="form-check mt-2 d-flex align-items-center">
                                <input type="radio" 
                                    class="form-check-input"
                                    id="cartoes_camareira_cavalo{{ $i }}_func"
                                    wire:model.live="form.cartoes_camareira_cavalo.{{ $i }}.status"
                                    value="funcionario">
                                <label class="form-check-label mr-2" for="cartoes_camareira_cavalo{{ $i }}_func">
                                    Funcionário:
                                </label>

                                @php 
                                    $cartaocamareiraStatus = $form['cartoes_camareira_cavalo'][$i]['status'] ?? null;
                                @endphp

                                <input type="text" 
                                    class="form-control form-control-sm w-50 ml-2"
                                    wire:model.live="form.cartoes_camareira_cavalo.{{ $i }}.funcionario"
                                    placeholder="Nome"
                                    @if($cartaocamareiraStatus !== 'funcionario') disabled @endif>
                            </div>

                            @error('form.cartoes_camareira_cavalo.'.$i.'.status')
                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                            @enderror
                            
                            @error('form.cartoes_camareira_cavalo.'.$i.'.funcionario')
                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                            @enderror

                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    {{-- Status Aberto / Fechado --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Aberto - Fechado::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="form-group">
                        <label>
                            <strong>Rouparia</strong>
                        </label>

                        <div class="d-flex">
                            <div class="form-check mr-3">
                                <input type="radio" class="form-check-input"
                                    id="rouparia_fechado"
                                    wire:model="form.rouparia"
                                    value="fechado">

                                <label class="form-check-label" for="rouparia_fechado">
                                    Fechado
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="radio" class="form-check-input"
                                    id="rouparia_aberto"
                                    wire:model="form.rouparia"
                                    value="aberto">

                                <label class="form-check-label" for="rouparia_aberto">
                                    Aberto
                                </label>
                            </div>
                        </div>
                        @error('form.rouparia') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="form-group">
                        <label>
                            <strong>Portão Praia</strong>
                        </label>

                        <div class="d-flex">
                            <div class="form-check mr-3">
                                <input type="radio" class="form-check-input"
                                    id="portao_praia_fechado"
                                    wire:model="form.portao_praia"
                                    value="fechado">

                                <label class="form-check-label" for="portao_praia_fechado">
                                    Fechado
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="radio" class="form-check-input"
                                    id="portao_praia_aberto"
                                    wire:model="form.portao_praia"
                                    value="aberto">

                                <label class="form-check-label" for="portao_praia_aberto">
                                    Aberto
                                </label>
                            </div>
                        </div>
                        @error('form.portao_praia') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="form-group">
                        <label>
                            <strong>Portão Bicicletário</strong>
                        </label>

                        <div class="d-flex">
                            <div class="form-check mr-3">
                                <input type="radio" class="form-check-input"
                                    id="portao_bicicletario_fechado"
                                    wire:model="form.portao_bicicletario"
                                    value="fechado">

                                <label class="form-check-label" for="portao_bicicletario_fechado">
                                    Fechado
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="radio" class="form-check-input"
                                    id="portao_bicicletario_aberto"
                                    wire:model="form.portao_bicicletario"
                                    value="aberto">

                                <label class="form-check-label" for="portao_bicicletario_aberto">
                                    Aberto
                                </label>
                            </div>
                        </div>
                        @error('form.portao_bicicletario') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="form-group">
                        <label>
                            <strong>Portão Entrega</strong>
                        </label>

                        <div class="d-flex">
                            <div class="form-check mr-3">
                                <input type="radio" class="form-check-input"
                                    id="portao_entrega_fechado"
                                    wire:model="form.portao_entrega"
                                    value="fechado">

                                <label class="form-check-label" for="portao_entrega_fechado">
                                    Fechado
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="radio" class="form-check-input"
                                    id="portao_entrega_aberto"
                                    wire:model="form.portao_entrega"
                                    value="aberto">

                                <label class="form-check-label" for="portao_entrega_aberto">
                                    Aberto
                                </label>
                            </div>
                        </div>
                        @error('form.portao_entrega') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="form-group">
                        <label>
                            <strong>Porta Recepção</strong>
                        </label>

                        <div class="d-flex">
                            <div class="form-check mr-3">
                                <input type="radio" class="form-check-input"
                                    id="porta_recepcao_fechado"
                                    wire:model="form.porta_recepcao"
                                    value="fechado">

                                <label class="form-check-label" for="porta_recepcao_fechado">
                                    Fechado
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="radio" class="form-check-input"
                                    id="porta_recepcao_aberto"
                                    wire:model="form.porta_recepcao"
                                    value="aberto">

                                <label class="form-check-label" for="porta_recepcao_aberto">
                                    Aberto
                                </label>
                            </div>
                        </div>
                        @error('form.porta_recepcao') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Celulares Recepção --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Celulares de Serviço (2 unidades)::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="border rounded p-3 mb-3">
                <h5 class="mb-3">
                    <strong>Celular 1 Recepção</strong>
                </h5>
                <div class="row">
                    {{-- Bateria --}}
                    <div class="col-md-5">
                        <label><strong>Bateria (%)</strong></label>
                        <input type="number"
                            class="form-control"
                            wire:model="form.celular1_bateria"
                            placeholder="Informe % da carga">
                        @error('form.celular1_bateria')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Está com funcionário --}}
                    <div class="col-md-7">
                        <label><strong>Está com o funcionário?</strong></label>
                        <input type="text"
                            class="form-control"
                            wire:model="form.celular1_funcionario"
                            placeholder="Nome do funcionário ou local (se estiver em uso)">
                    </div>
                </div>
            </div>
            <div class="border rounded p-3 mb-3">
                <h5 class="mb-3">
                    <strong>Celular Recepção 2</strong>
                </h5>
                <div class="row">
                    {{-- Bateria --}}
                    <div class="col-md-5">
                        <label><strong>Bateria (%)</strong></label>
                        <input type="number"
                            class="form-control"
                            wire:model="form.celular2_bateria"
                            placeholder="Informe % da carga">
                        @error('form.celular2_bateria')
                            <small class="text-danger d-block mt-2">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Está com funcionário --}}
                    <div class="col-md-7">
                        <label><strong>Está com o funcionário?</strong></label>
                        <input type="text"
                            class="form-control"
                            wire:model="form.celular2_funcionario"
                            placeholder="Nome do funcionário ou local (se estiver em uso)">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Máquinas de Cartão --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Recepção (Máquinas de Cartão)::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-4">
                    <label><strong>Máquina Nº 1</strong></label>
                </div>

                <div class="col-md-4">
                    <label>Carga atual do aparelho (%)</label>
                </div>

                <div class="col-md-4">
                    <input type="number" class="form-control @error('form.maquina_safra_1') is-invalid @enderror"
                        wire:model="form.maquina_safra_1"
                        min="1" max="100"
                        placeholder="Informe %">
                    @error('form.maquina_safra_1') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-4">
                    <label><strong>Máquina Nº 2</strong></label>
                </div>

                <div class="col-md-4">
                    <label>Carga atual do aparelho (%)</label>
                </div>

                <div class="col-md-4">
                    <input type="number" class="form-control @error('form.maquina_safra_2') is-invalid @enderror"
                        wire:model="form.maquina_safra_2"
                        min="1" max="100"
                        placeholder="Informe %">
                    @error('form.maquina_safra_2') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Rádios Comunicadores de Serviços --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Rádios Comunicadores de Serviços (7 unidades)::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                @for($i = 1; $i <= 7; $i++)
                    <div class="col-md-6 mb-4">
                        <label><strong>{{ $i }}. Rádio Comunicador</strong></label>
                        <div class="d-flex flex-column">

                            <div class="form-check">
                                <input type="radio" 
                                    class="form-check-input"
                                    id="radio{{ $i }}_recepcao"
                                    wire:model.live="form.radios.{{ $i }}.status"
                                    value="base">
                                <label class="form-check-label" for="radio{{ $i }}_recepcao">Na recepção</label>
                            </div>

                            <div class="form-check mt-2 d-flex align-items-center">
                                <input type="radio" 
                                    class="form-check-input"
                                    id="radio{{ $i }}_func"
                                    wire:model.live="form.radios.{{ $i }}.status"
                                    value="funcionario">
                                <label class="form-check-label mr-2" for="radio{{ $i }}_func">
                                    Funcionário:
                                </label>

                                @php 
                                    $radioStatus = $form['radios'][$i]['status'] ?? null;
                                @endphp

                                <input type="text" 
                                    class="form-control form-control-sm w-50 ml-2"
                                    wire:model.live="form.radios.{{ $i }}.funcionario"
                                    placeholder="Nome"
                                    @if($radioStatus !== 'funcionario') disabled @endif>
                            </div>

                            @error('form.radios.'.$i.'.status')
                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                            @enderror
                            
                            @error('form.radios.'.$i.'.funcionario')
                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                            @enderror

                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
    
    {{-- Geladeira Recepção --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Geladeira Recepção::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12"> 
                    <textarea wire:model.live="form.geladeira_recepcao" rows="16" class="w-full p-3 m-0 border border-gray-300 resize-none"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>