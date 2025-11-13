@extends('layouts.app')

@section('title', 'Nuevo Plan Individual PIE')

@section('content')

<div class="page-header">
    <h1 class="page-title">Nuevo Plan Individual PIE</h1>
    <p class="text-muted">Crear un plan individual para un estudiante del Programa de Integración Escolar.</p>
</div>

<form action="{{ route('pie.planes.store') }}" method="POST">
    @csrf


    {{-- =============================
        ESTUDIANTE PIE
    ============================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Estudiante PIE</h5>

        <button type="button"
                class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarAlumnoPIE">
            <i class="bi bi-search"></i> Buscar Estudiante PIE
        </button>

        <input type="hidden" name="estudiante_pie_id" id="estudiante_pie_id" required>

        <p class="fw-bold" id="textoAlumnoSeleccionado" style="min-height:22px;">
            No se ha seleccionado estudiante.
        </p>
    </div>



    {{-- =============================
        FECHAS DEL PLAN
    ============================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Fechas del Plan</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                <input type="date" name="fecha_inicio" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha de Término</label>
                <input type="date" name="fecha_termino" class="form-control">
            </div>

        </div>
    </div>



    {{-- =============================
        OBJETIVOS DEL PLAN
    ============================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Objetivos del Plan</h5>

        <textarea name="objetivos" class="form-control" rows="4"
                  placeholder="Describa los objetivos principales del plan..."
                  required></textarea>
    </div>



    {{-- =============================
        EVALUACIÓN (opcional)
    ============================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Evaluación (opcional)</h5>

        <textarea name="evaluacion" class="form-control" rows="4"
                  placeholder="Ingrese evaluaciones o seguimiento del plan..."></textarea>
    </div>



    {{-- BOTONES --}}
    <div class="d-flex gap-2 flex-wrap mt-4">

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Plan
        </button>

        <a href="{{ route('pie.planes.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>

    </div>

</form>



{{-- ===========================
    MODAL SOLO DE ALUMNO
=========================== --}}
@include('modulos.pie.partials.modal-buscar-estudiante-pie')


{{-- ===========================
    JS COMPATIBLE CON TUS PARTIALS
=========================== --}}
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

});
</script>

@endsection
