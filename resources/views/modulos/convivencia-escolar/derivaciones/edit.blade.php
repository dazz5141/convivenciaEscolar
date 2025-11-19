@extends('layouts.app')

@section('title', 'Editar Derivación')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Derivación #{{ $derivacion->id }}</h1>
    <p class="text-muted">Actualizar datos de una derivación ya registrada</p>
</div>



<form action="{{ route('convivencia.derivaciones.update', $derivacion->id) }}" method="POST">
    @csrf
    @method('PUT')


    {{-- =========================================================
         SECCIÓN 1: DATOS DEL ALUMNO  (NO EDITABLE)
    ========================================================= --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno Derivado</h5>

        <p class="fw-bold mb-1">
            {{ $derivacion->alumno->nombre_completo }}
        </p>

        <span class="text-muted small">
            {{ $derivacion->alumno->curso->nombre ?? '' }}
        </span>
    </div>



    {{-- =========================================================
        SECCIÓN 2: ORIGEN DE LA DERIVACIÓN (NO EDITABLE)
    ========================================================= --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Origen del Caso</h5>

        @if($derivacion->entidad_type === \App\Models\MedidaRestaurativa::class)
            
            <p class="fw-bold mb-1">Medida Restaurativa</p>

            <a href="{{ route('convivencia.medidas.show', $derivacion->entidad_id) }}"
            class="btn btn-warning btn-sm">
                <i class="bi bi-eye"></i> Ver Medida
            </a>

        @elseif($derivacion->entidad_type === \App\Models\SeguimientoEmocional::class)

            <p class="fw-bold mb-1">Seguimiento Emocional</p>

            <a href="{{ route('convivencia.seguimiento.show', $derivacion->entidad_id) }}"
            class="btn btn-warning btn-sm">
                <i class="bi bi-eye"></i> Ver Seguimiento
            </a>

        @else

            <p class="text-muted small">Sin información del origen.</p>

        @endif
    </div>

    {{-- =========================================================
         SECCIÓN 3: INFORMACIÓN EDITABLE
    ========================================================= --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información de la Derivación</h5>

        <div class="row g-3">

            {{-- Fecha --}}
            <div class="col-md-4">
                <label class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date"
                       name="fecha"
                       class="form-control"
                       value="{{ \Carbon\Carbon::parse($derivacion->fecha)->format('Y-m-d') }}"
                       required>
            </div>

            {{-- Tipo --}}
            <div class="col-md-4">
                <label class="form-label">Tipo de derivación <span class="text-danger">*</span></label>
                <select name="tipo" class="form-select" required>
                    <option value="interna" {{ $derivacion->tipo == 'interna' ? 'selected' : '' }}>Interna</option>
                    <option value="externa" {{ $derivacion->tipo == 'externa' ? 'selected' : '' }}>Externa</option>
                </select>
            </div>

            {{-- Estado --}}
            <div class="col-md-4">
                <label class="form-label">Estado <span class="text-danger">*</span></label>
                <select name="estado" class="form-select" required>
                    <option value="Enviada"     {{ $derivacion->estado == 'Enviada' ? 'selected' : '' }}>Enviada</option>
                    <option value="En revisión" {{ $derivacion->estado == 'En revisión' ? 'selected' : '' }}>En revisión</option>
                    <option value="Cerrada"     {{ $derivacion->estado == 'Cerrada' ? 'selected' : '' }}>Cerrada</option>
                </select>
            </div>

            {{-- Destino --}}
            <div class="col-12">
                <label class="form-label">Destino <span class="text-danger">*</span></label>
                <input type="text"
                       name="destino"
                       class="form-control"
                       value="{{ $derivacion->destino }}"
                       required>
            </div>

            {{-- Motivo --}}
            <div class="col-12">
                <label class="form-label">Motivo</label>
                <textarea name="motivo"
                          class="form-control"
                          rows="4"
                          placeholder="Describa el motivo de la derivación...">{{ $derivacion->motivo }}</textarea>
            </div>

        </div>
    </div>



    {{-- =========================================================
         SECCIÓN 4: FUNCIONARIO (NO EDITABLE)
    ========================================================= --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Funcionario Responsable</h5>

        <p class="fw-bold mb-1">
            {{ $derivacion->funcionario->nombre }}
            {{ $derivacion->funcionario->apellido_paterno }}
        </p>

        <span class="text-muted small">
            (El funcionario no se puede modificar)
        </span>
    </div>



    {{-- =========================================================
         BOTONES
    ========================================================= --}}
    <div class="d-flex gap-2 flex-wrap mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>Guardar Cambios
        </button>

        <a href="{{ route('convivencia.derivaciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
    </div>


</form>

@endsection
