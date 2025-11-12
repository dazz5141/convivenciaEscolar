@extends('layouts.app')

@section('title', 'Detalle del Informe PIE')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Informe PIE #{{ $informe->id }}</h1>
        <p class="text-muted">Detalle completo del informe registrado</p>
    </div>

    <a href="{{ route('pie.informes.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Volver
    </a>

    <a href="{{ route('pie.historial.index', $data->estudiante->id) }}" class="btn btn-outline-info">
        <i class="bi bi-clock-history me-1"></i> Volver al Historial
    </a>
</div>


<div class="row g-4">

    {{-- ===========================================
         COLUMNA PRINCIPAL
    ============================================ --}}
    <div class="col-lg-8">

        {{-- INFORMACIÓN PRINCIPAL --}}
        <div class="form-section">
            <h5 class="form-section-title">Información del Informe</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($informe->fecha)->format('d/m/Y') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Tipo:</div>
                <div class="detail-value">
                    {{ $informe->tipo }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Contenido:</div>
                <div class="detail-value">
                    {!! nl2br(e($informe->contenido)) !!}
                </div>
            </div>
        </div>


        {{-- ESTUDIANTE PIE --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Estudiante PIE</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">
                    {{ $informe->estudiante->alumno->apellido_paterno }}
                    {{ $informe->estudiante->alumno->apellido_materno }},
                    {{ $informe->estudiante->alumno->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Curso:</div>
                <div class="detail-value">
                    {{ $informe->estudiante->alumno->curso->nombre ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Diagnóstico PIE:</div>
                <div class="detail-value">
                    {!! $informe->estudiante->diagnostico
                        ? nl2br(e($informe->estudiante->diagnostico))
                        : '<span class="text-muted">Sin diagnóstico registrado.</span>' !!}
                </div>
            </div>
        </div>

    </div>



    {{-- ===========================================
         COLUMNA DERECHA
    ============================================ --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">

                <p class="mb-2">
                    <strong>Estudiante:</strong><br>
                    {{ $informe->estudiante->alumno->apellido_paterno }}
                    {{ $informe->estudiante->alumno->apellido_materno }},
                    {{ $informe->estudiante->alumno->nombre }}
                </p>

                <p class="mb-2">
                    <strong>Tipo de informe:</strong><br>
                    {{ $informe->tipo }}
                </p>

                <p class="mb-2">
                    <strong>Fecha:</strong><br>
                    {{ \Carbon\Carbon::parse($informe->fecha)->format('d/m/Y') }}
                </p>

            </div>
        </div>
    </div>

</div>

@endsection
