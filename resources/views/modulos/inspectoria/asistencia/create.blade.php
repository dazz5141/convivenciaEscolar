@extends('layouts.app')

@section('title', 'Registrar Asistencia / Atraso')

@section('content')

<div class="page-header">
    <h1 class="page-title">Registrar Evento de Asistencia</h1>
    <p class="text-muted">
        Registre atrasos, inasistencias, justificadas o retiros anticipados.
    </p>
</div>

<form action="{{ route('inspectoria.asistencia.store') }}" method="POST">
    @csrf

    {{-- =========================================================
         SECCIÓN 1: ALUMNO (OBLIGATORIO)
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno *</h5>

        <button type="button"
                class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarAlumno">
            <i class="bi bi-search"></i> Buscar Alumno
        </button>

        <input type="hidden" name="alumno_id" id="alumno_id">

        <p class="fw-bold" id="textoAlumnoSeleccionado" style="min-height: 22px;">
            No se ha seleccionado un alumno.
        </p>
    </div>


    {{-- =========================================================
         SECCIÓN 2: TIPO DE ASISTENCIA
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Tipo de Asistencia *</h5>

        <select name="tipo_id" class="form-select" required>
            <option value="">Seleccione...</option>
            @foreach ($tipos as $t)
                <option value="{{ $t->id }}">{{ $t->nombre }}</option>
            @endforeach
        </select>
    </div>


    {{-- =========================================================
         SECCIÓN 3: INFORMACIÓN DEL EVENTO
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información del Evento</h5>

        <div class="row g-3">

            {{-- FECHA --}}
            <div class="col-md-4">
                <label class="form-label">Fecha *</label>
                <input type="date"
                       name="fecha"
                       value="{{ date('Y-m-d') }}"
                       class="form-control"
                       required>
            </div>

            {{-- HORA (solo para atrasos o retiros) --}}
            <div class="col-md-4">
                <label class="form-label">Hora</label>
                <input type="time"
                       name="hora"
                       class="form-control">
            </div>

            {{-- OBSERVACIONES --}}
            <div class="col-12">
                <label class="form-label">Observaciones</label>
                <textarea
                    name="observaciones"
                    class="form-control"
                    rows="3"
                    placeholder="Observaciones adicionales (opcional)..."></textarea>
            </div>

        </div>
    </div>


    {{-- =========================================================
         BOTONES
    ========================================================== --}}
    <div class="d-flex gap-2 flex-wrap mt-4">

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Registrar Evento
        </button>

        <a href="{{ route('inspectoria.asistencia.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>

    </div>

</form>


{{-- =========================================================
     ERRORES DE VALIDACIÓN
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
     MODAL SELECCIONAR ALUMNO
========================================================= --}}
@include('modulos.inspectoria.partials.modal-buscar-alumno')


{{-- =========================================================
     JS SELECCIÓN DE ALUMNO
========================================================= --}}
<script>
document.addEventListener('click', function(e) {

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
});
</script>

@endsection
