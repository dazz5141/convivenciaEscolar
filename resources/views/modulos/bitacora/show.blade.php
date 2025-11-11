@extends('layouts.app')

@section('title', 'Detalle del Incidente')

@section('content')
<div class="page-header d-flex justify-content-between flex-wrap align-items-center">
    <div class="mb-2">
        <h1 class="page-title">Detalle del Incidente #{{ $incidente->id }}</h1>
        <p class="text-muted">Información completa del incidente registrado</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('bitacora.edit', $incidente->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>

        <a href="{{ route('bitacora.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>
    </div>
</div>


<div class="row g-4">

    {{-- ===========================
         COLUMNA PRINCIPAL
    ============================ --}}
    <div class="col-lg-8">

        {{-- =====================
             INFORMACIÓN BÁSICA
        ====================== --}}
        <div class="form-section">
            <h5 class="form-section-title">Información del Incidente</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($incidente->fecha)->format('d/m/Y') }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Tipo:</div>
                <div class="detail-value">{{ $incidente->tipo_incidente }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Estado:</div>
                <div class="detail-value">
                    <span class="badge bg-primary">{{ $incidente->estado->nombre ?? 'Sin estado' }}</span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Descripción:</div>
                <div class="detail-value">{!! nl2br(e($incidente->descripcion)) !!}</div>
            </div>
        </div>


        {{-- ===========================
             INVOLUCRADOS
        ============================ --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Alumnos Involucrados</h5>

            <div class="row">

                {{-- VÍCTIMAS --}}
                <div class="col-md-4">
                    <h6>Víctimas</h6>
                    @forelse ($incidente->victimas as $v)
                        @if($v->alumno)
                            <div class="mb-1">
                                • {{ $v->alumno->apellido_paterno }} {{ $v->alumno->apellido_materno }}, {{ $v->alumno->nombre }}
                                <br><span class="text-muted small">{{ $v->alumno->curso->nombre ?? '' }}</span>
                            </div>
                        @endif
                    @empty
                        <p class="text-muted small">Sin víctimas registradas</p>
                    @endforelse
                </div>

                {{-- AGRESORES --}}
                <div class="col-md-4">
                    <h6>Agresores</h6>
                    @forelse ($incidente->agresores as $a)
                        @if($a->alumno)
                            <div class="mb-1">
                                • {{ $a->alumno->apellido_paterno }} {{ $a->alumno->apellido_materno }}, {{ $a->alumno->nombre }}
                                <br><span class="text-muted small">{{ $a->alumno->curso->nombre ?? '' }}</span>
                            </div>
                        @endif
                    @empty
                        <p class="text-muted small">Sin agresores registrados</p>
                    @endforelse
                </div>

                {{-- TESTIGOS --}}
                <div class="col-md-4">
                    <h6>Testigos</h6>
                    @forelse ($incidente->testigos as $t)
                        @if($t->alumno)
                            <div class="mb-1">
                                • {{ $t->alumno->apellido_paterno }} {{ $t->alumno->apellido_materno }}, {{ $t->alumno->nombre }}
                                <br><span class="text-muted small">{{ $t->alumno->curso->nombre ?? '' }}</span>
                            </div>
                        @endif
                    @empty
                        <p class="text-muted small">Sin testigos registrados</p>
                    @endforelse
                </div>

            </div>
        </div>


        {{-- ===========================
             FUNCIONARIO REPORTANTE
        ============================ --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Funcionario que Reporta</h5>

            <div class="detail-item">
                <div class="detail-label">Reportado por:</div>
                <div class="detail-value">
                    {{ $incidente->reportadoPor->apellido_paterno }}
                    {{ $incidente->reportadoPor->apellido_materno }},
                    {{ $incidente->reportadoPor->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Cargo:</div>
                <div class="detail-value">
                    {{ $incidente->reportadoPor->cargo->nombre ?? 'Sin cargo registrado' }}
                </div>
            </div>
        </div>


        {{-- ===========================
             SEGUIMIENTO EMOCIONAL
        ============================ --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Seguimiento Emocional</h5>

            @if($incidente->seguimiento)
                <div class="detail-item">
                    <div class="detail-label">Fecha:</div>
                    <div class="detail-value">{{ $incidente->seguimiento->fecha }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Comentario:</div>
                    <div class="detail-value">
                        {{ $incidente->seguimiento->comentario }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Evaluado por:</div>
                    <div class="detail-value">
                        {{ $incidente->seguimiento->evaluador->nombre ?? '' }}
                    </div>
                </div>
            @else
                <p class="text-muted">No existe seguimiento asociado.</p>
            @endif
        </div>

    </div>



    {{-- ===========================
         COLUMNA DERECHA
    ============================ --}}
    <div class="col-lg-4">

        {{-- ===========================
             DOCUMENTOS ADJUNTOS
        ============================ --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Documentos Adjuntos</h5>
            </div>

            <div class="card-body">
                @if($incidente->documentos->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach ($incidente->documentos as $doc)
                            <a href="{{ asset('storage/' . $doc->ruta_archivo) }}"
                               target="_blank"
                               class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-file-earmark me-2 text-primary"></i>
                                {{ $doc->nombre_archivo }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small">No hay documentos adjuntos.</p>
                @endif
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
                <form action="{{ route('bitacora.observaciones.store', $incidente->id) }}" method="POST" class="mb-3">
                    @csrf

                    <label class="form-label fw-bold">Agregar nueva observación</label>
                    <textarea name="observacion" class="form-control" rows="3" required></textarea>

                    <button type="submit" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Observación
                    </button>
                </form>

                <hr>

                {{-- LISTADO DE OBSERVACIONES --}}
                @forelse ($incidente->observaciones->sortBy('fecha_observacion') as $obs)
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
