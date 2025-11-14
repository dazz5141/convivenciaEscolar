@extends('layouts.app')

@section('title', 'Registrar Novedad')

@section('content')

<div class="page-header">
    <h1 class="page-title">Registrar Novedad de Inspectoría</h1>
    <p class="text-muted">Ingrese la información correspondiente a la novedad registrada.</p>
</div>

<form action="{{ route('inspectoria.novedades.store') }}" method="POST">
    @csrf

    {{-- =========================================================
         SECCIÓN 1: ALUMNO (OPCIONAL)
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno involucrado (opcional)</h5>

        <button type="button"
                class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarAlumno">
            <i class="bi bi-search"></i> Buscar Alumno
        </button>

        <input type="hidden" name="alumno_id" id="alumno_id">

        <p class="fw-bold" id="textoAlumnoSeleccionado" style="min-height: 22px;">
            No se ha seleccionado alumno.
        </p>
    </div>


    {{-- =========================================================
         SECCIÓN 2: TIPO DE NOVEDAD
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Tipo de Novedad *</h5>

        <select name="tipo_novedad_id" class="form-select" required>
            <option value="">Seleccione...</option>
            @foreach ($tipos as $t)
                <option value="{{ $t->id }}">{{ $t->nombre }}</option>
            @endforeach
        </select>
    </div>


    {{-- =========================================================
         SECCIÓN 3: INFORMACIÓN DE LA NOVEDAD
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información de la Novedad</h5>

        <div class="row g-3">

            {{-- FECHA --}}
            <div class="col-md-4">
                <label class="form-label">Fecha y hora <span class="text-danger">*</span></label>
                <input type="datetime-local"
                       name="fecha"
                       class="form-control"
                       required>
            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="col-12">
                <label class="form-label">Descripción <span class="text-danger">*</span></label>
                <textarea
                    name="descripcion"
                    class="form-control"
                    rows="4"
                    placeholder="Describa la situación ocurrida..."
                    required></textarea>
            </div>

        </div>
    </div>


    {{-- =========================================================
         BOTONES
    ========================================================== --}}
    <div class="d-flex gap-2 flex-wrap mt-4">

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Registrar Novedad
        </button>

        <a href="{{ route('inspectoria.novedades.index') }}" class="btn btn-secondary">
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
     MODAL BUSCAR ALUMNO
========================================================= --}}
@include('modulos.inspectoria.partials.modal-buscar-alumno')


{{-- =========================================================
     JS DE SELECCIÓN DE ALUMNO
========================================================= --}}
<script>
document.addEventListener('click', function(e) {

    if (e.target.classList.contains('seleccionar-alumno')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let cursoNombre = e.target.dataset.curso;
        let cursoId = e.target.dataset.cursoId;

        document.getElementById('alumno_id').value = id;
        document.getElementById('curso_id').value = cursoId;

        document.getElementById('textoAlumnoSeleccionado').textContent =
            `${nombre} (${cursoNombre})`;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarAlumno')
        ).hide();
    }
});
</script>

@endsection
