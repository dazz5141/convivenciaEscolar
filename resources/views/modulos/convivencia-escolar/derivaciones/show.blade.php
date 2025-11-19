@extends('layouts.app')

@section('title', 'Detalle de la Derivación')

@section('content')

{{-- =========================================================
     ENCABEZADO
========================================================= --}}
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Derivación #{{ $derivacion->id }}</h1>
        <p class="text-muted">Información completa de la derivación registrada</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- Volver --}}
        <a href="{{ route('convivencia.derivaciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- Editar --}}
        <a href="{{ route('convivencia.derivaciones.edit', $derivacion->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>

        {{-- Botón al origen --}}
        @php
            use App\Models\BitacoraIncidente;
            use App\Models\SeguimientoEmocional;
            use App\Models\MedidaRestaurativa;
        @endphp

        @if($derivacion->entidad_type === BitacoraIncidente::class)
            <a href="{{ route('convivencia.bitacora.show', $derivacion->entidad_id) }}" 
               class="btn btn-warning">
                <i class="bi bi-journal-text me-2"></i> Ver Incidente
            </a>

        @elseif($derivacion->entidad_type === SeguimientoEmocional::class)
            <a href="{{ route('convivencia.seguimiento.show', $derivacion->entidad_id) }}" 
               class="btn btn-warning">
                <i class="bi bi-emoji-smile me-2"></i> Ver Seguimiento
            </a>

        @elseif($derivacion->entidad_type === MedidaRestaurativa::class)
            <a href="{{ route('convivencia.medidas.show', $derivacion->entidad_id) }}" 
               class="btn btn-warning">
                <i class="bi bi-arrow-right-circle me-2"></i> Ver Medida
            </a>
        @endif
    </div>
</div>



<div class="row g-4">

    {{-- ============================================================
         COLUMNA IZQUIERDA
    ============================================================ --}}
    <div class="col-lg-8">

        {{-- INFORMACIÓN GENERAL --}}
        <div class="form-section">
            <h5 class="form-section-title">Información de la Derivación</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($derivacion->fecha)->format('d/m/Y') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Tipo:</div>
                <div class="detail-value">
                    <span class="badge bg-info">{{ ucfirst($derivacion->tipo) }}</span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Estado:</div>
                <div class="detail-value">
                    <span class="badge bg-primary">{{ $derivacion->estado }}</span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Destino:</div>
                <div class="detail-value">
                    {{ $derivacion->destino }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Motivo:</div>
                <div class="detail-value">
                    {!! $derivacion->motivo 
                        ? nl2br(e($derivacion->motivo)) 
                        : '<span class="text-muted">Sin información</span>' !!}
                </div>
            </div>
        </div>



        {{-- ALUMNO --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Alumno Derivado</h5>

            <p class="fw-bold mb-1">
                {{ $derivacion->alumno->nombre_completo }}
            </p>

            <small class="text-muted">
                {{ $derivacion->alumno->curso->nombre ?? 'Sin curso' }}
            </small>
        </div>



        {{-- ORIGEN --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Origen del Caso</h5>

            @if ($derivacion->entidad_type === \App\Models\MedidaRestaurativa::class)
                <p class="fw-bold">Medida Restaurativa</p>

                <a href="{{ route('convivencia.medidas.show', $derivacion->entidad_id) }}" 
                class="btn btn-warning btn-sm">
                    <i class="bi bi-eye"></i> Ver Medida
                </a>

            @elseif ($derivacion->entidad_type === \App\Models\SeguimientoEmocional::class)
                <p class="fw-bold">Seguimiento Emocional</p>

                <a href="{{ route('convivencia.seguimiento.show', $derivacion->entidad_id) }}" 
                class="btn btn-warning btn-sm">
                    <i class="bi bi-eye"></i> Ver Seguimiento
                </a>

            @else
                <p class="text-muted small">Sin origen asociado.</p>
            @endif
        </div>

        {{-- FUNCIONARIO --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Funcionario Responsable</h5>

            <p class="mb-1 fw-bold">
                {{ $derivacion->funcionario->nombre }}
                {{ $derivacion->funcionario->apellido_paterno }}
            </p>

            <small class="text-muted">
                {{ $derivacion->funcionario->cargo->nombre ?? 'Sin cargo' }}
            </small>
        </div>

    </div>



    {{-- ============================================================
         COLUMNA DERECHA
    ============================================================ --}}
    <div class="col-lg-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">

                <p class="mb-2">
                    <strong>Tipo:</strong><br>
                    {{ ucfirst($derivacion->tipo) }}
                </p>

                <p class="mb-2">
                    <strong>Estado:</strong><br>
                    <span class="badge bg-primary">{{ $derivacion->estado }}</span>
                </p>

                <p class="mb-2">
                    <strong>Fecha:</strong><br>
                    {{ \Carbon\Carbon::parse($derivacion->fecha)->format('d/m/Y') }}
                </p>

                <p class="mb-2">
                    <strong>Alumno:</strong><br>
                    {{ $derivacion->alumno->nombre_completo }}
                </p>

                <p class="mb-2">
                    <strong>Funcionario:</strong><br>
                    {{ $derivacion->funcionario->nombre_completo ?? '' }}
                </p>

            </div>
        </div>

    </div>

</div>

@endsection
