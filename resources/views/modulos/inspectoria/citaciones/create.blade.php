@extends('layouts.app')

@section('title', 'Registrar Citación')

@section('content')

<div class="page-header">
    <h1 class="page-title">Registrar Citación a Apoderado</h1>
    <p class="text-muted">Ingrese la información correspondiente a la citación.</p>
</div>

@include('components.alerts')

<form action="{{ route('inspectoria.citaciones.store') }}" method="POST">
    @csrf

    {{-- =========================================================
         SECCIÓN 1: ALUMNO (OBLIGATORIO)
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno citado *</h5>

        <button type="button"
                class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarAlumno">
            <i class="bi bi-search"></i> Buscar Alumno
        </button>

        <input type="hidden" name="alumno_id" id="alumno_id" required>

        <p class="fw-bold" id="textoAlumnoSeleccionado" style="min-height: 22px;">
            No se ha seleccionado alumno.
        </p>
    </div>


    {{-- =========================================================
         SECCIÓN 2: APODERADO (OPCIONAL CON MODAL)
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Apoderado citado (opcional)</h5>

        <button type="button"
                class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarApoderado">
            <i class="bi bi-search"></i> Buscar Apoderado
        </button>

        <input type="hidden" name="apoderado_id" id="apoderado_id">

        <p class="fw-bold" id="textoApoderadoSeleccionado" style="min-height: 22px;">
            No se ha seleccionado apoderado.
        </p>

        <small class="text-muted">Si no selecciona uno, la citación queda sin apoderado asociado.</small>
    </div>


    {{-- =========================================================
         SECCIÓN 3: FECHA Y HORA
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Fecha y hora de la citación *</h5>

        <div class="col-md-4">
            <label class="form-label">Fecha y hora</label>
            <input type="datetime-local"
                   name="fecha_citacion"
                   class="form-control"
                   required>
        </div>
    </div>


    {{-- =========================================================
         SECCIÓN 4: ESTADO DE LA CITACIÓN
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Estado de la citación *</h5>

        <select name="estado_id" class="form-select" required>
            <option value="">Seleccione...</option>
            @foreach ($estados as $e)
                <option value="{{ $e->id }}">{{ $e->nombre }}</option>
            @endforeach
        </select>
    </div>


    {{-- =========================================================
         SECCIÓN 5: MOTIVO
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Motivo</h5>
        <textarea
            name="motivo"
            class="form-control"
            rows="3"
            placeholder="Indique el motivo de la citación..."></textarea>
    </div>


    {{-- =========================================================
         SECCIÓN 6: OBSERVACIONES
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Observaciones</h5>
        <textarea
            name="observaciones"
            class="form-control"
            rows="3"
            placeholder="Información adicional (opcional)..."></textarea>
    </div>


    {{-- =========================================================
         BOTONES
    ========================================================== --}}
    <div class="d-flex gap-2 flex-wrap mt-4">

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Registrar Citación
        </button>

        <a href="{{ route('inspectoria.citaciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>

    </div>

</form>


{{-- =========================================================
     ERRORES
========================================================= --}}
@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- =========================================================
     MODALS
========================================================= --}}

{{-- Modal Buscar Alumno --}}
@include('modulos.inspectoria.partials.modal-buscar-alumno')

{{-- Modal Buscar Apoderado --}}
@include('modulos.inspectoria.partials.modal-buscar-apoderado')


{{-- =========================================================
     JS SELECCIÓN DE ALUMNO Y APODERADO
========================================================= --}}
<script>
document.addEventListener('click', function(e) {

    // Selección de alumno
    if (e.target.classList.contains('seleccionar-alumno')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let curso = e.target.dataset.curso;

        document.getElementById('alumno_id').value = id;
        document.getElementById('textoAlumnoSeleccionado').textContent =
            `${nombre} (${curso})`;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarAlumno')
        ).hide();
    }

    // Selección de apoderado
    if (e.target.classList.contains('seleccionar-apoderado')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;

        document.getElementById('apoderado_id').value = id;
        document.getElementById('textoApoderadoSeleccionado').textContent =
            nombre;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarApoderado')
        ).hide();
    }
});
</script>

@endsection
