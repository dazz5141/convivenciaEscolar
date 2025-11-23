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
        @php
            $user = auth()->user();

            $notificaciones = $user->notificaciones()
                ->whereNull('leida')
                ->latest()
                ->take(5)
                ->get();

            $totalNoLeidas = $user->notificaciones()
                ->whereNull('leida')
                ->count();

            // GENERAR TÍTULO PROFESIONAL SEGÚN TIPO
            function tituloNotificacion($n) {
                return match($n->tipo) {
                    'incidente'            => 'Nuevo incidente',
                    'accidente'            => 'Accidente escolar',
                    'citacion'             => 'Nueva citación',
                    'asistencia'           => 'Registro de asistencia',
                    'medida_restaurativa'  => 'Medida restaurativa asignada',
                    'derivacion'           => 'Nueva derivación',
                    default                => 'Notificación'
                };
            }
        @endphp

        <div class="dropdown">
            <a href="#" class="position-relative" data-bs-toggle="dropdown">
                <i class="bi bi-bell" style="font-size: 1.35rem; color: #6c757d;"></i>

                @if($totalNoLeidas > 0)
                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                        {{ $totalNoLeidas }}
                    </span>
                @endif
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow" style="width: 350px;">
                <li class="dropdown-header fw-bold">
                    Notificaciones
                </li>

                @forelse($notificaciones as $n)
                    <li>
                        <div class="dropdown-item d-flex justify-content-between align-items-start">

                            <div class="me-2">
                                <div class="fw-semibold">
                                    {{ tituloNotificacion($n) }}
                                </div>

                                <div class="small text-muted">
                                    {{ $n->mensaje }}
                                </div>
                            </div>

                            <form action="{{ route('notificaciones.marcar-leida', $n->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-sm btn-light" title="Marcar como leída">
                                    <i class="bi bi-check2"></i>
                                </button>
                            </form>

                        </div>
                    </li>
                @empty
                    <li>
                        <div class="dropdown-item text-muted text-center">
                            Sin notificaciones
                        </div>
                    </li>
                @endforelse

                <li><hr class="dropdown-divider"></li>

                <li>
                    <a href="{{ route('notificaciones.index') }}" class="dropdown-item text-center fw-semibold">
                        Ver todas
                    </a>
                </li>
            </ul>
        </div>

        {{-- MENÚ DE USUARIO --}}
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
            data-bs-toggle="dropdown">

                {{-- Avatar con iniciales --}}
                @php
                    $usuario = Auth::user();
                    $iniciales = strtoupper(substr($usuario->nombre, 0, 1) . substr($usuario->apellido_paterno, 0, 1));
                @endphp

                <div class="user-avatar">{{ $iniciales }}</div>

                <div class="ms-2 d-none d-md-block">
                    {{-- Nombre dinámico --}}
                    <div style="font-weight: 600; color: #212529;">
                        {{ $usuario->nombre }} {{ $usuario->apellido_paterno }}
                    </div>

                    {{-- Rol dinámico --}}
                    <div style="font-size: 0.875rem; color: #6c757d;">
                        {{ $usuario->rol->nombre ?? 'Sin rol' }}
                    </div>
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
