@extends('layouts.app')

@section('title', 'Nueva Intervención PIE')

@section('content')

<div class="page-header">
    <h1 class="page-title">Nueva Intervención PIE</h1>
    <p class="text-muted">Registre una intervención realizada a un estudiante del Programa de Integración Escolar.</p>
</div>

@include('components.alerts')

{{-- =========================================================
     VALIDAR PERMISOS
========================================================= --}}
@if(!canAccess('intervenciones','create'))
    <div class="alert alert-warning mt-3">
        <i class="bi bi-exclamation-triangle me-2"></i>
        No tienes permisos para registrar intervenciones PIE.
    </div>
    @return
@endif


<form action="{{ route('pie.intervenciones.store') }}" method="POST">
    @csrf

    {{-- =========================================================
         SECCIÓN 1: ESTUDIANTE PIE
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Estudiante PIE</h5>

        <button type="button"
                class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarAlumnoPIE">
            <i class="bi bi-search"></i> Buscar Estudiante PIE
        </button>

        <input type="hidden" name="estudiante_pie_id" id="estudiante_pie_id" required>

        <p class="fw-bold" id="textoAlumnoSeleccionado" style="min-height: 22px;">
            No se ha seleccionado estudiante.
        </p>
    </div>



    {{-- =========================================================
         SECCIÓN 2: PROFESIONAL PIE
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Profesional PIE</h5>

        <button type="button"
                class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarProfesionalPIE">
            <i class="bi bi-search"></i> Buscar Profesional PIE
        </button>

        <input type="hidden" name="profesional_id" id="profesional_id" required>

        <p class="fw-bold" id="textoFuncionarioSeleccionado" style="min-height: 22px;">
            No se ha seleccionado profesional.
        </p>
    </div>



    {{-- =========================================================
         SECCIÓN 3: TIPO DE INTERVENCIÓN
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Tipo de Intervención</h5>

        <select name="tipo_intervencion_id" class="form-select" required>
            <option value="">Seleccione...</option>
            @foreach ($tipos as $t)
                <option value="{{ $t->id }}">{{ $t->nombre }}</option>
            @endforeach
        </select>
    </div>



    {{-- =========================================================
         SECCIÓN 4: FECHA Y DETALLE
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Detalle de la Intervención</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date"
                       name="fecha"
                       class="form-control"
                       required>
            </div>

            <div class="col-12">
                <label class="form-label">Detalle</label>
                <textarea name="detalle"
                          class="form-control"
                          rows="4"
                          placeholder="Describa la intervención realizada..."></textarea>
            </div>

        </div>
    </div>



    {{-- =========================================================
         BOTONES
    ========================================================== --}}
    <div class="d-flex gap-2 flex-wrap mt-4">

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Intervención
        </button>

        <a href="{{ route('pie.intervenciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>

    </div>

</form>

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
     INCLUIR MODALES
========================================================= --}}
@include('modulos.pie.partials.modal-buscar-estudiante-pie')
@include('modulos.pie.partials.modal-buscar-profesional-pie') 


{{-- =========================================================
     JAVASCRIPT DE SELECCIÓN
========================================================= --}}
<script>
document.addEventListener('click', function(e) {

    // ============================
    // Seleccionar Estudiante PIE
    // ============================
    if (e.target.classList.contains('seleccionar-estudiante-pie')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let curso = e.target.dataset.curso;

        document.getElementById('estudiante_pie_id').value = id;

        document.getElementById('textoAlumnoSeleccionado').textContent =
            `${nombre} (${curso})`;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarAlumnoPIE')
        ).hide();
    }


    // ============================
    // Seleccionar Profesional PIE
    // ============================
    if (e.target.classList.contains('seleccionar-profesional-pie')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let cargo = e.target.dataset.cargo;

        document.getElementById('profesional_id').value = id;

        document.getElementById('textoFuncionarioSeleccionado').textContent =
            `${nombre} (${cargo})`;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarProfesionalPIE')
        ).hide();
    }
});
</script>

@endsection
