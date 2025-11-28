@extends('layouts.app')

@section('title', 'Nuevo Profesional PIE')

@section('content')

<div class="page-header">
    <h1 class="page-title">Nuevo Profesional PIE</h1>
    <p class="text-muted">Asignar un funcionario al Programa de Integración Escolar</p>
</div>

@include('components.alerts')

<form action="{{ route('pie.profesionales.store') }}" method="POST">
    @csrf

    {{-- =========================================================
         SECCIÓN 1: FUNCIONARIO
    ========================================================== --}}
    @if(canAccess('pie','create'))
    <div class="form-section">
        <h5 class="form-section-title">Funcionario</h5>

        <button type="button"
                class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarFuncionario">
            <i class="bi bi-search"></i> Buscar Funcionario
        </button>

        <input type="hidden" name="funcionario_id" id="inputFuncionarioSeleccionado" required>

        <p class="fw-bold" id="textoFuncionarioSeleccionado">
            Ningún funcionario seleccionado.
        </p>
    </div>


    {{-- =========================================================
         SECCIÓN 2: TIPO PROFESIONAL PIE
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Tipo Profesional PIE</h5>

        <select name="tipo_id" class="form-select" required>
            <option value="">Seleccione...</option>
            @foreach ($tipos as $t)
                <option value="{{ $t->id }}">{{ $t->nombre }}</option>
            @endforeach
        </select>
    </div>


    {{-- =========================================================
         BOTONES
    ========================================================== --}}
    <div class="d-flex gap-2 flex-wrap mt-4">
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Profesional
        </button>

        <a href="{{ route('pie.profesionales.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

    @else
        <div class="alert alert-warning mt-3">
            <i class="bi bi-exclamation-triangle me-2"></i>
            No tienes permisos para registrar profesionales PIE.
        </div>
    @endif

</form>

{{-- MODAL --}}
@include('modulos.pie.partials.modal-buscar-funcionario')

{{-- =========================================================
     JS SELECCIÓN FUNCIONARIO
========================================================= --}}
<script>
document.addEventListener('click', function(e) {

    if (e.target.classList.contains('seleccionar-funcionario')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let cargo  = e.target.dataset.cargo ?? '';

        document.getElementById('inputFuncionarioSeleccionado').value = id;
        document.getElementById('textoFuncionarioSeleccionado').textContent =
            `${nombre} ${cargo ? '('+cargo+')' : ''}`;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarFuncionario')
        ).hide();
    }
});
</script>

@endsection
