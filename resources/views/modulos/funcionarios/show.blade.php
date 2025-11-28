@extends('layouts.app')

{{-- PERMISO --}}
@if(!canAccess('funcionarios', 'view'))
    @php(abort(403, 'No tienes permisos para ver funcionarios.'))
@endif

@section('title', 'Detalle del Funcionario')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Detalle del Funcionario</h1>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('funcionarios.edit', $funcionario->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>

        <a href="{{ route('funcionarios.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>
    </div>
</div>

<div class="form-section">
    <h5 class="form-section-title">Datos Personales</h5>

    <div class="detail-item">
        <div class="detail-label">RUN:</div>
        <div class="detail-value">{{ $funcionario->run }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Nombre:</div>
        <div class="detail-value">
            {{ $funcionario->nombre }} {{ $funcionario->apellido_paterno }} {{ $funcionario->apellido_materno }}
        </div>
    </div>
</div>

<div class="form-section">
    <h5 class="form-section-title">Información Laboral</h5>

    <div class="detail-item">
        <div class="detail-label">Cargo:</div>
        <div class="detail-value">{{ $funcionario->cargo->nombre }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Tipo Contrato:</div>
        <div class="detail-value">{{ $funcionario->tipoContrato->nombre }}</div>
    </div>
</div>

<div class="form-section">
    <h5 class="form-section-title">Ubicación</h5>

    <div class="detail-item">
        <div class="detail-label">Región:</div>
        <div class="detail-value">{{ $funcionario->region->nombre ?? '—' }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Provincia:</div>
        <div class="detail-value">{{ $funcionario->provincia->nombre ?? '—' }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Comuna:</div>
        <div class="detail-value">{{ $funcionario->comuna->nombre ?? '—' }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Dirección:</div>
        <div class="detail-value">{{ $funcionario->direccion }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Estado:</div>
        <div class="detail-value">
            @if($funcionario->activo)
                <span class="badge bg-success">Activo</span>
            @else
                <span class="badge bg-danger">Inactivo</span>
            @endif
        </div>
    </div>

</div>

@endsection
