@extends('layouts.app')

@section('title', 'Nuevo Informe PIE')

@section('content')

<div class="page-header">
    <h1 class="page-title">Nuevo Informe PIE</h1>
    <p class="text-muted">Crear un informe asociado a un estudiante del Programa de Integración Escolar.</p>
</div>

<form action="{{ route('pie.informes.store') }}" method="POST">
    @csrf


    {{-- =============================
         ESTUDIANTE PIE
    ============================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Estudiante PIE</h5>

        <button type="button"
                class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarAlumno">
            <i class="bi bi-search"></i> Buscar Estudiante
        </button>

        <input type="hidden" name="estudiante_pie_id" id="estudiante_pie_id" required>

        <p class="fw-bold" id="textoAlumnoSeleccionado">
            No se ha seleccionado estudiante.
        </p>
    </div>



    {{-- =============================
         TIPO DE INFORME
    ============================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Tipo de Informe</h5>

        <input type="text" name="tipo" class="form-control"
               placeholder="Ej: Informe técnico, informe anual, seguimiento, etc."
               required>
    </div>



    {{-- =============================
         FECHA DEL INFORME
    ============================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Fecha del Informe</h5>

        <input type="date" name="fecha" class="form-control" required>
    </div>



    {{-- =============================
         CONTENIDO
    ============================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Contenido del Informe</h5>

        <textarea name="contenido" class="form-control" rows="6"
                  placeholder="Escriba el contenido completo del informe..."
                  required></textarea>
    </div>



    {{-- BOTONES --}}
    <div class="d-flex gap-2 flex-wrap mt-4">

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Informe
        </button>

        <a href="{{ route('pie.informes.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>

    </div>

</form>



{{-- ===========================
     MODAL (SOLO ALUMNO)
=========================== --}}
@include('modulos.pie.partials.modal-buscar-alumno')



{{-- ===========================
     JS PARA SELECCIÓN
=========================== --}}
<script>
document.addEventListener('click', function(e) {

    // Seleccionar alumno (desde modal)
    if (e.target.classList.contains('seleccionar-alumno')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let curso = e.target.dataset.curso;

        document.getElementById('estudiante_pie_id').value = id;

        document.getElementById('textoAlumnoSeleccionado').textContent =
            `${nombre} (${curso})`;

        bootstrap.Modal.getInstance(document.getElementById('modalBuscarAlumno')).hide();
    }

});
</script>

@endsection
