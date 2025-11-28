@extends('layouts.app')

@section('title', 'Usuarios del Sistema')

@section('content')

{{-- 🔒 PERMISO --}}
@if(!canAccess('usuarios', 'view'))
    @php(abort(403, 'No tienes permisos para ver usuarios.'))
@endif

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Usuarios del Sistema</h1>
        <p class="text-muted">Gestión completa de cuentas de acceso</p>
    </div>

    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-2"></i> Nuevo Usuario
    </a>
</div>

<div class="card card-table mt-3">

    {{-- FILTROS --}}
    <div class="card-header">
        <form method="GET" action="{{ route('usuarios.index') }}">

            <div class="row g-3 align-items-end">

                {{-- Buscar --}}
                <div class="col-md-4">
                    <input type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Buscar por nombre o email..."
                        value="{{ request('buscar') }}">
                </div>

                {{-- Rol --}}
                <div class="col-md-2">
                    <select name="rol_id" class="form-select">
                        <option value="">— Rol —</option>

                        @foreach($roles as $r)
                            <option value="{{ $r->id }}"
                                {{ request('rol_id') == $r->id ? 'selected' : '' }}>
                                {{ $r->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Establecimiento --}}
                <div class="col-md-3">
                    <select name="establecimiento_id" class="form-select">
                        <option value="">— Establecimiento —</option>

                        @foreach($establecimientos as $e)
                            <option value="{{ $e->id }}"
                                {{ request('establecimiento_id') == $e->id ? 'selected' : '' }}>
                                {{ $e->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Estado --}}
                <div class="col-md-2">
                    <select name="estado" class="form-select">
                        <option value="">— Estado —</option>
                        <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                {{-- Botón Filtrar --}}
                <div class="col-md-1 text-end">
                    <button class="btn btn-secondary" style="min-width: 110px;">
                        <i class="bi bi-funnel me-1"></i> Filtrar
                    </button>
                </div>

            </div>

        </form>
    </div>

    {{-- TABLA --}}
    <div class="card-body">

        @if($usuarios->count() == 0)
            <div class="alert alert-info">
                No se encontraron usuarios con los filtros aplicados.
            </div>
        @else

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Email</th>
                        <th>Nombre</th>
                        <th>Rol</th>
                        <th>Establecimiento</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($usuarios as $u)
                    <tr>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->nombre_completo ?: '—' }}</td>
                        <td>{{ $u->rol->nombre }}</td>
                        <td>{{ $u->establecimiento->nombre ?? '—' }}</td>

                        <td>
                            @if($u->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>

                        <td class="text-end">

                            {{-- EDITAR --}}
                            <a href="{{ route('usuarios.edit', $u->id) }}"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>

                            {{-- ACTIVAR / DESACTIVAR --}}
                            @if($u->activo)
                                <form action="{{ route('usuarios.disable', $u->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-warning" title="Deshabilitar">
                                        <i class="bi bi-slash-circle"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('usuarios.enable', $u->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-success" title="Habilitar">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $usuarios->links() }}

        @endif
    </div>
</div>

@endsection
