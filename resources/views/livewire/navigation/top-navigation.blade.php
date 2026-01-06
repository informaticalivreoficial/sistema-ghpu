<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" href="{{route('web.home')}}" title="Ver Site" target="_blank"><i class="fas fa-desktop"></i></a>
        </li>


        <!-- Messages Dropdown Menu -->
        <livewire:components.navbar-messages />

        <!-- Notifications Dropdown Menu -->
        <livewire:components.notifications-dropdown />

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                <a href="{{ route('users.edit', [ 'userId' => auth()->id() ]) }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Perfil
                </a>
                @role(['super-admin', 'admin'])
                    <a class="dropdown-item" style="cursor: pointer;">
                        <i class="fas fa-file-invoice mr-2"></i> Financeiro
                    </a>
                @endrole
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <li class="nav-item dropdown">
            <a href="javascript:void(0)" onclick="Livewire.dispatch('open-support-modal')" title="Suporte" class="nav-link">
                <i class="fas fa-life-ring" style="color: rgb(223, 87, 87);"></i>
            </a>
        </li>
        
        @auth
            <livewire:auth.button-logout />
        @endauth
    </ul>
</nav>
