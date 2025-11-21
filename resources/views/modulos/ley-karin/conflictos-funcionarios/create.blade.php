@extends('layouts.app')

@section('title', 'Registrar Conflicto entre Funcionarios')

@section('content')

<div class="page-header">
    <h1 class="page-title">Registrar Conflicto entre Funcionarios</h1>
    <p class="text-muted">Complete la información correspondiente al conflicto reportado.</p>
</div>

@include('components.alerts')

<form action="{{ route('leykarin.conflictos-funcionarios.store') }}" method="POST">
    @csrf

    {{-- =========================================================
         SECCIÓN 1: FUNCIONARIO DENUNCIANTE
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Funcionario denunciante *</h5>

        <button type="button"
            class="btn btn-outline-primary mb-3 abrir-modal-funcionario"
            data-bs-toggle="modal"
            data-bs-target="#modalBuscarFuncionario"
            data-target="funcionario_1_id"
            data-texto="textoFuncionario1Seleccionado">
            <i class="bi bi-search"></i> Buscar Funcionario
        </button>

        <input type="hidden" name="funcionario_1_id" id="funcionario_1_id">

        <p class="fw-bold" id="textoFuncionario1Seleccionado" style="min-height: 22px;">
            No se ha seleccionado funcionario.
        </p>
    </div>


    {{-- =========================================================
         SECCIÓN 2: FUNCIONARIO DENUNCIADO
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Funcionario denunciado *</h5>

        <button type="button"
            class="btn btn-outline-danger mb-3 abrir-modal-funcionario"
            data-bs-toggle="modal"
            data-bs-target="#modalBuscarFuncionario"
            data-target="funcionario_2_id"
            data-texto="textoFuncionario2Seleccionado">
            <i class="bi bi-search"></i> Buscar Funcionario
        </button>

        <input type="hidden" name="funcionario_2_id" id="funcionario_2_id">

        <p class="fw-bold" id="textoFuncionario2Seleccionado" style="min-height: 22px;">
            No se ha seleccionado funcionario.
        </p>
    </div>


    {{-- =========================================================
         SECCIÓN 3: INFORMACIÓN DEL CONFLICTO
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información del Conflicto</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Fecha del conflicto <span class="text-danger">*</span></label>
                <input type="date" name="fecha" class="form-control" required>
            </div>

            <div class="col-md-8">
                <label class="form-label">Tipo de conflicto <span class="text-danger">*</span></label>
                <input type="text" name="tipo_conflicto" class="form-control"
                       placeholder="Ej: Discusión, maltrato, desacuerdo laboral..." required>
            </div>

            <div class="col-12">
                <label class="form-label">Lugar del conflicto</label>
                <input type="text" name="lugar_conflicto" class="form-control"
                       placeholder="Ej: Oficina, sala de profesores, pasillo...">
            </div>

            <div class="col-12">
                <label class="form-label">Descripción detallada <span class="text-danger">*</span></label>
                <textarea name="descripcion" class="form-control" rows="4"
                          placeholder="Describa detalladamente lo ocurrido..." required></textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Acuerdos previos (si existen)</label>
                <textarea name="acuerdos_previos" class="form-control" rows="3"
                          placeholder="Ingrese información relevante adicional..."></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Estado del caso</label>
                <select name="estado_id" class="form-select">
                    <option value="">Seleccione...</option>
                    @foreach ($estados as $e)
                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Confidencial</label>
                <select name="confidencial" class="form-select">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Derivado Ley Karin</label>
                <select name="derivado_ley_karin" class="form-select">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
            </div>

        </div>
    </div>


    {{-- BOTONES --}}
    <div class="d-flex gap-2 flex-wrap mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Registrar Conflicto
        </button>

        <a href="{{ route('leykarin.conflictos-funcionarios.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>


{{-- ERRORES --}}
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
     MODAL (UNA SOLA – REUTILIZADA PARA AMBOS)
========================================================= --}}
@include('modulos.ley-karin.partials.modal-buscar-funcionario')


{{-- =========================================================
     JS PARA SELECCIÓN DE FUNCIONARIOS
========================================================= --}}
<script>

let targetInput = null;
let targetTexto = null;

// Cuando se abre la modal, identificamos qué botón la llamó
document.querySelectorAll('.abrir-modal-funcionario').forEach(btn => {
    btn.addEventListener('click', () => {
        targetInput = btn.dataset.target;
        targetTexto = btn.dataset.texto;
    });
});

// Cuando se selecciona un funcionario
document.addEventListener('click', function(e) {

    if (e.target.classList.contains('seleccionar-funcionario')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let cargo = e.target.dataset.cargo;

        document.getElementById(targetInput).value = id;
        document.getElementById(targetTexto).textContent = `${nombre} (${cargo})`;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarFuncionario')
        ).hide();
    }
});
</script>

@endsection
