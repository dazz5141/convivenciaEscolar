@extends('layouts.app')

@section('title', 'Registrar Medida Restaurativa')

@section('content')

<div class="page-header">
    <h1 class="page-title">Registrar Medida Restaurativa</h1>
    <p class="text-muted">Crear una acción restaurativa asociada a un incidente o directamente a un alumno.</p>
</div>

<form action="{{ route('convivencia.medidas.store') }}" method="POST">
    @csrf

    {{-- =========================================================
         SECCIÓN 1: ALUMNO
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno involucrado *</h5>

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
         SECCIÓN 2: TIPO Y ESTADO
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Detalles de la Medida</h5>

        <div class="row g-3">
            {{-- Tipo de Medida --}}
            <div class="col-md-6">
                <label class="form-label">Tipo de Medida *</label>
                <select name="tipo_medida_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($tipos as $t)
                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Estado de Cumplimiento --}}
            <div class="col-md-6">
                <label class="form-label">Estado de Cumplimiento *</label>
                <select name="cumplimiento_estado_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($estadosCumplimiento as $e)
                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>


    {{-- =========================================================
         SECCIÓN 3: RESPONSABLE
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Responsable de la Medida *</h5>

        <button type="button"
                class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarFuncionario">
            <i class="bi bi-search"></i> Buscar Funcionario
        </button>

        <input type="hidden" name="responsable_id" id="responsable_id">

        <p class="fw-bold" id="textoFuncionarioSeleccionado" style="min-height: 22px;">
            No se ha seleccionado responsable.
        </p>
    </div>


    {{-- =========================================================
         SECCIÓN 4: FECHAS
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Fechas</h5>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Fecha Fin</label>
                <input type="date" name="fecha_fin" class="form-control">
            </div>
        </div>
    </div>


    {{-- =========================================================
         SECCIÓN 5: OBSERVACIONES
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Observaciones</h5>
        <textarea name="observaciones" class="form-control" rows="4"
                  placeholder="Detalles adicionales sobre la medida..."></textarea>
    </div>


    {{-- BOTONES --}}
    <div class="d-flex gap-2 flex-wrap mt-4">
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Medida
        </button>

        <a href="{{ route('convivencia.medidas.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>
</form>


{{-- INCLUIMOS LOS MODALES --}}
@include('modulos.convivencia-escolar.partials.modal-buscar-alumno')
@include('modulos.convivencia-escolar.partials.modal-buscar-funcionario')


{{-- =========================================================
     JS PARA RECIBIR SELECCIÓN
========================================================= --}}
<script>
function agregarAlumnoSeleccionado(id, nombre, curso) {
    document.getElementById('alumno_id').value = id;
    document.getElementById('textoAlumnoSeleccionado').textContent =
        `${nombre} (${curso})`;
}

document.addEventListener('click', function(e){
    if (e.target.classList.contains('seleccionar-funcionario')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let cargo = e.target.dataset.cargo;

        document.getElementById('responsable_id').value = id;
        document.getElementById('textoFuncionarioSeleccionado').textContent =
            `${nombre} (${cargo})`;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarFuncionario')
        ).hide();
    }
});
</script>

@endsection
