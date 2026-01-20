<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Ocorrência #{{ $ocorrencia->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #555;
        }

        h1 {
            margin-bottom: 10px;
        }

        @page {
            margin: 110px 40px 60px 40px;
        }

        footer {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #555;
        }

        header {
            position: fixed;
            top: -90px;
            left: 0;
            right: 0;
            height: 80px;
            border-bottom: 1px solid #555;
            padding-bottom: 10px;
        }

        .header-container {
            display: table;
            width: 100%;
        }

        .logo {
            display: table-cell;
            width: 120px;
            vertical-align: middle;
        }

        .logo img {
            max-height: 60px;
        }

        .company-info {
            display: table-cell;
            vertical-align: middle;
            font-size: 11px;
            padding-left: 10px;
        }

        .company-info strong {
            font-size: 13px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 50px;
            color: rgba(0, 0, 0, 0.08);
            z-index: -1;
            white-space: nowrap;
        }

        .section {
            margin-top: 20px;
        }

        .section-title {
            background: #f2f2f2;
            padding: 6px 8px;
            font-weight: bold;
            border-left: 4px solid #000;
            margin-bottom: 8px;
        }

        .checklist {
            margin-top: 5px;
        }

        .item {
            display: table;
            width: 100%;
            border-bottom: 1px dashed #ccc;
            padding: 4px 0;
        }

        .item span {
            display: table-cell;
            vertical-align: middle;
        }

        .item span:first-child {
            width: 90%;
        }

        .status {
            width: 10%;
            text-align: right;
            font-weight: bold;
        }

        .ok {
            color: #0a7a0a;
        }

        .no {
            color: #c40000;
        }

        .obs {
            border: 1px solid #ccc;
            padding: 8px;
            min-height: 60px;
            margin-top: 5px;
        }

        .meta-box {
            margin: 15px 0 18px 0;
            border: 1px solid #000;
            font-size: 11px;
        }

        .meta-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #000;
        }

        .meta-row:last-child {
            border-bottom: none;
        }

        .meta-label {
            display: table-cell;
            width: 15%;
            padding: 6px 8px;
            font-weight: bold;
            background: #f2f2f2;
            border-right: 1px solid #000;
            text-align: right;
        }

        .meta-value {
            display: table-cell;
            width: 35%;
            padding: 6px 8px;
            border-right: 1px solid #000;
        }

        .meta-row .meta-value:last-child {
            border-right: none;
        }

        .page-break {
            page-break-after: always;
        }
        
    </style>
