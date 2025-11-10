@extends('layouts.app')

@section('title', 'Detalle del Apoderado')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">
            {{ $apoderado->nombre }} {{ $apoderado->apellido_paterno }} {{ $apoderado->apellido_materno }}
        </h1>
        <p class="text-muted">RUT: {{ $apoderado->run }}</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('apoderados.edit', $apoderado->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>

        <a href="{{ route('apoderados.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>
    </div>
</div>

<div class="form-section">
    <h5 class="form-section-title">Datos Personales</h5>

    <div class="detail-item">
        <div class="detail-label">RUN:</div>
        <div class="detail-value">{{ $apoderado->run }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Nombre:</div>
        <div class="detail-value">
            {{ $apoderado->nombre }} {{ $apoderado->apellido_paterno }} {{ $apoderado->apellido_materno }}
        </div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Teléfono:</div>
        <div class="detail-value">{{ $apoderado->telefono ?? '—' }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Correo Electrónico:</div>
        <div class="detail-value">{{ $apoderado->email ?? '—' }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Dirección:</div>
        <div class="detail-value">{{ $apoderado->direccion ?? '—' }}</div>
    </div>
</div>

<div class="form-section">
    <h5 class="form-section-title">Ubicación</h5>

    <div class="detail-item">
        <div class="detail-label">Región:</div>
        <div class="detail-value">{{ $apoderado->region->nombre ?? '—' }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Provincia:</div>
        <div class="detail-value">{{ $apoderado->provincia->nombre ?? '—' }}</div>
    </div>

    <div class="detail-item">
        <div class="detail-label">Comuna:</div>
        <div class="detail-value">{{ $apoderado->comuna->nombre ?? '—' }}</div>
    </div>

</div>

<div class="form-section">
    <h5 class="form-section-title">Alumnos Asociados</h5>

    @if($apoderado->alumnos->isEmpty())
        <p class="text-muted">No tiene alumnos asociados.</p>
    @else
        <ul class="list-group">
            @foreach($apoderado->alumnos as $alumno)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $alumno->nombre }} {{ $alumno->apellido_paterno }}</strong>
                        <br>
                        <span class="text-muted small">
                            {{ $alumno->curso->nivel }} {{ $alumno->curso->letra }}
                        </span>
                    </div>
                    <a href="{{ route('alumnos.show', $alumno->id) }}"
                       class="btn btn-sm btn-outline-primary">
                        Ver Alumno
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

</div>

@endsection
