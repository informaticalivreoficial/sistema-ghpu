<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ficha do Colaborador</title>
    
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
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

        .profile {
            text-align: center;
            margin-bottom: 25px;
        }

        .profile img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #555;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        td.label {
            width: 30%;
            font-weight: bold;
            color: #555;
            background: #f8f9fa;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: right;
            color: #777;
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
    
    <h2 style="text-align: center">Ficha do Colaborador</h2>
    

    <div class="profile">
        @if ($user->avatar)
            <img src="{{ public_path('storage/' . $user->avatar) }}">
        @endif
    </div>

    <div class="card">
        <table>
            <tr>
                <td class="label">Nome</td>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <td class="label">E-mail</td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td class="label">CPF</td>
                <td>{{ $user->cpf }}</td>
            </tr>
            <tr style="color: #555;">
                <td class="label">RG</td>
                <td>{{ $user->rg }} <span style="margin: 0 10px 0 10px;">-</span> <b>Orgão Expedidor:</b> {{ $user->rg_expedition }}</td>
            </tr>
            <tr>
                <td class="label">Naturalidade</td>
                <td>{{ strtoupper($user->naturalness) }}</td>
            </tr>
            <tr>
                <td class="label">Gênero</td>
                <td>{{ ucfirst($user->gender) }}</td>
            </tr>
            <tr>
                <td class="label">Estado civil</td>
                <td>{{ ucfirst($user->civil_status) }}</td>
            </tr>
            <tr>
                <td class="label">Data de Nascimento</td>
                <td>{{ $user->birthday }}</td>
            </tr>
            <tr>
                <td class="label">Telefone</td>
                <td>{{ $user->phone }}</td>
            </tr>
            <tr>
                <td class="label">Telefone móvel</td>
                <td>{{ $user->cell_phone }}</td>
            </tr>
            <tr>
                <td class="label">WhatsApp</td>
                <td>{{ $user->whatsapp }}</td>
            </tr>
            <tr>
                <td class="label">Endereço</td>
                <td>{{ $user->street }}, {{ $user->number }}, 
                    {{ $user->neighborhood }} - {{ $user->city }}/{{ $user->state }} - {{ $user->zipcode }}</td>
            </tr>
            <tr>
                <td class="label">Complemento</td>
                <td>{{ $user->complement }}</td>
            </tr>
            <tr>
                <td class="label">Empresa</td>
                <td>{{ optional($user->company)->alias_name }}</td>
            </tr>
            <tr>
                <td class="label">Cargo</td>
                <td>{{ $user->cargo }}</td>
            </tr>
            @if ($user->information)
                <tr style="color: #555;">                
                    <td colspan="2">
                        <p><b>Informações Adicionais:</b></p>
                        {!! nl2br(e($user->information)) !!}
                    </td>
                </tr>
            @endif            
        </table>
    </div>

    <div class="footer">
        Documento gerado em {{ now()->format('d/m/Y H:i') }}
    </div>

    <footer>
        DOCUMENTO INTERNO — PROIBIDA A DIVULGAÇÃO OU REPRODUÇÃO SEM AUTORIZAÇÃO
    </footer>

</body>
</html>