</head>
<body>
    <header>
        <div class="header-container">

            <div class="logo">
                <img src="{{ $company->logoPathForPdf() }}" alt="{{$company->alias_name}}" style="max-height: 60px;">
            </div>

            <div class="company-info">
                <strong>{{$company->alias_name}}</strong><br>
                CNPJ: {{$company->document_company}}<br>
                Endereço: {{$company->street}}, {{$company->number}} — {{$company->city}}/{{$company->state}}<br>
                Telefone: {{$company->phone}} • Email: {{$company->email}}
            </div>

        </div>
    </header>

    {{-- MARCA D'ÁGUA (opcional) --}}
    <div class="watermark">
        DOCUMENTO INTERNO
    </div>

    <h1 style="text-align: center;">Ocorrência #{{ $ocorrencia->id }}</h1>

    <div class="meta-box">
        <div class="meta-row">
            <div class="meta-label">Tipo:</div>
            <div class="meta-value">
                @switch($ocorrencia->type)
                    @case('varreduras-fichas-sistemas')
                        Varredura de Fichas x Sistemas
                        @break

                    @case('ocorrencias-diarias')
                        Ocorrências Diárias
                        @break

                    @case('passagem-de-turno')
                        Passagem de Turno
                        @break

                    @case('passagem-de-turno-cavalo')
                        Passagem de Turno
                        @break

                    @case('branco')
                        Em Branco
                        @break
                @endswitch
            </div>

            <div class="meta-label">Colaborador:</div>
            <div class="meta-value">
                {{ $ocorrencia->user->name }}
            </div>
        </div>

        <div class="meta-row">
            <div class="meta-label">Título:</div>
            <div class="meta-value">
                {{ $ocorrencia->title }}
            </div>

            <div class="meta-label">Data:</div>
            <div class="meta-value">
                {{ $ocorrencia->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>  
        
    @if ($ocorrencia->type === 'passagem-de-turno')     
        <p>       
            <strong>Passando para:</strong> {{ $ocorrencia->destinatario }}  
        </p> 
    @endif    

    @if ($ocorrencia->type === 'branco' || $ocorrencia->type === 'ocorrencias-diarias')
        <p><strong>Descrição:</strong></p>
        <p>{!! $ocorrencia->content !!}</p>
    @endif

    @if ($ocorrencia->type === 'passagem-de-turno')     
        <h2>Checklist — 3º Andar</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            @foreach($data['checklist_3_andar'] as $label => $value)
                <tr>
                    <td width="60%"><strong>{{ $label }}</strong></td>
                    <td width="40%">{{ ucfirst($value) }}</td>
                </tr>
            @endforeach
        </table>

        

        <h2>Checklist — Área Externa, Estacionamento e Lixeiras</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            @foreach($data['checklist_estacionamento_lixeiras'] as $label => $value)
                <tr>
                    <td><strong>{{ $label }}</strong></td>
                    <td>{{ $value }}</td>
                </tr>
            @endforeach
        </table>

        <h2>Checklist — Recepção (Celulares e Máquinas de Cartão)</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            @foreach($data['checklist_recepcao'] as $label => $value)
                <tr>
                    <td><strong>{{ $label }}</strong></td>
                    <td>{{ $value }}%</td>
                </tr>
            @endforeach
        </table>

        {{-- QUEBRA DE PÁGINA MANUAL --}}
        <div class="page-break"></div>

        <h2>Checklist — Chaves de Serviço Diário — Estoque Fixo (14 unidades)</h2>

        @php
            $itens = [
                1 => 'Chave Mestra Elevador',
                2 => 'Molho de Chave Mestra de Todos os Aptos',
                3 => 'Cartão TAG - Sala de Eventos Ibrain',
                4 => 'Chave da Porta de Vidro da Recepção',
                5 => 'Chave da Lixeira (2)',
                6 => 'Chave Porta Sauna',
                7 => 'Chave Porta Manutenção',
                8 => 'Chave Cadeado Bike Roxa',
                9 => 'Chave Cadeado Bike Vermelha',
                10 => 'Chave HUB 3° Andar',
                11 => 'Chave Porta Automática Recepção Entrada',
                12 => 'Chave (Vareta) Abertura do P2',
                13 => 'Chave Cartão Magnético Rouparia 3° Andar',
                14 => 'Chave da máquina do café da manhã',
            ];
        @endphp

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            <thead>
                <tr>
                    <th>Item / Descrição</th> {{-- Alterado de ID para Descrição --}}
                    <th>Status</th>
                    <th>Com quem</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['chaves_servico'] as $id => $item)
                    <tr>
                        <td>
                            {{-- Busca o nome no array $itens usando o $id. Se não existir, mostra o ID --}}
                            {{ $itens[$id] ?? "Item ID: $id" }}
                        </td>
                        <td>{{ $item['status'] }}</td>
                        <td>{{ $item['pessoa'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table> 

        {{--  
        <h2>Checklist — Controles de TV Box (Estoque fixo: 7 unidades)</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">            
            <tr>
                <td><strong>Apto 103</strong></td>
                <td>{{ $data['tvbox_103'] ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Apto 201</strong></td>
                <td>{{ $data['tvbox_201'] ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Apto 203</strong></td>
                <td>{{ $data['tvbox_203'] ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Apto 204</strong></td>
                <td>{{ $data['tvbox_204'] ?? '-' }}</td>
            </tr>            
        </table>
        --}}        

        <h2>Checklist — Secador de Cabelo (Estoque Fixo: 5 unidades)</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th align="left">Equipamento</th>
                    <th align="left">Status / Localização</th>
                </tr>
            </thead>
            <tbody>
                {{-- Percorremos o array de secadores --}}
                @foreach($data['secadores'] as $secador)
                    <tr>
                        <td>Secador Nº {{ $secador['numero'] }}</td>
                        <td>
                            @if($secador['status'] === 'gaveta')
                                <span style="color: green;">✔ Está na gaveta</span>
                            @else
                                <span style="color: #d9534f;">
                                    ✖ Emprestado — Apto: <strong>{{ $secador['apto'] ?? 'N/A' }}</strong>
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- QUEBRA DE PÁGINA MANUAL --}}
        <div class="page-break"></div>

        <h2>Checklist — Rádios Comunicadores de Serviços (7 unidades)</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th align="left">Rádio</th>
                    <th align="left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['radios'] as $radio)
                    <tr>
                        <td>Rádio Nº {{ $radio['numero'] }}</td>
                        <td>
                            @if($radio['status'] === 'base')
                                <span style="color: green;">✔ Está na base</span>
                            @elseif($radio['status'] === 'funcionario')
                                <span style="color: #d9534f;">
                                    ✖ Com funcionário — {{ $radio['funcionario'] ?? 'N/A' }}
                                </span>
                            @else
                                <span>{{ $radio['status'] ?? '-' }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <h2>Checklist — Celulares de Serviço (8 unidades)</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th align="left">Celular</th>
                    <th align="left">Bateria</th>
                    <th align="left">Usuário</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['celulares'] as $celular)
                    <tr>
                        <td>Celular Nº {{ $celular['numero'] }}</td>
                        <td>{{ $celular['bateria'] ?? '-' }}%</td>
                        <td>
                            @if(!empty($celular['funcionario']) && $celular['funcionario'] !== '-')
                                {{ $celular['funcionario'] }}
                            @else
                                <span style="color: green;">✔ Livre</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- QUEBRA DE PÁGINA MANUAL --}}
        <div class="page-break"></div>

        <h2>Checklist — Gavetas de Jogos e Controles do 3° e Ar Cond.</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th align="left">Gaveta</th>
                    <th align="left">Status / Apto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['gavetas'] as $gaveta)
                    <tr>
                        <td>Gaveta Nº {{ $gaveta['numero'] }}</td>
                        <td>
                            @if($gaveta['status'] === 'gaveta')
                                <span style="color: green;">✔ Está na gaveta</span>
                            @else
                                <span style="color: #d9534f;">
                                    ✖ Emprestada — Apto: <strong>{{ $gaveta['apto_emprestado'] ?? 'N/A' }}</strong>
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <h2>Checklist — Dados do Turno</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            @foreach($data['turno'] as $label => $value)
                <tr>
                    <td><strong>{{ $label }}</strong></td>
                    <td>{{ $value }}</td>
                </tr>
            @endforeach
        </table>

        <h2>Checklist — Chaves Mecânicas dos Apartamentos</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th align="left">Chave Mecânica</th>
                    <th align="left">Status / Pessoa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['chaves_mecanicas'] as $chave)
                    <tr>
                        <td>Chave Nº {{ $chave['numero'] }}</td>
                        <td>
                            @if($chave['status'] === 'recepcao')
                                <span style="color: green;">✔ Está na recepção</span>
                            @elseif($chave['status'] === 'pessoa')
                                <span style="color: #d9534f;">
                                    ✖ Com: <strong>{{ $chave['pessoa'] ?? 'N/A' }}</strong>
                                </span>
                            @else
                                <span>{{ $chave['status'] ?? '-' }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if ($ocorrencia->type === 'passagem-de-turno-cavalo')
        <h2>Checklist — Dados do Turno</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            @foreach($data['turno'] as $label => $value)
                <tr>
                    <td><strong>{{ $label }}</strong></td>
                    <td>{{ $value }}</td>
                </tr>
            @endforeach
                <tr>
                    <td><strong>As luzes?</strong></td>
                    <td>
                        @if($data['luzes'] === 'ligada')
                            <span style="color: green;">✔ Ligada</span>
                        @else
                            <span style="color: #d9534f;">✖ Desligada</span>
                        @endif
                    </td>
                </tr>
        </table>

        <h2>Checklist — Secador de Cabelo (Estoque Fixo: 2 unidades)</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th align="left">Equipamento</th>
                    <th align="left">Status / Localização</th>
                </tr>
            </thead>
            <tbody>
                {{-- Percorremos o array de secadores --}}
                @foreach($data['secadores'] as $secador)
                    @if ($secador['numero'] <= 2)
                        <tr>
                            <td>Secador Nº {{ $secador['numero'] }}</td>
                            <td>
                                @if($secador['status'] === 'gaveta')
                                    <span style="color: green;">✔ Está na gaveta</span>
                                @else
                                    <span style="color: #d9534f;">
                                        ✖ Emprestado — Apto: <strong>{{ $secador['apto'] ?? 'N/A' }}</strong>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endif                    
                @endforeach
            </tbody>
        </table>

        {{-- QUEBRA DE PÁGINA MANUAL --}}
        <div class="page-break"></div>

        <h2>Checklist — Chaves Extras (Estoque Fixo: 20 unidades)</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th align="left">Disponível</th>
                    <th align="left">Status / Apto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['chaves_cavalo'] as $gaveta)
                    <tr>
                        <td>Chave Nº {{ $gaveta['numero'] }}</td>
                        <td>
                            @if($gaveta['status'] === 'disponivel')
                                <span style="color: green;">✔ Está disponível</span>
                            @else
                                <span style="color: #d9534f;">
                                    ✖ Emprestada — Apto: <strong>{{ $gaveta['chave_apto'] ?? 'N/A' }}</strong>
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- QUEBRA DE PÁGINA MANUAL --}}
        <div class="page-break"></div>

        <h2>Checklist — Aquecedores / Gás / Lixeira</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            @foreach($data['checklist_aquecedores_gas_lixeira'] as $label => $value)
                <tr>
                    <td><strong>{{ $label }}</strong></td>
                    <td>{{ $value }}</td>
                </tr>
            @endforeach
        </table>

        <h2>Checklist — Piscina / Ofurô</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            @foreach($data['checklist_piscina_ofuro'] as $label => $value)
                <tr>
                    <td width="60%"><strong>{{ $label }}</strong></td>
                    <td width="40%">{{ ucfirst($value) }}</td>
                </tr>
            @endforeach
        </table>        

        <h2>Checklist — Chaves de Serviço Diário — Estoque Fixo (15 unidades)</h2>

        @php
            $itens = [
                1 => 'Rouparia',
                2 => 'Depósito de Água',
                3 => 'Depósito Camareiras',
                4 => 'Bicicletário',
                5 => 'Chave 1 Portão Praia',
                6 => 'Chave 2 Portão Praia',
                7 => 'Chave Mestra',
                8 => 'Chave Manutenção/ Eletrônicos',
                9 => 'Aquecedor',
                10 => 'Chave Copa Mamãe',
                11 => 'Chave Ala Mar Armário',
                12 => 'Sala da Gerência',
                13 => 'Sala Depósito (Antiga Gerência)',
                14 => 'Chave Portão de Entrega',
                15 => 'Chave porta da manutenção',
            ];
        @endphp

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            <thead>
                <tr>
                    <th>Item / Descrição</th> {{-- Alterado de ID para Descrição --}}
                    <th>Status</th>
                    <th>Com quem</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['chaves_servico'] as $id => $item)
                    <tr>
                        <td>
                            {{-- Busca o nome no array $itens usando o $id. Se não existir, mostra o ID --}}
                            {{ $itens[$id] ?? "Item ID: $id" }}
                        </td>
                        <td>{{ $item['status'] }}</td>
                        <td>{{ $item['pessoa'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Checklist — Cartões</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            @foreach($data['checklist_cartoes'] as $label => $value)
                <tr>
                    <td width="60%"><strong>{{ $label }}</strong></td>
                    <td width="40%">{{ ucfirst($value) }}</td>
                </tr>
            @endforeach
        </table>

        <h2>Checklist — Cartões Camareiras (5 unidades)</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th align="left">Cartão</th>
                    <th align="left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['cartoes_camareira'] as $cartao)
                    @if ($cartao['numero'] <= 5)
                        <tr>
                            <td>Cartão Nº {{ $cartao['numero'] }}</td>
                            <td>
                                @if($cartao['status'] === 'disponivel')
                                    <span style="color: green;">✔ Está disponível</span>
                                @elseif($cartao['status'] === 'funcionario')
                                    <span style="color: #d9534f;">
                                        ✖ Com funcionário — {{ $cartao['funcionario'] ?? 'N/A' }}
                                    </span>
                                @else
                                    <span>{{ $cartao['status'] ?? '-' }}</span>
                                @endif
                            </td>
                        </tr>
                    @endif                    
                @endforeach
            </tbody>
        </table>

        <h2>Checklist — Aberto - Fechado</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            @foreach($data['checklist_aberto_fechado'] as $label => $value)
                <tr>
                    <td width="60%"><strong>{{ $label }}</strong></td>
                    <td width="40%">
                        @if ($value === 'aberto')
                            <span style="color: green;">✔ Aberto</span>
                        @elseif ($value === 'fechado')
                            <span style="color: #d9534f;">✖ Fechado</span>
                        @else
                            {{ $value }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>

        <h2>Checklist — Celulares de Serviço (2 unidades)</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th align="left">Celular</th>
                    <th align="left">Bateria</th>
                    <th align="left">Está com o funcionário?</th>
                </tr>
            </thead>
            <tbody>                
                <tr>
                    <td>Celular 1 Recepção</td>
                    <td>{{ $data['checklist_celulares']['bateria1'] ?? '-' }}%</td>
                    <td>
                        @if(!empty($data['checklist_celulares']['funcionario1']) && $data['checklist_celulares']['funcionario1'] !== '-')
                            {{ $data['checklist_celulares']['funcionario1'] }}
                        @else
                            <span style="color: green;">✔ Livre</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Celular 2 Recepção</td>
                    <td>{{ $data['checklist_celulares']['bateria2'] ?? '-' }}%</td>
                    <td>
                        @if(!empty($data['checklist_celulares']['funcionario2']) && $data['checklist_celulares']['funcionario2'] !== '-')
                            {{ $data['checklist_celulares']['funcionario2'] }}
                        @else
                            <span style="color: green;">✔ Livre</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- QUEBRA DE PÁGINA MANUAL --}}
        <div class="page-break"></div>

        <h2>Checklist — Recepção (Máquinas de Cartão)</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            @foreach($data['checklist_recepcao'] as $label => $value)
                <tr>
                    <td><strong>{{ $label }}</strong></td>
                    <td>{{ $value }}%</td>
                </tr>
            @endforeach
        </table>

        <h2>Checklist — Rádios Comunicadores de Serviços (7 unidades)</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th align="left">Rádio</th>
                    <th align="left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['radios'] as $radio)
                    <tr>
                        <td>Rádio Nº {{ $radio['numero'] }}</td>
                        <td>
                            @if($radio['status'] === 'base')
                                <span style="color: green;">✔ Está na base</span>
                            @elseif($radio['status'] === 'funcionario')
                                <span style="color: #d9534f;">
                                    ✖ Com funcionário — {{ $radio['funcionario'] ?? 'N/A' }}
                                </span>
                            @else
                                <span>{{ $radio['status'] ?? '-' }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Checklist — Geladeira Recepção</h2>

        {!! nl2br($data['geladeira_recepcao']) ?? '-' !!}
    @endif

    @if ($ocorrencia->type === 'varreduras-fichas-sistemas')

        <p style="margin-top: 10px;">
            <strong>Horário da conferência:</strong>
            <span style="border-bottom: 1px solid #000; padding: 0 10px;">
                {{ $data['horario'] ?? '-' }}
            </span>
        </p>

        {{-- CONFERÊNCIA DA FICHA --}}
        @if (!empty($data['conferencia_ficha']))
            <div class="section">
                <div class="section-title">1. Conferência da Ficha Física e Sistema</div>

                <div class="checklist">
                    @foreach ($data['conferencia_ficha'] as $key => $item)
                        @php
                            // Extrai o status e o motivo da nova estrutura de dados
                            $status = $item['status'] ?? null;
                            $motivo = $item['motivo'] ?? '';
                            $is_ok = $status === 'sim';
                            $status_class = $is_ok ? 'ok' : 'no';
                            $status_icon = $is_ok ? '✔' : '✖';
                        @endphp
                        <div class="item">
                            <span>{{ $data['labels'][$key] ?? $key }}</span>
                            <span class="status {{ $status_class }}">
                                {{ $status_icon }}
                            </span>
                            {{-- Exibe o motivo se o status não for 'sim' e o motivo não estiver vazio --}}
                            @if (!$is_ok && !empty($motivo))
                                <span class="motivo" style="font-size: 0.9em; color: #555; margin-left: 10px;">
                                    (Motivo: {{ $motivo }})
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- CONFERÊNCIA ADICIONAL --}}
        @if (!empty($data['conferencia_adicional']))
            <div class="section">
                <div class="section-title">2. Conferência Adicional</div>

                <div class="checklist">
                    @foreach ($data['conferencia_adicional'] as $key => $item)
                        @php
                            // Extrai o status e o motivo da nova estrutura de dados
                            $status = $item['status'] ?? null;
                            $motivo = $item['motivo'] ?? '';
                            $is_ok = $status === 'sim';
                            $status_class = $is_ok ? 'ok' : 'no';
                            $status_icon = $is_ok ? '✔' : '✖';
                        @endphp
                        <div class="item">
                            <span>{{ $labelsAdicional[$key] ?? $key }}</span>
                            <span class="status {{ $status_class }}">
                                {{ $status_icon }}
                            </span>
                            {{-- Exibe o motivo se o status não for 'sim' e o motivo não estiver vazio --}}
                            @if (!$is_ok && !empty($motivo))
                                <span class="motivo" style="font-size: 0.9em; color: #555; margin-left: 10px;">
                                    (Motivo: {{ $motivo }})
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- OBSERVAÇÕES --}}
        <div class="section">
            <div class="section-title">Observações do Turno</div>
            <div class="obs">
                {{ $data['observacoes_turno'] ?? '—' }}
            </div>
        </div>
    @endif

    <footer>
        DOCUMENTO INTERNO — PROIBIDA A DIVULGAÇÃO OU REPRODUÇÃO SEM AUTORIZAÇÃO
    </footer>
</body>
</html>