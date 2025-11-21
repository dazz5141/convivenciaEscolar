@extends('layouts.app')

@section('title', 'Nuevo Estudiante PIE')

@section('content')

<div class="page-header mb-3">
    <h1 class="page-title">Nuevo Estudiante PIE</h1>
    <p class="text-muted">Incorporar un alumno al Programa de Integración Escolar</p>
</div>

@include('components.alerts')

<form action="{{ route('pie.estudiantes.store') }}" method="POST">
    @csrf

    {{-- =======================
        ALUMNO
    ======================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno</h5>

        <button type="button" class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal" data-bs-target="#modalBuscarAlumno">
            <i class="bi bi-search"></i> Buscar Alumno
        </button>

        <input type="hidden" name="alumno_id" id="alumno_id" required>

        <p class="fw-bold" id="textoAlumnoSeleccionado">
            Ningún alumno seleccionado.
        </p>
    </div>



    {{-- =======================
        PROFESIONAL PIE (Responsable)
        *Este campo NO existe en la tabla estudiantes_pie
        *Lo eliminamos para evitar inconsistencias
    ======================== --}}
    {{-- ELIMINADO --}}



    {{-- =======================
        INFORMACIÓN DEL PIE
    ======================== --}}
    <div class="form-section mt-4">

        <h5 class="form-section-title">Información del Estudiante PIE</h5>

        <div class="row g-3">

            {{-- FECHA DE INGRESO (Obligatoria) --}}
            <div class="col-md-4">
                <label class="form-label">Fecha de Ingreso al PIE *</label>
                <input type="date" name="fecha_ingreso" class="form-control" required>
            </div>

            {{-- DIAGNÓSTICO --}}
            <div class="col-md-8">
                <label class="form-label">Diagnóstico</label>
                <input type="text" name="diagnostico" class="form-control"
                       placeholder="Ej: TEL, Disfasia, dificultades...">
            </div>

            {{-- OBSERVACIONES --}}
            <div class="col-12">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3"
                          placeholder="Comentarios adicionales..."></textarea>
            </div>

        </div>

    </div>



    {{-- BOTONES --}}
    <div class="d-flex gap-2 flex-wrap mt-4">

        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Estudiante
        </button>

        <a href="{{ route('pie.estudiantes.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>

    </div>

</form>



{{-- ===========================
    MODALES
=========================== --}}
@include('modulos.pie.partials.modal-buscar-alumno')
@include('modulos.pie.partials.modal-buscar-funcionario')



{{-- ===========================
     JS DE SELECCIÓN
=========================== --}}
<script>

document.addEventListener('click', function(e){

    // Selección de alumno
    if (e.target.classList.contains('seleccionar-alumno')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let curso = e.target.dataset.curso;

        document.getElementById('alumno_id').value = id;
        document.getElementById('textoAlumnoSeleccionado').textContent =
            `${nombre} (${curso})`;

        bootstrap.Modal.getInstance(document.getElementById('modalBuscarAlumno')).hide();
    }

});

</script>

@endsection
