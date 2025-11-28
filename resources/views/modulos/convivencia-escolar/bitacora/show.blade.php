@extends('layouts.app')

@section('title', 'Detalle del Incidente')

@section('content')

@ver('bitacora')

{{-- =========================================================
     ENCABEZADO
========================================================= --}}
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Incidente #{{ $incidente->id }}</h1>
        <p class="text-muted">Información completa del incidente registrado</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- Volver --}}
        <a href="{{ route('convivencia.bitacora.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- Editar --}}
        @editar('bitacora')
        <a href="{{ route('convivencia.bitacora.edit', $incidente->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>
        @endeditar

        {{-- Crear seguimiento emocional --}}
        @crear('seguimientos')
        <a href="{{ route('convivencia.seguimiento.create', ['alumno' => $incidente->alumno_principal_id, 'incidente' => $incidente->id]) }}"
            class="btn btn-warning">
                <i class="bi bi-emoji-smile"></i> Crear Seguimiento Emocional
        </a>
        @endcrear

    </div>
</div>


<div class="row g-4">

    {{-- ============================================================
         COLUMNA IZQUIERDA (Información principal)
    ============================================================ --}}
    <div class="col-lg-8">

        {{-- INFORMACIÓN GENERAL --}}
        <div class="form-section">
            <h5 class="form-section-title">Información del Incidente</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($incidente->fecha)->format('d/m/Y') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Tipo:</div>
                <div class="detail-value">{{ $incidente->tipo_incidente }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Estado:</div>
                <div class="detail-value">
                    <span class="badge bg-primary">
                        {{ $incidente->estado->nombre ?? 'Sin estado' }}
                    </span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Descripción:</div>
                <div class="detail-value">
                    {!! $incidente->descripcion
                        ? nl2br(e($incidente->descripcion))
                        : '<span class="text-muted">Sin información</span>' !!}
                </div>
            </div>
        </div>


        {{-- ALUMNOS INVOLUCRADOS --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Alumnos Involucrados</h5>

            <div class="row">

                {{-- Víctimas --}}
                <div class="col-md-4">
                    <h6 class="fw-bold">Víctimas</h6>
                    @forelse($incidente->victimas as $v)
                        <div class="mb-2">
                            • {{ $v->alumno->nombre_completo }}
                            <br>
                            <small class="text-muted">{{ $v->alumno->curso->nombre ?? '—' }}</small>
                        </div>
                    @empty
                        <p class="text-muted small">Sin víctimas registradas.</p>
                    @endforelse
                </div>

                {{-- Agresores --}}
                <div class="col-md-4">
                    <h6 class="fw-bold">Agresores</h6>
                    @forelse($incidente->agresores as $a)
                        <div class="mb-2">
                            • {{ $a->alumno->nombre_completo }}
                            <br>
                            <small class="text-muted">{{ $a->alumno->curso->nombre ?? '—' }}</small>
                        </div>
                    @empty
                        <p class="text-muted small">Sin agresores registrados.</p>
                    @endforelse
                </div>

                {{-- Testigos --}}
                <div class="col-md-4">
                    <h6 class="fw-bold">Testigos</h6>
                    @forelse($incidente->testigos as $t)
                        <div class="mb-2">
                            • {{ $t->alumno->nombre_completo }}
                            <br>
                            <small class="text-muted">{{ $t->alumno->curso->nombre ?? '—' }}</small>
                        </div>
                    @empty
                        <p class="text-muted small">Sin testigos registrados.</p>
                    @endforelse
                </div>
            </div>
        </div>


        {{-- FUNCIONARIO REPORTANTE --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Funcionario que Reporta</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">
                    {{ $incidente->reportadoPor->nombre_completo ?? 'No disponible' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Cargo:</div>
                <div class="detail-value">
                    {{ $incidente->reportadoPor->cargo->nombre ?? 'Sin cargo' }}
                </div>
            </div>
        </div>


        {{-- SEGUIMIENTO EMOCIONAL --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Seguimiento Emocional</h5>

            @if($incidente->seguimiento)
                <div class="detail-item">
                    <div class="detail-label">Fecha:</div>
                    <div class="detail-value">
                        {{ $incidente->seguimiento->fecha }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Comentario:</div>
                    <div class="detail-value">
                        {!! nl2br(e($incidente->seguimiento->comentario)) !!}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Evaluado por:</div>
                    <div class="detail-value">
                        {{ $incidente->seguimiento->evaluador->nombre_completo ?? '' }}
                    </div>
                </div>
            @else
                <p class="text-muted">No existe seguimiento asociado.</p>
            @endif
        </div>

    </div>



    {{-- ============================================================
         COLUMNA DERECHA (Resumen)
    ============================================================ --}}
    <div class="col-lg-4">

        {{-- RESUMEN GENERAL --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen General</h5>
            </div>

            <div class="card-body">

                {{-- Tipo --}}
                <p class="mb-2">
                    <strong>Tipo:</strong><br>
                    {{ $incidente->tipo_incidente }}
                </p>

                {{-- Estado --}}
                <p class="mb-2">
                    <strong>Estado:</strong><br>
                    <span class="badge bg-primary">
                        {{ $incidente->estado->nombre ?? 'Sin estado' }}
                    </span>
                </p>

                {{-- Fecha --}}
                <p class="mb-2">
                    <strong>Fecha:</strong><br>
                    {{ \Carbon\Carbon::parse($incidente->fecha)->format('d/m/Y') }}
                </p>

                {{-- Reportado por --}}
                <p class="mb-2">
                    <strong>Funcionario:</strong><br>
                    {{ $incidente->reportadoPor->nombre_completo ?? 'No disponible' }}
                </p>

            </div>
        </div>


        {{-- DOCUMENTOS --}}
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Documentos Adjuntos</h5>

                {{-- Botón para ir al módulo de documentos --}}
                <a href="{{ route('convivencia.bitacora.documentos.index', $incidente->id) }}"
                class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-folder2-open me-1"></i> Gestionar Documentos
                </a>
            </div>

            <div class="card-body">

                @if($incidente->documentos->count())
                    <ul class="list-group list-group-flush">

                        @foreach($incidente->documentos as $doc)
                            <li class="list-group-item">
                                <a href="{{ asset($doc->ruta_archivo) }}" target="_blank">
                                    <i class="bi bi-file-earmark text-primary me-2"></i>
                                    {{ $doc->nombre_archivo }}
                                </a>
                            </li>
                        @endforeach

                    </ul>
                @else
                    <p class="text-muted small m-0">No hay documentos adjuntos.</p>
                @endif

            </div>
        </div>


        {{-- OBSERVACIONES --}}
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Historial de Observaciones</h5>
            </div>

            <div class="card-body">

                {{-- Agregar --}}
                @crear('bitacora')
                <form action="{{ route('convivencia.bitacora.observaciones.store', $incidente->id) }}"
                      method="POST" class="mb-3">
                    @csrf

                    <label class="form-label fw-bold">Agregar observación</label>
                    <textarea name="observacion" class="form-control" rows="3" required></textarea>

                    <button class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Agregar
                    </button>
                </form>
                @endcrear

                <hr>

                {{-- Listado --}}
                @forelse($incidente->observaciones->sortBy('fecha_observacion') as $obs)
                    <div class="mb-3 p-2 border rounded bg-light">

                        <div class="small text-muted mb-1">
                            {{ \Carbon\Carbon::parse($obs->fecha_observacion)->format('d/m/Y H:i') }}
                            — <strong>{{ $obs->funcionario->nombre_completo ?? 'Funcionario' }}</strong>
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

@endver

@endsection
