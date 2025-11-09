<nav class="topbar">

    <div class="d-flex align-items-center">
        <button class="topbar-toggle">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <div class="topbar-user d-flex align-items-center gap-4">

        {{-- SELECTOR DE ESTABLECIMIENTO --}}
        @include('partials.selector-establecimiento')


        {{-- NOTIFICACIONES --}}
        <div class="notification-badge">
            <i class="bi bi-bell" style="font-size: 1.25rem; color: #6c757d;"></i>
            <span class="badge bg-danger">3</span>
        </div>

        {{-- MENÚ DE USUARIO --}}
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
               data-bs-toggle="dropdown">
                <div class="user-avatar">JD</div>
                <div class="ms-2 d-none d-md-block">
                    <div style="font-weight: 600; color: #212529;">Juan Díaz</div>
                    <div style="font-size: 0.875rem; color: #6c757d;">Administrador</div>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Configuración</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>

</nav>
