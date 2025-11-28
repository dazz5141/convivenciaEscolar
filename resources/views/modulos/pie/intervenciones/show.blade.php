@extends('layouts.app')

@section('title', 'Detalle de Intervención PIE')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Intervención PIE #{{ $intervencion->id }}</h1>
        <p class="text-muted">Detalle completo de la intervención registrada</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- Volver --}}
        <a href="{{ route('pie.intervenciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- Historial --}}
        <a href="{{ route('pie.historial.index', $intervencion->estudiante->id) }}" 
           class="btn btn-primary">
            <i class="bi bi-clock-history me-1"></i> Historial
        </a>

    </div>
</div>

{{-- =========================================================
     PERMISOS
========================================================= --}}
@if(!canAccess('intervenciones','view'))
    <div class="alert alert-warning mt-3">
        <i class="bi bi-exclamation-triangle me-2"></i>
        No tienes permisos para visualizar esta intervención PIE.
    </div>
    @return
@endif


<div class="row g-4">

    {{-- ============================================================
         COLUMNA PRINCIPAL
    ============================================================ --}}
    <div class="col-lg-8">

        {{-- ============================================================
             INFORMACIÓN PRINCIPAL
        ============================================================ --}}
        <div class="form-section">
            <h5 class="form-section-title">Información de la Intervención</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($intervencion->fecha)->format('d/m/Y') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Tipo de Intervención:</div>
                <div class="detail-value">
                    {{ $intervencion->tipo->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Detalle de la Intervención:</div>
                <div class="detail-value">
                    {!! $intervencion->detalle ? nl2br(e($intervencion->detalle)) : '<span class="text-muted">Sin detalle registrado.</span>' !!}
                </div>
            </div>
        </div>



        {{-- ============================================================
             ESTUDIANTE PIE
        ============================================================ --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Estudiante PIE</h5>

            <div class="detail-item">
                <div class="detail-label">Alumno:</div>
                <div class="detail-value">
                    {{ $intervencion->estudiante->alumno->apellido_paterno }}
                    {{ $intervencion->estudiante->alumno->apellido_materno }},
                    {{ $intervencion->estudiante->alumno->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Curso:</div>
                <div class="detail-value">
                    {{ $intervencion->estudiante->alumno->curso->nombre ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Diagnóstico PIE:</div>
                <div class="detail-value">
                    {!! $intervencion->estudiante->diagnostico 
                        ? nl2br(e($intervencion->estudiante->diagnostico)) 
                        : '<span class="text-muted">Sin información</span>' !!}
                </div>
            </div>
        </div>



        {{-- ============================================================
             PROFESIONAL PIE
        ============================================================ --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Profesional PIE</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">
                    {{ $intervencion->profesional->funcionario->apellido_paterno }}
                    {{ $intervencion->profesional->funcionario->apellido_materno }},
                    {{ $intervencion->profesional->funcionario->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Cargo:</div>
                <div class="detail-value">
                    {{ $intervencion->profesional->funcionario->cargo->nombre ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Tipo de Profesional PIE:</div>
                <div class="detail-value">
                    {{ $intervencion->profesional->tipo->nombre ?? '—' }}
                </div>
            </div>
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

                <p class="mb-2">
                    <strong>Estudiante:</strong><br>
                    {{ $intervencion->estudiante->alumno->apellido_paterno }}
                    {{ $intervencion->estudiante->alumno->apellido_materno }},
                    {{ $intervencion->estudiante->alumno->nombre }}
                </p>

                <p class="mb-2">
                    <strong>Profesional:</strong><br>
                    {{ $intervencion->profesional->funcionario->apellido_paterno }}
                    {{ $intervencion->profesional->funcionario->apellido_materno }},
                    {{ $intervencion->profesional->funcionario->nombre }}
                </p>

                <p class="mb-2">
                    <strong>Tipo:</strong><br>
                    {{ $intervencion->tipo->nombre }}
                </p>

                <p class="mb-2">
                    <strong>Fecha:</strong><br>
                    {{ \Carbon\Carbon::parse($intervencion->fecha)->format('d/m/Y') }}
                </p>

            </div>
        </div>

    </div>

</div>

@endsection
