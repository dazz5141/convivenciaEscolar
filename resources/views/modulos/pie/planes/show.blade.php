@extends('layouts.app')

@section('title', 'Detalle del Plan Individual PIE')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Plan Individual PIE #{{ $planIndividualPIE->id }}</h1>
        <p class="text-muted">Detalle completo del plan individual</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        {{-- Volver --}}
        <a href="{{ route('pie.planes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- Historial --}}
        @if($planIndividualPIE->estudiante)
            <a href="{{ route('pie.historial.index', $planIndividualPIE->estudiante->id) }}" class="btn btn-primary">
                <i class="bi bi-clock-history me-1"></i> Historial
            </a>
        @endif
    </div>
</div>


<div class="row g-4">

    {{-- ===========================================
         COLUMNA PRINCIPAL
    ============================================ --}}
    <div class="col-lg-8">

        {{-- INFORMACIÓN DEL PLAN --}}
        <div class="form-section">
            <h5 class="form-section-title">Información del Plan</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha Inicio:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($planIndividualPIE->fecha_inicio)->format('d/m/Y') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Fecha Término:</div>
                <div class="detail-value">
                    {{ $planIndividualPIE->fecha_termino
                        ? \Carbon\Carbon::parse($planIndividualPIE->fecha_termino)->format('d/m/Y')
                        : '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Objetivos:</div>
                <div class="detail-value">{!! nl2br(e($planIndividualPIE->objetivos)) !!}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Evaluación:</div>
                <div class="detail-value">
                    {!! $planIndividualPIE->evaluacion
                        ? nl2br(e($planIndividualPIE->evaluacion))
                        : '<span class="text-muted">Sin evaluación registrada.</span>' !!}
                </div>
            </div>
        </div>




        {{-- ESTUDIANTE PIE --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Estudiante PIE</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">
                    @if($planIndividualPIE->estudiante && $planIndividualPIE->estudiante->alumno)
                        {{ $planIndividualPIE->estudiante->alumno->apellido_paterno }}
                        {{ $planIndividualPIE->estudiante->alumno->apellido_materno }},
                        {{ $planIndividualPIE->estudiante->alumno->nombre }}
                    @else
                        <span class="text-muted">Sin información del alumno.</span>
                    @endif
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Curso:</div>
                <div class="detail-value">
                    {{ $planIndividualPIE->estudiante->alumno->curso->nombre ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Diagnóstico PIE:</div>
                <div class="detail-value">
                    {!! $planIndividualPIE->estudiante?->diagnostico
                        ? nl2br(e($planIndividualPIE->estudiante?->diagnostico))
                            : '<span class="text-muted">Sin diagnóstico registrado.</span>' !!}
                </div>
            </div>
        </div>

    </div>



    {{-- ===========================================
         COLUMNA DERECHA (RESUMEN)
    ============================================ --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">

                <p class="mb-2">
                    <strong>Estudiante:</strong><br>
                    {{ $planIndividualPIE->estudiante?->alumno?->apellido_paterno }}
                    {{ $planIndividualPIE->estudiante?->alumno?->apellido_materno }},
                    {{ $planIndividualPIE->estudiante?->alumno?->nombre ?? '—' }}
                </p>

                <p class="mb-2">
                    <strong>Inicio:</strong><br>
                    {{ \Carbon\Carbon::parse($planIndividualPIE->fecha_inicio)->format('d/m/Y') }}
                </p>

                <p class="mb-2">
                    <strong>Término:</strong><br>
                    {{ $planIndividualPIE->fecha_termino
                        ? \Carbon\Carbon::parse($planIndividualPIE->fecha_termino)->format('d/m/Y')
                        : '—' }}
                </p>

            </div>
        </div>
    </div>

</div>

@endsection
