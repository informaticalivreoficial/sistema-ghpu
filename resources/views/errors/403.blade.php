

@section('content')
    <div class="container text-center py-5">
        <h1 class="display-4 text-danger">🚫 Acesso negado</h1>

        <p class="lead mt-3">
            Você não tem permissão para acessar esta página.
        </p>

        <p class="text-muted">
            Se você acredita que isso é um erro, entre em contato com o administrador.
        </p>

        <a href="{{ route('admin') }}" class="btn btn-primary mt-4">
            Voltar ao Dashboard
        </a>
    </div>
@endsection