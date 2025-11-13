@extends('layouts.app')

@section('title', 'Detalle del Informe PIE')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Informe PIE #{{ $informePIE->id }}</h1>
        <p class="text-muted">Detalle completo del informe registrado</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        {{-- Volver --}}
        <a href="{{ route('pie.informes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- Historial --}}
        @if($informePIE->estudiante)
            <a href="{{ route('pie.historial.index', $informePIE->estudiante->id) }}" class="btn btn-primary">
                <i class="bi bi-clock-history me-1"></i> Historial
            </a>
        @endif
    </div>
</div>

<div class="row g-4">

    {{-- ============================================================
         COLUMNA IZQUIERDA (PRINCIPAL)
    ============================================================ --}}
    <div class="col-lg-8">

        {{-- ============================================================
             INFORMACIÓN DEL INFORME
        ============================================================ --}}
        <div class="form-section">
            <h5 class="form-section-title">Información del Informe</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($informePIE->fecha)->format('d/m/Y') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Tipo:</div>
                <div class="detail-value">{{ $informePIE->tipo }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Contenido:</div>
                <div class="detail-value">
                    {!! $informePIE->contenido
                        ? nl2br(e($informePIE->contenido))
                        : '<span class="text-muted">Sin contenido registrado.</span>' !!}
                </div>
            </div>
        </div>

        {{-- ============================================================
             ESTUDIANTE PIE
        ============================================================ --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Estudiante PIE</h5>

            @if($informePIE->estudiante && $informePIE->estudiante->alumno)
                <div class="detail-item">
                    <div class="detail-label">Alumno:</div>
                    <div class="detail-value">
                        {{ $informePIE->estudiante->alumno->apellido_paterno }}
                        {{ $informePIE->estudiante->alumno->apellido_materno }},
                        {{ $informePIE->estudiante->alumno->nombre }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Curso:</div>
                    <div class="detail-value">
                        {{ $informePIE->estudiante->alumno->curso->nombre ?? '—' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Diagnóstico PIE:</div>
                    <div class="detail-value">
                        {!! $informePIE->estudiante->diagnostico
                            ? nl2br(e($informePIE->estudiante->diagnostico))
                            : '<span class="text-muted">Sin información</span>' !!}
                    </div>
                </div>
            @else
                <p class="text-muted mb-0">No se encontró información del estudiante asociado.</p>
            @endif
        </div>
    </div>

    {{-- ============================================================
         COLUMNA DERECHA (RESUMEN)
    ============================================================ --}}
    <div class="col-lg-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">

                @if($informePIE->estudiante && $informePIE->estudiante->alumno)
                    <p class="mb-2">
                        <strong>Estudiante:</strong><br>
                        {{ $informePIE->estudiante->alumno->apellido_paterno }}
                        {{ $informePIE->estudiante->alumno->apellido_materno }},
                        {{ $informePIE->estudiante->alumno->nombre }}
                    </p>
                @endif

                <p class="mb-2">
                    <strong>Tipo de Informe:</strong><br>
                    {{ $informePIE->tipo }}
                </p>

                <p class="mb-2">
                    <strong>Fecha:</strong><br>
                    {{ \Carbon\Carbon::parse($informePIE->fecha)->format('d/m/Y') }}
                </p>

            </div>
        </div>

    </div>
</div>

@endsection
