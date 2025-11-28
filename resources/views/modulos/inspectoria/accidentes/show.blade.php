@extends('layouts.app')

@section('title', 'Detalle del Accidente Escolar')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Accidente Escolar #{{ $accidente->id }}</h1>
        <p class="text-muted">Información completa del registro de accidente</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- Volver --}}
        <a href="{{ route('inspectoria.accidentes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- Editar (solo si tiene permiso) --}}
        @canAccess('editar_accidente')
            <a href="{{ route('inspectoria.accidentes.edit', $accidente) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i> Editar
            </a>
        @endcanAccess

    </div>
</div>

<div class="row g-4">

    {{-- ============================================================
         COLUMNA IZQUIERDA (Información principal)
    ============================================================ --}}
    <div class="col-lg-8">

        {{-- INFORMACIÓN GENERAL DEL ACCIDENTE --}}
        <div class="form-section">
            <h5 class="form-section-title">Información del Accidente</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">
                    {{ $accidente->fecha->format('d/m/Y H:i') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Tipo de accidente:</div>
                <div class="detail-value">{{ $accidente->tipo->nombre }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Lugar:</div>
                <div class="detail-value">{{ $accidente->lugar }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Descripción:</div>
                <div class="detail-value">
                    {!! $accidente->descripcion 
                        ? nl2br(e($accidente->descripcion)) 
                        : '<span class="text-muted">Sin descripción registrada.</span>' !!}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Atención inmediata:</div>
                <div class="detail-value">
                    {!! $accidente->atencion_inmediata 
                        ? nl2br(e($accidente->atencion_inmediata)) 
                        : '<span class="text-muted">Sin información registrada.</span>' !!}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Derivación a salud:</div>
                <div class="detail-value">
                    {{ $accidente->derivacion_salud ? 'Sí, fue derivado' : 'No' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Registrado por:</div>
                <div class="detail-value">
                    {{ $accidente->funcionario->nombre_completo 
                        ?? $accidente->usuario->nombre_completo 
                        ?? 'No disponible' }}
                </div>
            </div>

        </div>

        {{-- INFORMACIÓN DEL ALUMNO --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Alumno Involucrado</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">
                    {{ $accidente->alumno->nombre_completo }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Curso:</div>
                <div class="detail-value">
                    {{ $accidente->alumno->curso->nombre ?? '—' }}
                </div>
            </div>

        </div>
    </div>

    {{-- ============================================================
         COLUMNA DERECHA (RESUMEN GENERAL)
    ============================================================ --}}
    <div class="col-lg-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">

                <p class="mb-2">
                    <strong>Alumno:</strong><br>
                    {{ $accidente->alumno->nombre_completo }}<br>
                    <span class="text-muted">{{ $accidente->alumno->curso->nombre ?? '' }}</span>
                </p>

                <p class="mb-2">
                    <strong>Tipo de accidente:</strong><br>
                    {{ $accidente->tipo->nombre }}
                </p>

                <p class="mb-2">
                    <strong>Fecha:</strong><br>
                    {{ $accidente->fecha->format('d/m/Y H:i') }}
                </p>

                <p class="mb-2">
                    <strong>Registrado por:</strong><br>
                    {{ $accidente->funcionario->nombre_completo 
                        ?? $accidente->usuario->nombre_completo 
                        ?? 'No disponible' }}
                </p>

            </div>
        </div>

    </div>
</div>

@endsection
