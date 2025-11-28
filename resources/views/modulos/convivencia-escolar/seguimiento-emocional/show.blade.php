@extends('layouts.app')

@section('title', 'Detalle del Seguimiento Emocional')

@section('content')

<div class="page-header d-flex justify-content-between flex-wrap align-items-center">
    <div class="mb-2">
        <h1 class="page-title">Seguimiento Emocional #{{ $seguimiento->id }}</h1>
        <p class="text-muted">Información completa del seguimiento registrado</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- EDITAR (solo roles con permiso edit en seguimientos) --}}
        @editar('seguimientos')
        <a href="{{ route('convivencia.seguimiento.edit', $seguimiento->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>
        @endeditar

        {{-- VOLVER (visible para todos los roles) --}}
        <a href="{{ route('convivencia.seguimiento.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- REGISTRAR MEDIDA RESTAURATIVA (solo roles autorizados) --}}
        @crear('medidas')
        <a href="{{ route('convivencia.medidas.create', ['seguimiento_id' => $seguimiento->id]) }}"
            class="btn btn-warning">
            <i class="bi bi-emoji-smile me-2"></i> Registrar Medida Restaurativa
        </a>
        @endcrear

        {{-- CREAR DERIVACIÓN (solo roles autorizados) --}}
        @crear('derivaciones')
        <a href="{{ route('convivencia.derivaciones.create', [
                'tipo_entidad' => 'seguimiento',
                'entidad_id'   => $seguimiento->id,
                'alumno_id'    => $seguimiento->alumno_id
            ]) }}"
           class="btn btn-warning">
            <i class="bi bi-arrow-right-circle me-2"></i> Crear Derivación
        </a>
        @endcrear

    </div>
</div>


<div class="row g-4">

    {{-- ===========================
         COLUMNA PRINCIPAL
    ============================ --}}
    <div class="col-lg-8">

        {{-- ===========================
             ALUMNO
        ============================ --}}
        <div class="form-section">
            <h5 class="form-section-title">Alumno Evaluado</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">
                    {{ $seguimiento->alumno->apellido_paterno }}
                    {{ $seguimiento->alumno->apellido_materno }},
                    {{ $seguimiento->alumno->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Curso:</div>
                <div class="detail-value">
                    {{ $seguimiento->alumno->curso->nombre ?? 'Sin curso asignado' }}
                </div>
            </div>
        </div>



        {{-- ===========================
             INFORMACIÓN DEL SEGUIMIENTO
        ============================ --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Información del Seguimiento</h5>

            {{-- Fecha --}}
            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($seguimiento->fecha)->format('d/m/Y') }}
                </div>
            </div>

            {{-- Nivel emocional --}}
            <div class="detail-item">
                <div class="detail-label">Nivel emocional:</div>
                <div class="detail-value">
                    <span class="badge bg-info">
                        {{ $seguimiento->nivel->nombre ?? 'No asignado' }}
                    </span>
                </div>
            </div>

            {{-- Estado --}}
            <div class="detail-item">
                <div class="detail-label">Estado:</div>
                <div class="detail-value">
                    <span class="badge bg-{{ $seguimiento->estado->color ?? 'secondary' }}">
                        {{ $seguimiento->estado->nombre }}
                    </span>
                </div>
            </div>

            {{-- Comentario --}}
            <div class="detail-item">
                <div class="detail-label">Comentario:</div>
                <div class="detail-value">
                    {!! nl2br(e($seguimiento->comentario ?? 'Sin comentarios')) !!}
                </div>
            </div>
        </div>



        {{-- ===========================
             FUNCIONARIO
        ============================ --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Funcionario Evaluador</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">
                    {{ $seguimiento->evaluador->apellido_paterno ?? '' }}
                    {{ $seguimiento->evaluador->apellido_materno ?? '' }},
                    {{ $seguimiento->evaluador->nombre ?? '' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Cargo:</div>
                <div class="detail-value">
                    {{ $seguimiento->evaluador->cargo->nombre ?? 'Sin cargo registrado' }}
                </div>
            </div>
        </div>

    </div>



    {{-- ===========================
         COLUMNA DERECHA
    ============================ --}}
    <div class="col-lg-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen del Estado</h5>
            </div>

            <div class="card-body">

                <p><strong>Fecha:</strong><br>
                    {{ \Carbon\Carbon::parse($seguimiento->fecha)->format('d/m/Y') }}
                </p>

                <p><strong>Estado actual:</strong><br>
                    <span class="badge bg-{{ $seguimiento->estado->color ?? 'secondary' }}">
                        {{ $seguimiento->estado->nombre }}
                    </span>
                </p>

                <p><strong>Nivel emocional:</strong><br>
                    <span class="badge bg-info">
                        {{ $seguimiento->nivel->nombre ?? 'Sin asignar' }}
                    </span>
                </p>

                <p><strong>Evaluado por:</strong><br>
                    {{ $seguimiento->evaluador->nombre ?? '—' }}
                </p>

            </div>
        </div>

        {{-- ============================================
            HISTORIAL DE OBSERVACIONES
        ============================================ --}}
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Historial de Observaciones</h5>
            </div>

            <div class="card-body">

                {{-- FORMULARIO PARA AGREGAR OBSERVACIÓN --}}
                <form action="{{ route('convivencia.seguimiento.observaciones.store', $seguimiento->id) }}" method="POST" class="mb-3">
                    @csrf

                    <label class="form-label fw-bold">Agregar nueva observación</label>
                    <textarea name="observacion" class="form-control" rows="3" required></textarea>

                    <button type="submit" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Observación
                    </button>
                </form>

                <hr>

                {{-- LISTADO DE OBSERVACIONES --}}
                @forelse ($seguimiento->observaciones->sortBy('fecha_observacion') as $obs)
                    <div class="mb-3 p-2 border rounded bg-light">

                        <div class="small text-muted">
                            {{ \Carbon\Carbon::parse($obs->fecha_observacion)->format('d/m/Y H:i') }}
                            — <strong>{{ $obs->funcionario->nombre ?? 'Funcionario' }}</strong>
                        </div>

                        <div>{!! nl2br(e($obs->observacion)) !!}</div>

                    </div>
                @empty
                    <p class="text-muted small">No existen observaciones registradas.</p>
                @endforelse

            </div>
        </div>

    </div>

</div>

@endsection
