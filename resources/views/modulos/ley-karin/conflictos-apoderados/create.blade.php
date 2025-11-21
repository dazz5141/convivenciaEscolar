@extends('layouts.app')

@section('title', 'Registrar Conflicto con Apoderado')

@section('content')

<div class="page-header">
    <h1 class="page-title">Registrar Conflicto con Apoderado</h1>
    <p class="text-muted">Complete la información correspondiente al conflicto reportado.</p>
</div>

@include('components.alerts')

<form action="{{ route('leykarin.conflictos-apoderados.store') }}" method="POST">
    @csrf

    {{-- =========================================================
         SECCIÓN 1: FUNCIONARIO INVOLUCRADO
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Funcionario involucrado *</h5>

        <button type="button"
            class="btn btn-outline-primary mb-3 abrir-modal-funcionario"
            data-bs-toggle="modal"
            data-bs-target="#modalBuscarFuncionario"
            data-target="funcionario_id"
            data-texto="textoFuncionarioSeleccionado">
            <i class="bi bi-search"></i> Buscar Funcionario
        </button>

        <input type="hidden" name="funcionario_id" id="funcionario_id">

        <p class="fw-bold" id="textoFuncionarioSeleccionado" style="min-height: 22px;">
            No se ha seleccionado funcionario.
        </p>
    </div>



    {{-- =========================================================
         SECCIÓN 2: APODERADO INVOLUCRADO (HÍBRIDO)
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Apoderado involucrado *</h5>

        <div class="row g-3">

            {{-- SWITCH ENTRE INTERNO / EXTERNO --}}
            <div class="col-md-12">
                <label class="form-label fw-bold">Tipo de apoderado</label>
                <select id="tipo_apoderado" class="form-select">
                    <option value="interno">Apoderado registrado en el sistema</option>
                    <option value="externo">Apoderado externo (no registrado)</option>
                </select>
            </div>

            {{-- APODERADO INTERNO --}}
            <div class="col-md-12" id="div_apoderado_interno">
                <button type="button"
                    class="btn btn-outline-success mt-2"
                    data-bs-toggle="modal"
                    data-bs-target="#modalBuscarApoderado">
                    <i class="bi bi-search"></i> Buscar Apoderado
                </button>

                <input type="hidden" name="apoderado_id" id="apoderado_id">

                <p class="fw-bold mt-2" id="textoApoderadoSeleccionado" style="min-height: 22px;">
                    No se ha seleccionado apoderado.
                </p>
            </div>

            {{-- APODERADO EXTERNO --}}
            <div class="col-md-12 d-none" id="div_apoderado_externo">
                <label class="form-label">Nombre del apoderado *</label>
                <input type="text" name="apoderado_nombre" class="form-control"
                       placeholder="Nombre completo del apoderado">

                <label class="form-label mt-3">RUT del apoderado *</label>
                <input type="text" name="apoderado_rut" class="form-control"
                       placeholder="Ej: 12.345.678-9">
            </div>

        </div>
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
                       placeholder="Ej: Discusión, maltrato, reclamo..." required>
            </div>

            <div class="col-12">
                <label class="form-label">Lugar del conflicto</label>
                <input type="text" name="lugar_conflicto" class="form-control"
                       placeholder="Ej: Oficina, pasillo, sala de reuniones...">
            </div>

            <div class="col-12">
                <label class="form-label">Descripción del conflicto <span class="text-danger">*</span></label>
                <textarea name="descripcion" class="form-control" rows="4"
                          placeholder="Describa lo ocurrido..." required></textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Acción tomada</label>
                <textarea name="accion_tomada" class="form-control" rows="3"
                          placeholder="Mediación, informe, derivación..."></textarea>
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

        <a href="{{ route('leykarin.conflictos-apoderados.index') }}" class="btn btn-secondary">
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



{{-- MODAL FUNCIONARIOS --}}
@include('modulos.ley-karin.partials.modal-buscar-funcionario')

{{-- MODAL APODERADOS INTERNOS --}}
@include('modulos.ley-karin.partials.modal-buscar-apoderado')



{{-- =========================================================
     JS – CONTROL HÍBRIDO APODERADOS + FUNCIONARIOS
========================================================= --}}
<script>

let targetInput = null;
let targetTexto = null;

/* ===============================
   Selección de funcionario
================================= */
document.querySelectorAll('.abrir-modal-funcionario').forEach(btn => {
    btn.addEventListener('click', () => {
        targetInput = btn.dataset.target;
        targetTexto = btn.dataset.texto;
    });
});

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


/* ===============================
   Apoderado interno o externo
================================= */
document.getElementById('tipo_apoderado').addEventListener('change', function() {
    let tipo = this.value;

    document.getElementById('div_apoderado_interno').classList.toggle('d-none', tipo !== 'interno');
    document.getElementById('div_apoderado_externo').classList.toggle('d-none', tipo !== 'externo');
});


/* ===============================
   Selección de apoderado interno
================================= */
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('seleccionar-apoderado')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let rut = e.target.dataset.rut;

        document.getElementById('apoderado_id').value = id;
        document.getElementById('textoApoderadoSeleccionado').textContent = `${nombre} (${rut})`;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarApoderado')
        ).hide();
    }
});
</script>

@endsection
