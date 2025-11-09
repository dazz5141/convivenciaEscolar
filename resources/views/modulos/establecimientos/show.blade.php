@extends('layouts.app')

@section('title', 'Detalle del Establecimiento')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detalle del Establecimiento</h1>
</div>

<div class="form-section">
    <h5 class="form-section-title">Información General</h5>

    <div class="row g-3">

        <div class="col-md-4">
            <label class="fw-bold">RBD</label>
            <p>{{ $establecimiento->RBD }}</p>
        </div>

        <div class="col-md-8">
            <label class="fw-bold">Nombre</label>
            <p>{{ $establecimiento->nombre }}</p>
        </div>

        <div class="col-12">
            <label class="fw-bold">Dirección</label>
            <p>{{ $establecimiento->direccion }}</p>
        </div>

    </div>

    <h5 class="form-section-title mt-4">Ubicación y Dependencia</h5>

    <div class="row g-3">

        <div class="col-md-3">
            <label class="fw-bold">Dependencia</label>
            <p>{{ $establecimiento->dependencia->nombre ?? '-' }}</p>
        </div>

        <div class="col-md-3">
            <label class="fw-bold">Región</label>
            <p>{{ $establecimiento->region->nombre ?? '-' }}</p>
        </div>

        <div class="col-md-3">
            <label class="fw-bold">Provincia</label>
            <p>{{ $establecimiento->provincia->nombre ?? '-' }}</p>
        </div>

        <div class="col-md-3">
            <label class="fw-bold">Comuna</label>
            <p>{{ $establecimiento->comuna->nombre ?? '-' }}</p>
        </div>

        <div class="col-12">
            <label class="fw-bold">Estado</label>
            <p>
                @if($establecimiento->activo)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-danger">Deshabilitado</span>
                @endif
            </p>
        </div>

    </div>
</div>

<div class="d-flex gap-2 flex-wrap mt-3">
    <a href="{{ route('establecimientos.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Volver
    </a>

    @if(auth()->user()->rol_id === 1)
        <a href="{{ route('establecimientos.edit', $establecimiento->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>
    @endif
</div>

@endsection
