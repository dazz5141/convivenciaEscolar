@extends('layouts.app')

@section('title', 'Registrar Accidente Escolar')

@section('content')

<div class="page-header">
    <h1 class="page-title">Registrar Accidente Escolar</h1>
    <p class="text-muted">Complete la información del accidente ocurrido a un estudiante.</p>
</div>

<form action="{{ route('inspectoria.accidentes.store') }}" method="POST">
    @csrf


    {{-- =========================================================
         SECCIÓN 1: ALUMNO INVOLUCRADO
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno Involucrado</h5>

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
         SECCIÓN 2: TIPO DE ACCIDENTE
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Tipo de Accidente</h5>

        <select name="tipo_accidente_id" class="form-select" required>
            <option value="">Seleccione...</option>
            @foreach ($tipos as $t)
                <option value="{{ $t->id }}">{{ $t->nombre }}</option>
            @endforeach
        </select>
    </div>



    {{-- =========================================================
         SECCIÓN 3: INFORMACIÓN DEL ACCIDENTE
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información del Accidente</h5>

        <div class="row g-3">

            {{-- FECHA --}}
            <div class="col-md-4">
                <label class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="datetime-local"
                       name="fecha"
                       class="form-control"
                       required>
            </div>

            {{-- LUGAR --}}
            <div class="col-md-8">
                <label class="form-label">Lugar</label>
                <input type="text"
                       name="lugar"
                       class="form-control"
                       placeholder="Ej: Patio, sala de clases, pasillo...">
            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="col-12">
                <label class="form-label">Descripción del Accidente <span class="text-danger">*</span></label>
                <textarea name="descripcion"
                          class="form-control"
                          rows="4"
                          placeholder="Describa cómo ocurrió el accidente..."
                          required></textarea>
            </div>

            {{-- ATENCIÓN INMEDIATA --}}
            <div class="col-12">
                <label class="form-label">Atención Inmediata</label>
                <textarea name="atencion_inmediata"
                          class="form-control"
                          rows="3"
                          placeholder="Indique si se aplicó hielo, curación, desinfección, etc."></textarea>
            </div>

            {{-- DERIVACIÓN A SALUD --}}
            <div class="col-md-4">
                <label class="form-label">¿Derivado a centro de salud?</label>
                <select name="derivacion_salud" class="form-select">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
            </div>

        </div>
    </div>



    {{-- =========================================================
         BOTONES
    ========================================================== --}}
    <div class="d-flex gap-2 flex-wrap mt-4">

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Accidente
        </button>

        <a href="{{ route('inspectoria.accidentes.index') }}" class="btn btn-secondary">
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
     MODAL BUSCAR ALUMNO (ya existe en tu sistema)
========================================================= --}}
@include('modulos.inspectoria.partials.modal-buscar-alumno')



{{-- =========================================================
     JAVASCRIPT DE SELECCIÓN DE ALUMNO
========================================================= --}}
<script>
document.addEventListener('click', function(e) {

    // ============================
    // Seleccionar alumno desde modal
    // ============================
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
