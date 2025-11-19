@extends('layouts.app')

@section('title', 'Detalle de la Medida Restaurativa')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Medida #{{ $medida->id }}</h1>
        <p class="text-muted">Información completa de la medida restaurativa</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- Volver --}}
        <a href="{{ route('convivencia.medidas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- Editar --}}
        <a href="{{ route('convivencia.medidas.edit', $medida->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>

        {{-- Crear Derivación --}}
        <a href="{{ route('convivencia.derivaciones.create', [
                'tipo_entidad' => 'medida',
                'entidad_id'   => $medida->id,
                'alumno_id'    => $medida->alumno_id
            ]) }}"
            class="btn btn-warning">
            <i class="bi bi-arrow-right-circle me-2"></i> Crear Derivación
        </a>
    </div>
</div>


<div class="row g-4">

    {{-- ===============================================
         COLUMNA IZQUIERDA — Información principal
    =============================================== --}}
    <div class="col-lg-8">

        {{-- INFORMACIÓN GENERAL --}}
        <div class="form-section">
            <h5 class="form-section-title">Información de la Medida</h5>

            <div class="detail-item">
                <div class="detail-label">Tipo de medida:</div>
                <div class="detail-value">
                    {{ $medida->tipoMedida->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Estado de cumplimiento:</div>
                <div class="detail-value">
                    {{ $medida->cumplimiento->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Fecha inicio:</div>
                <div class="detail-value">
                    {{ $medida->fecha_inicio 
                        ? \Carbon\Carbon::parse($medida->fecha_inicio)->format('d/m/Y') 
                        : '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Fecha fin:</div>
                <div class="detail-value">
                    {{ $medida->fecha_fin 
                        ? \Carbon\Carbon::parse($medida->fecha_fin)->format('d/m/Y') 
                        : '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Observaciones:</div>
                <div class="detail-value">
                    {!! $medida->observaciones 
                        ? nl2br(e($medida->observaciones)) 
                        : '<span class="text-muted">Sin información disponible.</span>' !!}
                </div>
            </div>

        </div>


        {{-- INFORMACIÓN DEL ALUMNO --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Alumno Involucrado</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">
                    {{ $medida->alumno->nombre_completo ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Curso:</div>
                <div class="detail-value">
                    {{ $medida->alumno->curso->nombre ?? '—' }}
                </div>
            </div>
        </div>


        {{-- INFORMACIÓN DEL INCIDENTE (solo si existe) --}}
        @if($medida->incidente)
        <div class="form-section mt-4">
            <h5 class="form-section-title">Incidente Asociado</h5>

            <div class="detail-item">
                <div class="detail-label">ID del incidente:</div>
                <div class="detail-value">
                    {{ $medida->incidente->id }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Fecha del incidente:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($medida->incidente->fecha)->format('d/m/Y') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Descripción:</div>
                <div class="detail-value">
                    {!! $medida->incidente->descripcion 
                        ? nl2br(e($medida->incidente->descripcion))
                        : '<span class="text-muted">Sin información.</span>' !!}
                </div>
            </div>
        </div>
        @endif

    </div>


    {{-- ===============================================
         COLUMNA DERECHA — Resumen rápido
    =============================================== --}}
    <div class="col-lg-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">

                <p class="mb-2">
                    <strong>Alumno:</strong><br>
                    {{ $medida->alumno->nombre_completo ?? '—' }} <br>
                    <span class="text-muted">{{ $medida->alumno->curso->nombre ?? '' }}</span>
                </p>

                <p class="mb-2">
                    <strong>Tipo de medida:</strong><br>
                    {{ $medida->tipoMedida->nombre }}
                </p>

                <p class="mb-2">
                    <strong>Estado:</strong><br>
                    {{ $medida->cumplimiento->nombre }}
                </p>

                <p class="mb-2">
                    <strong>Responsable:</strong><br>
                    {{ $medida->responsable->nombre }} {{ $medida->responsable->apellido_paterno }}
                </p>

                @if($medida->incidente)
                <p class="mb-2">
                    <strong>Incidente asociado:</strong><br>
                    ID {{ $medida->incidente->id }}
                </p>
                @endif

            </div>
        </div>

    </div>
</div>

@endsection
