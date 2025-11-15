@extends('layouts.app')

@section('title', 'Detalle de la Citación')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Citación #{{ $citacion->id }}</h1>
        <p class="text-muted">Información completa del registro</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- Volver --}}
        <a href="{{ route('inspectoria.citaciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- Editar --}}
        <a href="{{ route('inspectoria.citaciones.edit', $citacion) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- ============================================================
         COLUMNA IZQUIERDA (Información principal)
    ============================================================ --}}
    <div class="col-lg-8">

        {{-- INFORMACIÓN GENERAL --}}
        <div class="form-section">
            <h5 class="form-section-title">Información de la Citación</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha citación:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($citacion->fecha_citacion)->format('d/m/Y H:i') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Estado:</div>
                <div class="detail-value">
                    {{ $citacion->estado->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Motivo:</div>
                <div class="detail-value">
                    {!! $citacion->motivo
                        ? nl2br(e($citacion->motivo))
                        : '<span class="text-muted">Sin motivo registrado.</span>' !!}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Observaciones:</div>
                <div class="detail-value">
                    {!! $citacion->observaciones
                        ? nl2br(e($citacion->observaciones))
                        : '<span class="text-muted">Sin observaciones.</span>' !!}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Registrado por:</div>
                <div class="detail-value">
                    {{ $citacion->funcionario->nombre_completo ?? 'No disponible' }}
                </div>
            </div>

        </div>

        {{-- INFORMACIÓN DEL ALUMNO --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Alumno Citado</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">{{ $citacion->alumno->nombre_completo }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Curso:</div>
                <div class="detail-value">
                    {{ $citacion->alumno->curso->nombre ?? '—' }}
                </div>
            </div>
        </div>

        {{-- INFORMACIÓN DEL APODERADO --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Apoderado</h5>

            @if($citacion->apoderado)
                <div class="detail-item">
                    <div class="detail-label">Nombre:</div>
                    <div class="detail-value">{{ $citacion->apoderado->nombre_completo }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Teléfono:</div>
                    <div class="detail-value">{{ $citacion->apoderado->telefono ?? '—' }}</div>
                </div>
            @else
                <p class="text-muted m-0">No se seleccionó apoderado.</p>
            @endif
        </div>

    </div>

    {{-- ============================================================
         COLUMNA DERECHA (Resumen)
    ============================================================ --}}
    <div class="col-lg-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">

                {{-- ALUMNO --}}
                <p class="mb-2">
                    <strong>Alumno:</strong><br>
                    {{ $citacion->alumno->nombre_completo }}<br>
                    <span class="text-muted">{{ $citacion->alumno->curso->nombre ?? '' }}</span>
                </p>

                {{-- APODERADO --}}
                <p class="mb-2">
                    <strong>Apoderado:</strong><br>
                    @if($citacion->apoderado)
                        {{ $citacion->apoderado->nombre_completo }}<br>
                        <span class="text-muted">{{ $citacion->apoderado->telefono ?? '' }}</span>
                    @else
                        <span class="text-muted">No registrado</span>
                    @endif
                </p>

                {{-- FECHA --}}
                <p class="mb-2">
                    <strong>Fecha citación:</strong><br>
                    {{ \Carbon\Carbon::parse($citacion->fecha_citacion)->format('d/m/Y H:i') }}
                </p>

                {{-- ESTADO --}}
                <p class="mb-2">
                    <strong>Estado:</strong><br>
                    {{ $citacion->estado->nombre }}
                </p>

                {{-- FUNCIONARIO --}}
                <p class="mb-0">
                    <strong>Registrado por:</strong><br>
                    {{ $citacion->funcionario->nombre_completo ?? 'No disponible' }}
                </p>

            </div>
        </div>

    </div>
</div>

@endsection
