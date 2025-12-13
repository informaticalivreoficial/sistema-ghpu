<div> 
    <h1 class="text-xl text-center pb-4">Passagem de Turno Hotel São Charbel</h1> 
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label class="labelforms">De => {{ auth()->user()->name }} para:</label>
                        <input type="text"  wire:model.live="destinatario" class="form-control w-50 @error('destinatario') is-invalid @enderror" placeholder="Nome do funcionário que está assumindo o turno">
                        @error('destinatario')
                            <span class="error erro-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>                     
            </div>
        </div>            
    </div>

    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — 3° Andar::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-12 col-sm-6 col-md-4 mb-3">
                    <div class="form-group">
                        <label class="labelforms">A sauna está ligada?</label>
                        <div class="d-flex flex-wrap">
                            <label class="mr-3">
                                <input type="radio" name="form.sauna" value="ligada" wire:model.live="form.sauna"> Ligada em uso
                            </label>

                            <label>
                                <input type="radio" name="form.sauna" value="desligada" wire:model.live="form.sauna"> Desligada e fechada
                            </label>
                        </div>
                        @error('form.sauna') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

            </div>

            <div class="row mb-3">
                <div class="col-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">Como está a temperatura do aquecedor?</label>
                        <input type="number" name="form.temperatura_aquecedor" class="form-control w-25"
                            wire:model.live="form.temperatura_aquecedor"
                            placeholder="°C">
                        @error('form.temperatura_aquecedor') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">A chama do aquecedor está acesa?</label>
                        <div class="d-flex flex-wrap">
                            <label class="mr-3">
                                <input type="radio" value="acesa" wire:model.live="form.chama_aquecedor"> Acesa
                            </label>

                            <label>
                                <input type="radio" value="apagada" wire:model.live="form.chama_aquecedor"> Apagada
                            </label>
                        </div>
                        @error('form.chama_aquecedor') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">O motor da piscina está ligado?</label>
                        <div class="d-flex flex-wrap">
                            <label class="mr-3">
                                <input type="radio" value="ligado" wire:model.live="form.motor_piscina"> Ligado
                            </label>

                            <label>
                                <input type="radio" value="desligado" wire:model.live="form.motor_piscina"> Desligado
                            </label>
                        </div>
                        @error('form.motor_piscina') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">
                            A piscina está coberta?
                            <small class="text-info">(deve-se cobrir todos os dias 22h às 8h)</small>
                        </label>
                        <div class="d-flex flex-wrap">
                            <label class="mr-3">
                                <input type="radio" value="coberta" wire:model.live="form.piscina_coberta"> Coberta
                            </label>

                            <label>
                                <input type="radio" value="descoberta" wire:model.live="form.piscina_coberta"> Descoberta
                            </label>
                        </div>
                        @error('form.piscina_coberta') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">
                            Portas e janelas da sala do Ibrain estão fechadas?
                        </label>
                        <div class="d-flex flex-wrap">
                            <label class="mr-3">
                                <input type="radio" value="fechadas" wire:model.live="form.ibrain_fechado"> Fechadas sem uso
                            </label>

                            <label>
                                <input type="radio" value="uso" wire:model.live="form.ibrain_fechado"> Em uso
                            </label>
                        </div>
                        @error('form.ibrain_fechado') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">O ar condicionado do salão Ibrain está ligado?</label>
                        <div class="d-flex flex-wrap">
                            <label class="mr-3">
                                <input type="radio" value="ligado" wire:model.live="form.ac_ibrain"> Ligado em uso
                            </label>

                            <label>
                                <input type="radio" value="desligado" wire:model.live="form.ac_ibrain"> Desligado
                            </label>
                        </div>
                        @error('form.ac_ibrain') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">As luzes da sala de TV estão ligadas?</label>
                        <div class="d-flex flex-wrap">
                            <label class="mr-3">
                                <input type="radio" value="ligadas" wire:model.live="form.luzes_tv"> Ligadas em uso
                            </label>

                            <label>
                                <input type="radio" value="desligadas" wire:model.live="form.luzes_tv"> Desligadas
                            </label>
                        </div>
                        @error('form.luzes_tv') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <label class="labelforms">
                            A TV do 3° andar está ligada?
                            <small class="text-info">(Deve-se ligar às 7h e desligar às 17h todos os dias)</small>
                        </label>
                        <div class="d-flex flex-wrap">
                            <label class="mr-3">
                                <input type="radio" value="ligada" wire:model.live="form.tv_3andar"> Ligada
                            </label>

                            <label>
                                <input type="radio" value="desligada" wire:model.live="form.tv_3andar"> Desligada fora de hora
                            </label>
                        </div>
                        @error('form.tv_3andar') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- AREA EXTERNA, ESTACIONAMENTO E LIXEIRAS --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Área Externa, Estacionamento e Lixeiras::</h3>
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
                            <strong>Porta interna da lixeira?</strong>
                            <br><small class="text-info">(Deve estar sempre fechada)</small>
                        </label>

                        <div class="d-flex">
                            <div class="form-check mr-3">
                                <input type="radio" class="form-check-input"
                                    id="porta_interna_fechada"
                                    wire:model="form.porta_interna"
                                    value="fechada">

                                <label class="form-check-label" for="porta_interna_fechada">
                                    Fechada
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="radio" class="form-check-input"
                                    id="porta_interna_aberta"
                                    wire:model="form.porta_interna"
                                    value="aberta">

                                <label class="form-check-label" for="porta_interna_aberta">
                                    Aberta
                                </label>
                            </div>
                        </div>
                        @error('form.porta_interna') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="form-group">
                        <label>
                            <strong>Porta externa da lixeira?</strong>
                            <br><small class="text-info">(Abrir todos os dias das 17h às 00h)</small>
                        </label>

                        <div class="d-flex">
                            <div class="form-check mr-3">
                                <input type="radio" class="form-check-input"
                                    id="porta_externa_fechada"
                                    wire:model="form.porta_externa"
                                    value="fechada">

                                <label class="form-check-label" for="porta_externa_fechada">
                                    Fechada
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="radio" class="form-check-input"
                                    id="porta_externa_aberta"
                                    wire:model="form.porta_externa"
                                    value="aberta">

                                <label class="form-check-label" for="porta_externa_aberta">
                                    Aberta
                                </label>
                            </div>
                        </div>
                        @error('form.porta_externa') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="form-group">
                        <label>
                            <strong>Luzes do Estacionamento 1?</strong>
                            <br><small class="text-info">(Só acender em uso)</small>
                        </label>

                        <div class="d-flex">
                            <div class="form-check mr-3">
                                <input type="radio" class="form-check-input"
                                    id="estacionamento_ligada"
                                    wire:model="form.luzes_estacionamento1"
                                    value="ligada">

                                <label class="form-check-label" for="estacionamento_ligada">
                                    Ligada
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="radio" class="form-check-input"
                                    id="estacionamento_desligada"
                                    wire:model="form.luzes_estacionamento1"
                                    value="desligada">

                                <label class="form-check-label" for="estacionamento_desligada">
                                    Desligada
                                </label>
                            </div>
                        </div>
                        @error('form.luzes_estacionamento1') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Recepção (Celulares e Máquinas de Cartão)::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-4">
                    <label><strong>Máquina Safra Nº 1</strong></label>
                </div>

                <div class="col-md-4">
                    <label>Carga atual do aparelho (%)</label>
                </div>

                <div class="col-md-4">
                    <input type="number" class="form-control @error('form.maquina_safra_1') is-invalid @enderror"
                        wire:model="form.maquina_safra_1"
                        min="2" max="10"
                        placeholder="Informe %">
                    @error('form.maquina_safra_1') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-4">
                    <label><strong>Máquina Safra Nº 2</strong></label>
                </div>

                <div class="col-md-4">
                    <label>Carga atual do aparelho (%)</label>
                </div>

                <div class="col-md-4">
                    <input type="number" class="form-control @error('form.maquina_safra_2') is-invalid @enderror"
                        wire:model="form.maquina_safra_2"
                        min="2" max="10"
                        placeholder="Informe %">
                    @error('form.maquina_safra_2') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-4">
                    <label><strong>Celular Nº 12996429664</strong></label>
                </div>

                <div class="col-md-4">
                    <label>Carga atual do aparelho (%)</label>
                </div>

                <div class="col-md-4">
                    <input type="number" class="form-control @error('form.celular_1') is-invalid @enderror"
                        wire:model="form.celular_1"
                        min="2" max="10"
                        placeholder="Informe %">
                    @error('form.celular_1') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-4">
                    <label><strong>Celular Nº 1238321080</strong></label>
                </div>

                <div class="col-md-4">
                    <label>Carga atual do aparelho (%)</label>
                </div>

                <div class="col-md-4">
                    <input type="number" class="form-control @error('form.celular_2') is-invalid @enderror"
                        wire:model="form.celular_2"
                        min="2" max="10"
                        placeholder="Informe %">
                    @error('form.celular_2') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

        </div>
    </div>


    <div class="card mt-4">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Chaves de Serviço Diário — Estoque Fixo (15 unidades)::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            @php
                $itens = [
                    17 => 'Chave Mestra Elevador',
                    18 => 'Molho de Chave Mestra de Todos os Aptos',
                    19 => 'Cartão TAG - Sala de Eventos Ibrain',
                    20 => 'Chave da Porta de Vidro da Recepção',
                    21 => 'Chave da Lixeira (2)',
                    22 => 'Chave Porta Sauna',
                    23 => 'Chave Porta Manutenção',
                    24 => 'Controle Remoto P1',
                    25 => 'Controle Remoto P2',
                    26 => 'Chave Cadeado Bike Roxa',
                    27 => 'Chave Cadeado Bike Vermelha',
                    28 => 'Chave HUB 3° Andar',
                    29 => 'Chave Porta Automática Recepção Entrada',
                    30 => 'Chave (Vareta) Abertura do P2',
                    31 => 'Chave Cartão Magnético Rouparia 3° Andar',
                ];
            @endphp

            @foreach ($itens as $key => $label)
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


    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Controles de TV Box (Estoque fixo: 7 unidades)::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            <!-- Linha: Apto 103 -->
            <div class="form-group">
                <label><strong>Apto 103</strong> @error('form.tvbox_103') <small class="text-danger">{{ $message }}</small> @enderror</label>
                <div class="row">

                    <!-- Está no pote -->
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="radio" id="tvbox_103_pote" 
                                class="form-check-input"
                                wire:model="form.tvbox_103"
                                value="pote">
                            <label class="form-check-label" for="tvbox_103_pote">Está no pote</label>
                        </div>
                    </div>

                    <!-- Está em uso -->
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="radio" id="tvbox_103_uso" 
                                class="form-check-input"
                                wire:model="form.tvbox_103"
                                value="uso">
                            <label class="form-check-label" for="tvbox_103_uso">Está emprestado / em uso</label>
                        </div>
                    </div>
                </div>                    
            </div>
            
            <hr style="margin-top:5px !important; margin-bottom: 5px !important;">

            <!-- Linha: Apto 201 -->
            <div class="form-group">
                <label><strong>Apto 201</strong> @error('form.tvbox_201') <small class="text-danger">{{ $message }}</small> @enderror</label>
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="radio" id="tvbox_201_pote" 
                                class="form-check-input"
                                wire:model="form.tvbox_201"
                                value="pote">
                            <label class="form-check-label" for="tvbox_201_pote">Está no pote</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="radio" id="tvbox_201_uso" 
                                class="form-check-input"
                                wire:model="form.tvbox_201"
                                value="uso">
                            <label class="form-check-label" for="tvbox_201_uso">Está emprestado / em uso</label>
                        </div>
                    </div>

                </div>
            </div>

            <hr style="margin-top:5px !important; margin-bottom: 5px !important;">

            <!-- Linha: Apto 203 -->
            <div class="form-group">
                <label><strong>Apto 203</strong> @error('form.tvbox_203') <small class="text-danger">{{ $message }}</small> @enderror</label>
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="radio" id="tvbox_203_pote" 
                                class="form-check-input"
                                wire:model="form.tvbox_203"
                                value="pote">
                            <label class="form-check-label" for="tvbox_203_pote">Está no pote</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="radio" id="tvbox_203_uso" 
                                class="form-check-input"
                                wire:model="form.tvbox_203"
                                value="uso">
                            <label class="form-check-label" for="tvbox_203_uso">Está emprestado / em uso</label>
                        </div>
                    </div>

                </div>
            </div>

            <hr style="margin-top:5px !important; margin-bottom: 5px !important;">

            <!-- Linha: Apto 204 -->
            <div class="form-group">
                <label><strong>Apto 204</strong> @error('form.tvbox_204') <small class="text-danger">{{ $message }}</small> @enderror</label>
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="radio" id="tvbox_204_pote" 
                                class="form-check-input"
                                wire:model="form.tvbox_204"
                                value="pote">
                            <label class="form-check-label" for="tvbox_204_pote">Está no pote</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="radio" id="tvbox_204_uso" 
                                class="form-check-input"
                                wire:model="form.tvbox_204"
                                value="uso">
                            <label class="form-check-label" for="tvbox_204_uso">Está emprestado / em uso</label>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>


    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Secador de Cabelo (Estoque Fixo: 5 unidades)::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            @for($i = 1; $i <= 5; $i++)
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
                                    id="radio{{ $i }}_base"
                                    wire:model.live="form.radios.{{ $i }}.status"
                                    value="base">
                                <label class="form-check-label" for="radio{{ $i }}_base">Na base</label>
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


    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Celulares de Serviço (8 unidades)::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            {{-- LOOP DOS 8 CELULARES --}}
            @foreach($celulares as $i => $celular)
                <div class="border rounded p-3 mb-3">
                    <h5 class="mb-3">
                        <strong>{{ $i + 1 }}.</strong> {{ $celular['titulo'] }}
                    </h5>

                    <div class="row">
                        {{-- Bateria --}}
                        <div class="col-md-5">
                            <label><strong>Bateria (%)</strong></label>
                            <input type="number"
                                class="form-control"
                                wire:model="form.celulares.{{ $i }}.bateria"
                                placeholder="Informe % da carga">
                            @error('form.celulares.'.$i.'.bateria')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Está com funcionário --}}
                        <div class="col-md-7">
                            <label><strong>Está com o funcionário?</strong></label>
                            <input type="text"
                                class="form-control"
                                wire:model="form.celulares.{{ $i }}.funcionario"
                                placeholder="Nome do funcionário (se estiver em uso)">
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>


    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Gavetas de Jogos e Controles do 3° e Ar Cond.::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            @php
                $itens = [
                    ['id' => 1, 'label' => 'Controle PS4 — Nº 1'],
                    ['id' => 2, 'label' => 'Controle PS4 — Nº 2'],
                    ['id' => 3, 'label' => 'Jogo FIFA 25'],
                    ['id' => 4, 'label' => 'Jogo Days Gone'],
                    ['id' => 5, 'label' => 'Jogo Gran Turismo'],
                    ['id' => 6, 'label' => 'God of War'],
                    ['id' => 7, 'label' => 'Controle TV 3° Andar'],
                    ['id' => 8, 'label' => 'Controle Ar iBrain'],
                    ['id' => 9, 'label' => 'Controle Ar Recepção'],
                    ['id' => 10, 'label' => 'FIFA FC 26'],
                    ['id' => 11, 'label' => 'Formula 1 — 24'],
                ];
            @endphp

            <div class="row">
                @foreach ($itens as $item)
                    <div class="col-md-6">
                        <div class="border p-3 mb-3 rounded">

                            <label class="font-weight-bold">{{ $item['id'] }}. {{ $item['label'] }}</label>
                            @error('form.gavetas.'.$item['id'])
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                            <div class="mt-2">
                                <div class="form-check">
                                    <input type="radio"
                                        class="form-check-input"
                                        id="gaveta_{{ $item['id'] }}"
                                        wire:model="form.gavetas.{{ $item['id'] }}"
                                        value="gaveta">
                                    <label class="form-check-label" for="gaveta_{{ $item['id'] }}">
                                        Está na gaveta
                                    </label>
                                </div>

                                <div class="form-check mt-2">
                                    <input type="radio"
                                        class="form-check-input"
                                        id="emprestado_{{ $item['id'] }}"
                                        wire:model.live="form.gavetas.{{ $item['id'] }}"
                                        value="emprestado">
                                    <label class="form-check-label" for="emprestado_{{ $item['id'] }}">
                                        Emprestado, Nº APTO:
                                    </label>

                                    @php
                                        $gavetaStatus = $form['gavetas'][$item['id']] ?? null;
                                    @endphp

                                    @if($gavetaStatus === 'emprestado')
                                        <input type="text"
                                            class="form-control mt-2 @error('form.apto_emprestado.'.$item['id']) is-invalid @enderror"
                                            placeholder="Informe o nº do apartamento"
                                            wire:model.live="form.apto_emprestado.{{ $item['id'] }}">
                                        
                                        @error('form.apto_emprestado.'.$item['id'])
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>


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
                <div class="col-md-6">
                    <label class="font-weight-bold">Quantos hóspedes no hotel agora?</label>
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
                <div class="col-md-6">
                    <label class="font-weight-bold">Quantos aptos ocupados agora?</label>
                    <input type="number" 
                        class="form-control @error('form.turno.apto_ocupados') is-invalid @enderror"
                        wire:model.live="form.turno.apto_ocupados"
                        placeholder="Informe o total de apartamentos"
                        min="0">
                    @error('form.turno.apto_ocupados')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mt-3">
                <!-- Reservas -->
                <div class="col-md-6">
                    <label class="font-weight-bold">Quantas reservas para chegar?</label>
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
                <div class="col-md-6">
                    <label class="font-weight-bold">Quantos check outs à fazer?</label>
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

            <div class="row mt-3">
                <!-- Interditados -->
                <div class="col-md-6">
                    <label class="font-weight-bold">Quantos aptos interditados?</label>
                    <input type="number" 
                        class="form-control @error('form.turno.interditados') is-invalid @enderror"
                        wire:model.live="form.turno.interditados"
                        placeholder="Informe o total"
                        min="0">
                    @error('form.turno.interditados')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Cartões emprestados -->
                <div class="col-md-6">
                    <label class="font-weight-bold">Cartões magnéticos emprestados?</label>
                    <input type="number" 
                        class="form-control @error('form.turno.cartoes_emprestados') is-invalid @enderror"
                        wire:model.live="form.turno.cartoes_emprestados"
                        placeholder="Quantidade de cartões"
                        min="0">
                    @error('form.turno.cartoes_emprestados')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mt-3">
                <!-- Cartões aguardando -->
                <div class="col-md-6">
                    <label class="font-weight-bold">Cartões aguardando para uso?</label>
                    <input type="number" 
                        class="form-control @error('form.turno.cartoes_aguardando') is-invalid @enderror"
                        wire:model.live="form.turno.cartoes_aguardando"
                        placeholder="Quantidade de cartões"
                        min="0">
                    @error('form.turno.cartoes_aguardando')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Luzes da calçada -->
                <div class="col-md-6">
                    <label class="font-weight-bold">As luzes da calçada estão acesas? 17h30</label>
                    <div class="d-flex gap-3 mt-2">
                        <div class="form-check mr-3">
                            <input type="radio" 
                                class="form-check-input"
                                id="luz_ligada"
                                wire:model.live="form.turno.luzes_calcada"
                                value="ligada">
                            <label class="form-check-label" for="luz_ligada">Ligada</label>
                        </div>

                        <div class="form-check">
                            <input type="radio" 
                                class="form-check-input"
                                id="luz_desligada"
                                wire:model.live="form.turno.luzes_calcada"
                                value="desligada">
                            <label class="form-check-label" for="luz_desligada">Desligada</label>
                        </div>
                    </div>
                    @error('form.turno.luzes_calcada')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <hr class="mt-4">

            <!-- Responsabilidade do caixa -->
            <div class="form-group">
                <label class="font-weight-bold">Responsabilidade do caixa entregue para:</label>
                <input type="text" 
                    class="form-control @error('form.turno.caixa_responsavel') is-invalid @enderror"
                    wire:model.live="form.turno.caixa_responsavel"
                    placeholder="Nome do funcionário">
                @error('form.turno.caixa_responsavel')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            <!-- Cartão mestre -->
            <div class="form-group mt-3">
                <label class="font-weight-bold">O cartão mestre da recepção está no caixa?</label>
                <div class="d-flex gap-3 mt-2">
                    <div class="form-check mr-3">
                        <input type="radio" 
                            class="form-check-input"
                            id="cartao_mestre_sim"
                            wire:model.live="form.turno.cartao_mestre"
                            value="sim">
                        <label class="form-check-label" for="cartao_mestre_sim">Sim</label>
                    </div>

                    <div class="form-check">
                        <input type="radio" 
                            class="form-check-input"
                            id="cartao_mestre_nao"
                            wire:model.live="form.turno.cartao_mestre"
                            value="nao">
                        <label class="form-check-label" for="cartao_mestre_nao">Não</label>
                    </div>
                </div>
                @error('form.turno.cartao_mestre')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            <!-- Chave aquecedor -->
            <div class="form-group mt-3">
                <label class="font-weight-bold">A chave do aquecedor está no caixa?</label>
                <div class="d-flex gap-3 mt-2">
                    <div class="form-check mr-3">
                        <input type="radio" 
                            class="form-check-input"
                            id="aquecedor_sim"
                            wire:model.live="form.turno.aquecedor"
                            value="sim">
                        <label class="form-check-label" for="aquecedor_sim">Sim</label>
                    </div>

                    <div class="form-check">
                        <input type="radio" 
                            class="form-check-input"
                            id="aquecedor_nao"
                            wire:model.live="form.turno.aquecedor"
                            value="nao">
                        <label class="form-check-label" for="aquecedor_nao">Não</label>
                    </div>
                </div>
                @error('form.turno.aquecedor')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            <!-- Cartões extras -->
            <div class="form-group mt-3">
                <label class="font-weight-bold">Cartões extras (20 und.) estão no caixa?</label>
                <div class="d-flex gap-3 mt-2">
                    <div class="form-check mr-3">
                        <input type="radio" 
                            class="form-check-input"
                            id="cartoes_extras_sim"
                            wire:model.live="form.turno.cartoes_extras"
                            value="sim">
                        <label class="form-check-label" for="cartoes_extras_sim">Sim</label>
                    </div>

                    <div class="form-check">
                        <input type="radio" 
                            class="form-check-input"
                            id="cartoes_extras_nao"
                            wire:model.live="form.turno.cartoes_extras"
                            value="nao">
                        <label class="form-check-label" for="cartoes_extras_nao">Não</label>
                    </div>
                </div>
                @error('form.turno.cartoes_extras')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror

                @if(isset($form['turno']['cartoes_extras']) && $form['turno']['cartoes_extras'] === 'nao')
                    <input type="text" 
                        class="form-control mt-2"
                        placeholder="Informe onde estão"
                        wire:model.live="form.turno.cartoes_extras_local">
                    @error('form.turno.cartoes_extras_local')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                @endif
            </div>

            <!-- Código de cores -->
            <div class="form-group mt-3">
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

            <hr class="mt-4">

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
                        placeholder="Valor faturamento"
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


    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Checklist — Chaves Mecânicas dos Apartamentos::</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($chavesMec as $index => $apto) 
                    <div class="col-lg-4 mb-3">
                        <label class="labelforms"><strong>{{ $apto }}</strong></label>

                        <div class="form-group">
                            <select class="form-control mb-2 @error('form.chaves_mecanicas.'.$index.'.status') is-invalid @enderror"
                                    wire:model.live="form.chaves_mecanicas.{{ $index }}.status">
                                <option value="">Selecione</option>
                                <option value="recepcao">RECEPÇÃO</option>
                                <option value="pessoa">ESTÁ COM</option>
                            </select>
                            
                            @error('form.chaves_mecanicas.'.$index.'.status')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror

                            @php
                                $chaveStatus = $form['chaves_mecanicas'][$index]['status'] ?? null;
                            @endphp

                            <input type="text"
                                class="form-control mt-2 @error('form.chaves_mecanicas.'.$index.'.pessoa') is-invalid @enderror"
                                placeholder="Nome da pessoa"
                                wire:model.live="form.chaves_mecanicas.{{ $index }}.pessoa"
                                @if($chaveStatus !== 'pessoa') disabled @endif>
                            
                            @error('form.chaves_mecanicas.'.$index.'.pessoa')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>