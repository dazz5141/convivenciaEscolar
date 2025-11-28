@extends('layouts.app')

@section('title', 'Registrar Denuncia Ley Karin')

@section('content')

<div class="page-header">
    <h1 class="page-title">Registrar Denuncia Ley Karin</h1>
    <p class="text-muted">Complete la información correspondiente a la denuncia ingresada.</p>
</div>

@include('components.alerts')

<form action="{{ route('leykarin.denuncias.store') }}" method="POST">
    @csrf

    {{-- =========================================================
         CONFLICTO ASOCIADO
    ========================================================== --}}
    <div class="form-section mb-4">
        <h5 class="form-section-title">Conflicto Asociado (opcional)</h5>

        <button type="button"
            class="btn btn-outline-dark abrir-modal-conflicto"
            data-bs-toggle="modal"
            data-bs-target="#modalConflictos"
            data-input="conflictable_id"
            data-texto="textoConflictoSeleccionado">
            <i class="bi bi-link-45deg"></i> Seleccionar Conflicto
        </button>

        <input type="hidden" name="conflictable_type" id="conflictable_type">
        <input type="hidden" name="conflictable_id" id="conflictable_id">

        <p class="fw-bold mt-2 text-primary" id="textoConflictoSeleccionado">
            No se ha seleccionado un conflicto.
        </p>
    </div>


    {{-- =========================================================
         DATOS DEL DENUNCIANTE
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Denunciante *</h5>

        <div class="d-flex gap-2 flex-wrap mb-3">

            {{-- FUNCIONARIO --}}
            <button type="button"
                class="btn btn-outline-primary abrir-modal-funcionario"
                data-bs-toggle="modal"
                data-bs-target="#modalFuncionarioLK"
                data-input="denunciante_id"
                data-texto="textoDenunciante">
                <i class="bi bi-person-vcard"></i> Funcionario
            </button>

            {{-- APODERADO --}}
            <button type="button"
                class="btn btn-outline-success abrir-modal-apoderado"
                data-bs-toggle="modal"
                data-bs-target="#modalApoderadoLK"
                data-input="denunciante_id"
                data-texto="textoDenunciante">
                <i class="bi bi-person-lines-fill"></i> Apoderado
            </button>

        </div>

        <input type="hidden" name="denunciante_id" id="denunciante_id">

        <p class="fw-bold" id="textoDenunciante">No se ha seleccionado un denunciante.</p>
    </div>


    {{-- =========================================================
         DATOS DEL DENUNCIADO
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Denunciado *</h5>

        <div class="d-flex gap-2 flex-wrap mb-3">

            {{-- FUNCIONARIO --}}
            <button type="button"
                class="btn btn-outline-danger abrir-modal-funcionario"
                data-bs-toggle="modal"
                data-bs-target="#modalFuncionarioLK"
                data-input="denunciado_id"
                data-texto="textoDenunciado">
                <i class="bi bi-person-x"></i> Funcionario
            </button>

            {{-- APODERADO --}}
            <button type="button"
                class="btn btn-outline-warning abrir-modal-apoderado"
                data-bs-toggle="modal"
                data-bs-target="#modalApoderadoLK"
                data-input="denunciado_id"
                data-texto="textoDenunciado">
                <i class="bi bi-person-fill-exclamation"></i> Apoderado
            </button>

        </div>

        <input type="hidden" name="denunciado_id" id="denunciado_id">

        <p class="fw-bold" id="textoDenunciado">No se ha seleccionado un denunciado.</p>
    </div>


    {{-- =========================================================
         INFORMACIÓN DE LA DENUNCIA
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información de la Denuncia</h5>

        <div class="row g-3">

            {{-- FECHA --}}
            <div class="col-md-4">
                <label class="form-label">Fecha de la denuncia <span class="text-danger">*</span></label>
                <input type="date" name="fecha_denuncia" class="form-control" required>
            </div>

            {{-- TIPO --}}
            <div class="col-md-6">
                <label class="form-label">Tipo de denuncia <span class="text-danger">*</span></label>
                <select name="tipo_denuncia_id" class="form-select" required>
                    <option value="">Seleccione tipo...</option>
                    @foreach ($tipos as $t)
                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="col-12">
                <label class="form-label">Descripción detallada <span class="text-danger">*</span></label>
                <textarea name="descripcion" class="form-control" rows="5"
                    placeholder="Describa detalladamente lo ocurrido..." required></textarea>
            </div>

        </div>
    </div>


    {{-- BOTONES --}}
    <div class="d-flex gap-2 flex-wrap mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Registrar Denuncia
        </button>

        <a href="{{ route('leykarin.denuncias.index') }}" class="btn btn-secondary">
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


{{-- MODALES --}}
@include('modulos.ley-karin.partials.modal-buscar-funcionario-denuncias')
@include('modulos.ley-karin.partials.modal-buscar-apoderado-denuncias')
@include('modulos.ley-karin.partials.modal-buscar-conflicto-denuncias')


<script>
let inputObjetivo = null;
let textoObjetivo = null;

/* Asignar input + texto dinámico */
document.querySelectorAll('.abrir-modal-funcionario, .abrir-modal-apoderado, .abrir-modal-conflicto')
    .forEach(btn => {
        btn.addEventListener('click', () => {
            inputObjetivo = btn.dataset.input;
            textoObjetivo = btn.dataset.texto;
        });
    });

/* Seleccionar funcionario */
function seleccionarFuncionarioLK(id, nombre, cargo) {
    document.getElementById(inputObjetivo).value = id;
    document.getElementById(textoObjetivo).textContent = `${nombre} (${cargo})`;
}

/* Seleccionar apoderado */
function seleccionarApoderadoLK(id, nombre, run) {
    document.getElementById(inputObjetivo).value = id;
    document.getElementById(textoObjetivo).textContent = `${nombre} — RUN: ${run}`;
}

/* Seleccionar conflicto */
function seleccionarConflictoLK(type, id, descripcion) {
    document.getElementById('conflictable_type').value = type;
    document.getElementById(inputObjetivo).value = id;
    document.getElementById(textoObjetivo).textContent = descripcion;
}
</script>

@endsection
