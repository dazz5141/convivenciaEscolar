@extends('layouts.app')

@section('title', 'Detalle del Evento de Asistencia')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Evento #{{ $evento->id }}</h1>
        <p class="text-muted">Información completa del registro</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- Volver --}}
        <a href="{{ route('inspectoria.asistencia.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- =========================================================
             PERMISO: SOLO PUEDE EDITAR QUIEN TIENE edit EN ATRASOS
        ========================================================== --}}
        @if(canAccess('atrasos', 'edit'))
        <a href="{{ route('inspectoria.asistencia.edit', $evento) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>
        @endif

    </div>
</div>

{{-- =========================================================
     PERMISO: SOLO PUEDE VER QUIEN TIENE view EN ATRASOS
========================================================= --}}
@if(canAccess('atrasos', 'view'))

<div class="row g-4">

    {{-- ============================================================
         COLUMNA IZQUIERDA — Información principal
    ============================================================ --}}
    <div class="col-lg-8">

        {{-- INFORMACIÓN GENERAL --}}
        <div class="form-section">
            <h5 class="form-section-title">Información del Evento</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}
                    @if($evento->hora)
                        — {{ \Carbon\Carbon::parse($evento->hora)->format('H:i') }}
                    @endif
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Tipo de evento:</div>
                <div class="detail-value">
                    {{ $evento->tipo->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Observación:</div>
                <div class="detail-value">
                    {!! $evento->observaciones 
                        ? nl2br(e($evento->observaciones)) 
                        : '<span class="text-muted">Sin observaciones.</span>' !!}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Registrado por:</div>
                <div class="detail-value">
                    {{ $evento->funcionario->nombre_completo ?? 'No disponible' }}
                </div>
            </div>

        </div>

        {{-- INFORMACIÓN DEL ALUMNO --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Alumno Involucrado</h5>

            @if($evento->alumno)
                <div class="detail-item">
                    <div class="detail-label">Nombre:</div>
                    <div class="detail-value">
                        {{ $evento->alumno->nombre_completo }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Curso:</div>
                    <div class="detail-value">
                        {{ $evento->alumno->curso->nombre ?? '—' }}
                    </div>
                </div>
            @else
                <p class="text-muted m-0">No hay alumno asociado.</p>
            @endif
        </div>

    </div>

    {{-- ============================================================
         COLUMNA DERECHA — Resumen
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
                    @if($evento->alumno)
                        {{ $evento->alumno->nombre_completo }}<br>
                        <span class="text-muted">{{ $evento->alumno->curso->nombre ?? '' }}</span>
                    @else
                        <span class="text-muted">No hay alumno registrado</span>
                    @endif
                </p>

                {{-- TIPO --}}
                <p class="mb-2">
                    <strong>Tipo:</strong><br>
                    {{ $evento->tipo->nombre }}
                </p>

                {{-- FECHA --}}
                <p class="mb-2">
                    <strong>Fecha:</strong><br>
                    {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}
                    @if($evento->hora)
                        — {{ \Carbon\Carbon::parse($evento->hora)->format('H:i') }}
                    @endif
                </p>

                {{-- FUNCIONARIO --}}
                <p class="mb-2">
                    <strong>Registrado por:</strong><br>
                    {{ $evento->funcionario->nombre_completo ?? 'No disponible' }}
                </p>

            </div>
        </div>

    </div>

</div>

@endif {{-- FIN PERMISO DE VIEW --}}

@endsection
