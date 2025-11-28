@extends('layouts.app')

@section('title', 'Detalle de Auditoría')

@section('content')

{{-- 🔒 PERMISO --}}
@if(!canAccess('auditoria', 'view'))
    @php(abort(403, 'No tienes permiso para ver auditorías.'))
@endif

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Registro de Auditoría #{{ $registro->id }}</h1>
        <p class="text-muted">Información detallada del evento registrado</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('auditoria.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>
    </div>
</div>


{{-- =========================================================
    INFORMACIÓN GENERAL
========================================================= --}}
<div class="form-section">
    <h5 class="form-section-title">Información General</h5>

    <div class="detail-item">
        <div class="detail-label">ID del Registro:</div>
        <div class="detail-value">{{ $registro->id }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Fecha y Hora:</div>
        <div class="detail-value">
            {{ $registro->created_at->format('d/m/Y H:i') }}
        </div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Módulo:</div>
        <div class="detail-value">{{ $registro->modulo }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Acción:</div>
        <div class="detail-value">
            @if($registro->accion == 'create')
                <span class="badge bg-success">Creación</span>
            @elseif($registro->accion == 'update')
                <span class="badge bg-warning text-dark">Actualización</span>
            @elseif($registro->accion == 'delete')
                <span class="badge bg-danger">Eliminación</span>
            @elseif($registro->accion == 'login')
                <span class="badge bg-info text-dark">Login</span>
            @endif
        </div>
    </div>
</div>


{{-- =========================================================
    USUARIO RESPONSABLE
========================================================= --}}
<div class="form-section">
    <h5 class="form-section-title">Usuario Responsable</h5>

    <div class="detail-item">
        <div class="detail-label">Usuario:</div>
        <div class="detail-value">
            {{ $registro->usuario->nombre_completo ?? $registro->usuario->email }}
        </div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Email:</div>
        <div class="detail-value">{{ $registro->usuario->email }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Rol:</div>
        <div class="detail-value">{{ $registro->usuario->rol->nombre }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Establecimiento Asociado:</div>
        <div class="detail-value">
            {{ $registro->establecimiento->nombre ?? 'No Aplica' }}
        </div>
    </div>
</div>


{{-- =========================================================
    DETALLE DE LA ACCIÓN
========================================================= --}}
<div class="form-section">
    <h5 class="form-section-title">Detalle de la Acción</h5>

    <div class="detail-item">
        <div class="detail-label">Descripción:</div>
        <div class="detail-value">
            {{ $registro->detalle ?: '—' }}
        </div>
    </div>
</div>


@endsection
