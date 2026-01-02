<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }} | {{ env('APP_NAME') }}</title>

    <link rel="icon" href="{{ asset('theme/images/chave.png')}}" type="image/x-icon">

    {{-- Theme style --}}
    <link rel="stylesheet" href="{{ asset('theme/dist/css/adminlte.min.css') }}">

    {{-- General Styles --}}
    <link rel="stylesheet" href="{{ asset('theme/dist/css/styles.css') }}">

    {{-- Toastr --}}
    <link rel="stylesheet" href="{{ asset('theme/plugins/toastr/toastr.min.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="login-body bg-cover bg-center bg-fixed" style="background-image: url({{url(asset('theme/images/login-bg.jpg'))}});">
    {{ $slot }}

    {{-- Componente Toastr Global --}}
    <livewire:components.toastr-notification />

    {{-- Toastr --}}
    <script src="{{ asset('theme/plugins/toastr/toastr.min.js') }}"></script>
</body>

</html>