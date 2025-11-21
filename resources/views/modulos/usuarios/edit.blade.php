@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Usuario</h1>
</div>

<form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- DATOS ACCESO --}}
    <div class="form-section">
        <h5 class="form-section-title">Datos de Acceso</h5>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" 
                       value="{{ $usuario->email }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Nueva contraseña (opcional)</label>
                <input type="password" name="password" class="form-control">
            </div>
        </div>
    </div>

    {{-- ASIGNACIONES --}}
    <div class="form-section">
        <h5 class="form-section-title">Asignación</h5>

        <div class="row g-3">

            {{-- ROL --}}
            <div class="col-md-4">
                <label class="form-label">Rol *</label>
                <select name="rol_id" class="form-select" required>
                    @foreach($roles as $r)
                        <option value="{{ $r->id }}" {{ $usuario->rol_id == $r->id ? 'selected' : '' }}>
                            {{ $r->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- FUNCIONARIO --}}
            <div class="col-md-4">
                <label class="form-label">Funcionario</label>

                @if($usuario->funcionario)
                    <input type="text" class="form-control" 
                           value="{{ $usuario->funcionario->nombre_completo }} ({{ $usuario->funcionario->cargo->nombre }})"
                           readonly>
                    <small class="text-muted">Este usuario está vinculado a un funcionario y no puede cambiarse.</small>

                    <input type="hidden" name="funcionario_id" value="{{ $usuario->funcionario_id }}">

                @else
                    <span class="badge bg-secondary">Sin funcionario</span>
                @endif
            </div>

            {{-- ESTABLECIMIENTO --}}
            <div class="col-md-4">
                <label class="form-label">Establecimiento</label>

                @if($usuario->funcionario)
                    <input type="text" class="form-control"
                           value="{{ $usuario->establecimiento->nombre ?? '—' }}" readonly>
                    <small class="text-muted">Asignado automáticamente desde el funcionario.</small>

                    <input type="hidden" name="establecimiento_id" 
                           value="{{ $usuario->establecimiento_id }}">

                @else
                    <select name="establecimiento_id" class="form-select">
                        <option value="">— No asignado —</option>
                        @foreach($establecimientos as $e)
                            <option value="{{ $e->id }}" {{ $usuario->establecimiento_id == $e->id ? 'selected' : '' }}>
                                {{ $e->nombre }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

        </div>
    </div>

    {{-- DATOS PERSONALES --}}
    <div class="form-section">
        <h5 class="form-section-title">Datos Personales</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre"
                       value="{{ $usuario->nombre ?? '' }}"
                       class="form-control"
                       {{ $usuario->funcionario ? 'readonly' : '' }}>
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Paterno</label>
                <input type="text" name="apellido_paterno"
                       value="{{ $usuario->apellido_paterno ?? '' }}"
                       class="form-control"
                       {{ $usuario->funcionario ? 'readonly' : '' }}>
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Materno</label>
                <input type="text" name="apellido_materno"
                       value="{{ $usuario->apellido_materno ?? '' }}"
                       class="form-control"
                       {{ $usuario->funcionario ? 'readonly' : '' }}>
            </div>

        </div>

        @if($usuario->funcionario)
            <small class="text-muted">
                Estos datos provienen del funcionario y no son editables desde aquí.
            </small>
        @endif

    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endsection
