@extends('layouts.app')

@section('title', 'Detalle del Establecimiento')

@section('content')

{{-- PERMISO --}}
@if(!canAccess('establecimientos', 'view'))
    @php(abort(403, 'No tienes permisos para ver establecimientos.'))
@endif

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Detalle del Establecimiento</h1>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- EDITAR --}}
        @if(canAccess('establecimientos', 'edit'))
            <a href="{{ route('establecimientos.edit', $establecimiento->id) }}" 
               class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i> Editar
            </a>
        @endif

        {{-- VOLVER --}}
        <a href="{{ route('establecimientos.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>
    </div>
</div>

<div class="form-section">

    <h5 class="form-section-title">Información General</h5>

    <div class="detail-item">
        <div class="detail-label">RBD:</div>
        <div class="detail-value">{{ $establecimiento->RBD }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Nombre:</div>
        <div class="detail-value">{{ $establecimiento->nombre }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Dirección:</div>
        <div class="detail-value">{{ $establecimiento->direccion }}</div>
    </div>

</div>

<div class="form-section">

    <h5 class="form-section-title">Ubicación y Dependencia</h5>

    <div class="detail-item">
        <div class="detail-label">Dependencia:</div>
        <div class="detail-value">{{ $establecimiento->dependencia->nombre ?? '—' }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Región:</div>
        <div class="detail-value">{{ $establecimiento->region->nombre ?? '—' }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Provincia:</div>
        <div class="detail-value">{{ $establecimiento->provincia->nombre ?? '—' }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Comuna:</div>
        <div class="detail-value">{{ $establecimiento->comuna->nombre ?? '—' }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Estado:</div>
        <div class="detail-value">
            @if($establecimiento->activo)
                <span class="badge bg-success">Activo</span>
            @else
                <span class="badge bg-danger">Deshabilitado</span>
            @endif
        </div>
    </div>

</div>

@endsection
