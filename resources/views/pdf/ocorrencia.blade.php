<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Ocorrência #{{ $ocorrencia->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
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
            border-bottom: 1px solid #000;
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

    <h1 style="text-align: center;">Ocorrências</h1>

    <p>
        <strong>Tipo de ocorrência:</strong> {{ $ocorrencia->type }}   
        <span style="margin: 0 10px 0 10px;">-</span>     
        <strong>Colaborador:</strong> {{ $ocorrencia->user->name }}
        <span style="margin: 0 10px 0 10px;">-</span> 
        <strong>Data:</strong> {{ $ocorrencia->created_at->format('d/m/Y H:i') }}
    </p>

    <p><strong>Título:</strong> {{ $ocorrencia->title }}</p>    
        
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

    <footer>
        DOCUMENTO INTERNO — PROIBIDA A DIVULGAÇÃO OU REPRODUÇÃO SEM AUTORIZAÇÃO
    </footer>
</body>
</html>