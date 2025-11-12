@extends('layouts.app')

@section('title', 'Ficha Estudiante PIE')

@section('content')

<div class="page-header d-flex justify-content-between flex-wrap align-items-center">
    <div class="mb-2">
        <h1 class="page-title">
            Estudiante PIE: {{ $estudiantePIE->alumno->apellido_paterno }}
            {{ $estudiantePIE->alumno->apellido_materno }},
            {{ $estudiantePIE->alumno->nombre }}
        </h1>

        <p class="text-muted">
            Información completa del alumno en el Programa de Integración Escolar
        </p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        {{-- Volver --}}
        <a href="{{ route('pie.estudiantes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver
        </a>

        {{-- Historial --}}
        <a href="{{ route('pie.historial.index', $estudiantePIE->id) }}" class="btn btn-primary">
            <i class="bi bi-clock-history me-1"></i> Historial
        </a>

        {{-- Egreso --}}
        @if(!$estudiantePIE->fecha_egreso)
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEgreso">
                <i class="bi bi-box-arrow-right me-2"></i> Egresar del PIE
            </button>
        @endif

    </div>
</div>



<div class="row g-4">

    {{-- ===========================
         COLUMNA PRINCIPAL
    ============================ --}}
    <div class="col-lg-8">

        {{-- ===========================
             INFORMACIÓN DEL ALUMNO
        ============================ --}}
        <div class="form-section">
            <h5 class="form-section-title">Información del Alumno</h5>

            <div class="detail-item">
                <div class="detail-label">Nombre Completo:</div>
                <div class="detail-value">
                    {{ $estudiantePIE->alumno->apellido_paterno }}
                    {{ $estudiantePIE->alumno->apellido_materno }},
                    {{ $estudiantePIE->alumno->nombre }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">RUN:</div>
                <div class="detail-value">
                    {{ $estudiantePIE->alumno->run }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Curso:</div>
                <div class="detail-value">
                    {{ $estudiantePIE->alumno->curso->nombre ?? '—' }}
                </div>
            </div>
        </div>


        {{-- ===========================
             DATOS PIE
        ============================ --}}
        <div class="form-section mt-4">
            <h5 class="form-section-title">Datos PIE</h5>

            <div class="detail-item">
                <div class="detail-label">Fecha de Ingreso:</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($estudiantePIE->fecha_ingreso)->format('d/m/Y') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Diagnóstico:</div>
                <div class="detail-value">
                    {{ $estudiantePIE->diagnostico ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Observaciones:</div>
                <div class="detail-value">
                    {!! nl2br(e($estudiantePIE->observaciones ?? '—')) !!}
                </div>
            </div>

            @if($estudiantePIE->fecha_egreso)
                <div class="alert alert-warning mt-3">
                    <strong>Egresado del PIE:</strong>
                    {{ \Carbon\Carbon::parse($estudiantePIE->fecha_egreso)->format('d/m/Y') }}
                </div>
            @endif
        </div>
    </div>



    {{-- ===========================
         COLUMNA DERECHA
    ============================ --}}
    <div class="col-lg-4">

        {{-- ===========================
             INTERVENCIONES
        ============================ --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Intervenciones del Estudiante</h5>
            </div>

            <div class="card-body">

                @forelse($estudiantePIE->intervenciones as $i)
                    <div class="p-2 mb-2 border rounded bg-light">
                        <strong>{{ $i->tipo->nombre }}</strong><br>

                        <span class="text-muted small">
                            {{ \Carbon\Carbon::parse($i->fecha)->format('d/m/Y') }}
                        </span>

                        <div class="mt-1">{{ $i->detalle }}</div>
                    </div>
                @empty
                    <p class="text-muted">No existen intervenciones registradas.</p>
                @endforelse

            </div>
        </div>

    </div>

</div>



{{-- ================================
      MODAL DE EGRESO DEL PIE
================================ --}}
<div class="modal fade" id="modalEgreso" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Egresar Estudiante del PIE</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('pie.estudiantes.egresar', $estudiantePIE->id) }}" method="POST">
        @csrf

        <div class="modal-body">

          <p class="text-muted">
            Confirme la fecha de egreso del estudiante
            <strong>
                {{ $estudiantePIE->alumno->apellido_paterno }}
                {{ $estudiantePIE->alumno->apellido_materno }},
                {{ $estudiantePIE->alumno->nombre }}
            </strong>.
          </p>

          <label class="form-label">Fecha de Egreso *</label>
          <input type="date" name="fecha_egreso" class="form-control" required>

          <label class="form-label mt-3">Observaciones</label>
          <textarea name="observaciones_egreso" class="form-control" rows="3"></textarea>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

          <button type="submit" class="btn btn-danger">
            <i class="bi bi-box-arrow-right me-2"></i> Confirmar Egreso
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection
