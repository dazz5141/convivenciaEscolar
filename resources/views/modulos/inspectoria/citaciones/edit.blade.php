@extends('layouts.app')

@section('title', 'Editar Citación')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Citación a Apoderado</h1>
</div>

<form action="{{ route('inspectoria.citaciones.update', $citacion) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- =========================================================
         SECCIÓN 1: ALUMNO (NO EDITABLE)
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno citado</h5>

        <p class="fw-bold">
            {{ $citacion->alumno->nombre_completo }}<br>
            <small class="text-muted">{{ $citacion->alumno->curso->nombre ?? '' }}</small>
        </p>
    </div>


    {{-- =========================================================
         SECCIÓN 2: APODERADO (EDITABLE CON MODAL)
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Apoderado citado (opcional)</h5>

        <button type="button"
                class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarApoderado">
            <i class="bi bi-search"></i> Cambiar Apoderado
        </button>

        <input type="hidden" name="apoderado_id" id="apoderado_id" value="{{ $citacion->apoderado_id }}">

        <p class="fw-bold" id="textoApoderadoSeleccionado">
            {{ $citacion->apoderado->nombre_completo ?? 'No se seleccionó apoderado' }}
        </p>

        <small class="text-muted">Si lo deja en blanco, la citación queda sin apoderado asociado.</small>
    </div>


    {{-- =========================================================
         SECCIÓN 3: FECHA Y HORA (EDITABLE)
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Fecha y hora de la citación</h5>

        <div class="col-md-4">
            <label class="form-label">Fecha y hora *</label>
            <input type="datetime-local"
                   class="form-control"
                   name="fecha_citacion"
                   value="{{ \Carbon\Carbon::parse($citacion->fecha_citacion)->format('Y-m-d\TH:i') }}"
                   required>
        </div>
    </div>


    {{-- =========================================================
         SECCIÓN 4: ESTADO
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Estado de la citación</h5>

        <select name="estado_id" class="form-select" required>
            @foreach ($estados as $e)
                <option value="{{ $e->id }}"
                    {{ $citacion->estado_id == $e->id ? 'selected' : '' }}>
                    {{ $e->nombre }}
                </option>
            @endforeach
        </select>
    </div>


    {{-- =========================================================
         SECCIÓN 5: MOTIVO
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Motivo</h5>

        <textarea name="motivo"
                  class="form-control"
                  rows="3">{{ $citacion->motivo }}</textarea>
    </div>


    {{-- =========================================================
         SECCIÓN 6: OBSERVACIONES
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Observaciones</h5>

        <textarea name="observaciones"
                  class="form-control"
                  rows="3">{{ $citacion->observaciones }}</textarea>
    </div>


    {{-- =========================================================
         BOTONES
    ========================================================== --}}
    <div class="d-flex gap-2 mt-4">
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('inspectoria.citaciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>


{{-- MODAL APODERADO --}}
@include('modulos.inspectoria.partials.modal-buscar-apoderado')


{{-- JS SELECCIÓN DE APODERADO --}}
<script>
document.addEventListener('click', function(e) {

    if (e.target.classList.contains('seleccionar-apoderado')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;

        document.getElementById('apoderado_id').value = id;
        document.getElementById('textoApoderadoSeleccionado').textContent = nombre;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarApoderado')
        ).hide();
    }
});
</script>

@endsection
