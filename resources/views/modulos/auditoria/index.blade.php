@extends('layouts.app')

@section('title', 'Auditoría del Sistema')

@section('content')

{{-- 🔒 PERMISO --}}
@if(!canAccess('auditoria', 'view'))
    @php(abort(403, 'No tienes permiso para ver la auditoría.'))
@endif

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Auditoría del Sistema</h1>
        <p class="text-muted">Registros de acciones realizadas dentro del sistema</p>
    </div>
</div>

<div class="card card-table mt-3">

    {{-- ========== FILTROS ========== --}}
    <div class="card-header">
        <form method="GET" action="{{ route('auditoria.index') }}">

            {{-- ===================== FILA 1 ===================== --}}
            <div class="row g-3 align-items-end">

                {{-- Buscar texto --}}
                <div class="col-md-3">
                    <input type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Buscar por detalle..."
                        value="{{ request('buscar') }}">
                </div>

                {{-- Módulo --}}
                <div class="col-md-3">
                    <select name="modulo" class="form-select">
                        <option value="">— Módulo —</option>
                        @foreach($auditorias->pluck('modulo')->unique() as $mod)
                            <option value="{{ $mod }}" {{ request('modulo') == $mod ? 'selected' : '' }}>
                                {{ $mod }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Acción --}}
                <div class="col-md-3">
                    <select name="accion" class="form-select">
                        <option value="">— Acción —</option>

                        <option value="create" {{ request('accion') == 'create' ? 'selected' : '' }}>Creación</option>
                        <option value="update" {{ request('accion') == 'update' ? 'selected' : '' }}>Actualización</option>
                        <option value="delete" {{ request('accion') == 'delete' ? 'selected' : '' }}>Eliminación</option>

                        <option value="login" {{ request('accion') == 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('accion') == 'logout' ? 'selected' : '' }}>Logout</option>

                        <option value="disable" {{ request('accion') == 'disable' ? 'selected' : '' }}>Deshabilitación</option>
                        <option value="enable" {{ request('accion') == 'enable' ? 'selected' : '' }}>Habilitación</option>
                    </select>
                </div>

                {{-- Usuario --}}
                <div class="col-md-3">
                    <select name="usuario_id" class="form-select">
                        <option value="">— Usuario —</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->nombre_completo ?? $u->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            {{-- ===================== FILA 2 ===================== --}}
            <div class="row g-3 align-items-end mt-2">

                {{-- Establecimiento (solo admin general) --}}
                @if(Auth::user()->rol_id == 1)
                <div class="col-md-3">
                    <select name="establecimiento_id" class="form-select">
                        <option value="">— Establecimiento —</option>
                        @foreach($establecimientos as $e)
                            <option value="{{ $e->id }}" {{ request('establecimiento_id') == $e->id ? 'selected' : '' }}>
                                {{ $e->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Fecha desde --}}
                <div class="col-md-3">
                    <label class="form-label small text-muted">Desde</label>
                    <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
                </div>

                {{-- Fecha hasta --}}
                <div class="col-md-3">
                    <label class="form-label small text-muted">Hasta</label>
                    <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
                </div>

                {{-- Botón Filtrar --}}
                <div class="col-md-3 text-end">
                    <button class="btn btn-secondary w-100">
                        <i class="bi bi-funnel me-1"></i> Filtrar
                    </button>
                </div>

            </div>

        </form>
    </div>

    {{-- ========== TABLA ========== --}}
    <div class="card-body">

        @if($auditorias->count() == 0)
            <div class="alert alert-info">No se encontraron registros.</div>
        @else

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Establecimiento</th>
                        <th>Módulo</th>
                        <th>Acción</th>
                        <th>Detalle</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($auditorias as $a)
                    <tr>
                        <td>{{ $a->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $a->usuario->nombre_completo ?? $a->usuario->email }}</td>
                        <td>{{ $a->establecimiento?->nombre ?? '— Global —' }}</td>
                        <td>{{ $a->modulo }}</td>

                        <td>
                            @if($a->accion == 'create')
                                <span class="badge bg-success">Crear</span>

                            @elseif($a->accion == 'update')
                                <span class="badge bg-warning text-dark">Actualizar</span>

                            @elseif($a->accion == 'delete')
                                <span class="badge bg-danger">Eliminar</span>

                            @elseif($a->accion == 'login')
                                <span class="badge bg-info text-dark">Login</span>

                            @elseif($a->accion == 'logout')
                                <span class="badge bg-dark">Logout</span>

                            @elseif($a->accion == 'disable')
                                <span class="badge bg-secondary">Deshabilitar</span>

                            @elseif($a->accion == 'enable')
                                <span class="badge bg-primary">Habilitar</span>
                            @endif
                        </td>

                        <td>{{ Str::limit($a->detalle, 60) }}</td>

                        <td class="text-end">
                            <a href="{{ route('auditoria.show', $a->id) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        {{ $auditorias->links() }}

        @endif
    </div>
</div>

@endsection
