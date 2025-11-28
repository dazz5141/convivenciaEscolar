@extends('layouts.app')

@section('title', 'Nueva Derivación PIE')

@section('content')

{{-- ============================================================
     PERMISO: CREAR
============================================================ --}}
@if(!canAccess('pie','create'))
    <div class="alert alert-danger mt-3">
        <i class="bi bi-x-circle me-2"></i>
        No tienes permisos para registrar derivaciones PIE.
    </div>
    @return
@endif


<div class="page-header">
    <h1 class="page-title">Nueva Derivación PIE</h1>
    <p class="text-muted">Registrar una derivación asociada a un estudiante del Programa de Integración Escolar.</p>
</div>

@include('components.alerts')

<form action="{{ route('pie.derivaciones.store') }}" method="POST">
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

        <p class="fw-bold" id="textoAlumnoSeleccionado" style="min-height: 22px;">
            No se ha seleccionado estudiante.
        </p>
    </div>


    {{-- =============================
        FECHA Y DESTINO
    ============================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información de la Derivación</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date" name="fecha" class="form-control" required>
            </div>

            <div class="col-md-8">
                <label class="form-label">Destino <span class="text-danger">*</span></label>
                <input type="text" name="destino" class="form-control"
                       placeholder="Ej: Psicólogo externo, fonoaudiólogo, UTP, etc."
                       required>
            </div>

        </div>
    </div>


    {{-- =============================
        MOTIVO
    ============================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Motivo de la Derivación</h5>

        <textarea name="motivo" class="form-control" rows="4"
                  placeholder="Describa el motivo de la derivación..." required></textarea>
    </div>


    {{-- =============================
        ESTADO
    ============================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Estado (opcional)</h5>

        <select name="estado" class="form-select">
            <option value="">Seleccione...</option>
            <option value="En curso">En curso</option>
            <option value="Cerrada">Cerrada</option>
            <option value="Pendiente">Pendiente</option>
        </select>
    </div>


    {{-- BOTONES --}}
    <div class="d-flex gap-2 flex-wrap mt-4">

        {{-- PERMISO: CREAR --}}
        @if(canAccess('pie','create'))
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i> Guardar Derivación
            </button>
        @endif

        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>

    </div>

</form>


{{-- ===========================
    MODAL SOLO DE ALUMNOS
=========================== --}}
@include('modulos.pie.partials.modal-buscar-estudiante-pie')


{{-- ===========================
    JS PARA SELECCIÓN DE ALUMNO
=========================== --}}
<script>
document.addEventListener('click', function(e){

    if (e.target.classList.contains('seleccionar-estudiante-pie')) {

        let id     = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let curso  = e.target.dataset.curso;

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
