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

        <h2>Checklist — Chaves de Serviço Diário — Estoque Fixo (15 unidades)</h2>

        <table width="100%" cellspacing="0" cellpadding="6" border="1">
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Com quem</th>
            </tr>

            @foreach($data['chaves_servico'] as $id => $item)
                <tr>
                    <td>{{ $id }}</td>
                    <td>{{ $item['status'] }}</td>
                    <td>{{ $item['pessoa'] }}</td>
                </tr>
            @endforeach
        </table> 

        <h2>Checklist — Controles de TV Box (Estoque fixo: 7 unidades)</h2>
        <h2>Checklist — Secador de Cabelo (Estoque Fixo: 5 unidades)</h2>
        <h2>Checklist — Rádios Comunicadores de Serviços (7 unidades)</h2>
        <h2>Checklist — Celulares de Serviço (8 unidades)</h2>
        <h2>Checklist — Gavetas de Jogos e Controles do 3° e Ar Cond.</h2>
        
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