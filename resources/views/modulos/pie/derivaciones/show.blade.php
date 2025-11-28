@extends('layouts.app')

@section('title', 'Detalle de Derivación PIE')

@section('content')

{{-- ============================================================
     PERMISO: VER
============================================================ --}}
@if(!canAccess('pie','view'))
    <div class="alert alert-danger mt-3">
        <i class="bi bi-x-circle me-2"></i>
        No tienes permisos para ver los detalles de derivaciones PIE.
    </div>
    @return
@endif


<div class="page-header d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h1 class="page-title">Derivación PIE #{{ $derivacionPIE->id }}</h1>
        <p class="text-muted">Detalle completo de la derivación registrada</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- PERMISO: VER --}}
        @if(canAccess('pie','view'))
            <a href="{{ route('pie.derivaciones.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        @endif

        {{-- Historial (visible siempre porque NO es una acción administrativa) --}}
        @if($derivacionPIE->estudiante)
            <a href="{{ route('pie.historial.index', $derivacionPIE->estudiante->id) }}"
               class="btn btn-primary">
                <i class="bi bi-clock-history me-1"></i> Historial
            </a>
        @endif

    </div>
</div>


<div class="row g-4">

    {{-- ============================
         COLUMNA PRINCIPAL
    ============================= --}}
    <div class="col-lg-8">

        {{-- INFORMACIÓN GENERAL --}}
        <div class="form-section">
            <h5 class="form-section-title">Información de la Derivación</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($derivacionPIE->fecha)->format('d/m/Y') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Destino:</div>
                <div class="detail-value">{{ $derivacionPIE->destino }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Estado:</div>
                <div class="detail-value">
                    <span class="badge bg-{{ $derivacionPIE->estado == 'Cerrada' ? 'secondary' : ($derivacionPIE->estado == 'En curso' ? 'info' : 'warning') }}">
                        {{ $derivacionPIE->estado ?? '—' }}
                    </span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Motivo:</div>
                <div class="detail-value">
                    {!! nl2br(e($derivacionPIE->motivo)) !!}
                </div>
            </div>
        </div>



        {{-- ESTUDIANTE PIE --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Estudiante PIE</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">
                    {{ $derivacionPIE->estudiante?->alumno?->apellido_paterno }}
                    {{ $derivacionPIE->estudiante?->alumno?->apellido_materno }},
                    {{ $derivacionPIE->estudiante?->alumno?->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Curso:</div>
                <div class="detail-value">
                    {{ $derivacionPIE->estudiante->alumno->curso->nombre ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Diagnóstico PIE:</div>
                <div class="detail-value">
                    {!! $derivacionPIE->estudiante?->diagnostico
                        ? nl2br(e($derivacionPIE->estudiante->diagnostico))
                        : '<span class="text-muted">Sin diagnóstico registrado.</span>' !!}
                </div>
            </div>

        </div>

    </div>



    {{-- ============================
         COLUMNA DERECHA (RESUMEN)
    ============================= --}}
    <div class="col-lg-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">

                <p class="mb-2">
                    <strong>Estudiante:</strong><br>
                    {{ $derivacionPIE?->estudiante?->alumno?->apellido_paterno }}
                    {{ $derivacionPIE?->estudiante?->alumno?->apellido_materno }},
                    {{ $derivacionPIE?->estudiante?->alumno?->nombre }}
                </p>

                <p class="mb-2">
                    <strong>Fecha:</strong><br>
                    {{ $derivacionPIE?->fecha ? \Carbon\Carbon::parse($derivacionPIE->fecha)->format('d/m/Y') : '—' }}
                </p>

                <p class="mb-2">
                    <strong>Destino:</strong><br>
                    {{ $derivacionPIE?->destino ?? '—' }}
                </p>

                <p class="mb-2">
                    <strong>Estado:</strong><br>
                    {{ $derivacionPIE?->estado ?? '—' }}
                </p>

            </div>
        </div>

    </div>

</div>

@endsection
