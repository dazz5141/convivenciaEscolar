@extends('layouts.app')

@section('title', 'Registrar Retiro Anticipado')

@section('content')

<div class="page-header">
    <h1 class="page-title">Registrar Retiro Anticipado</h1>
    <p class="text-muted">Complete la información correspondiente al retiro del alumno.</p>
</div>

@include('components.alerts')

{{-- =========================================================
     PERMISO: SOLO SI TIENE permiso create en retiros
========================================================= --}}
@if(canAccess('retiros', 'create'))

<form action="{{ route('inspectoria.retiros.store') }}" method="POST">
    @csrf

    {{-- =========================================================
         SECCIÓN 1: ALUMNO
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno a retirar *</h5>

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
         SECCIÓN 2: PERSONA QUE RETIRA
    ========================================================== --}}
    <div class="form-section mt-4">

        <h5 class="form-section-title">Persona que retira *</h5>

        <p class="text-muted small mb-2">
            Puede seleccionar un apoderado registrado o ingresar manualmente los datos del adulto que retira.
        </p>

        {{-- BOTÓN ABRIR MODAL APODERADO --}}
        <button type="button"
                class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarApoderado">
            <i class="bi bi-search"></i> Seleccionar Apoderado
        </button>

        <input type="hidden" name="apoderado_id" id="apoderado_id">

        <p class="fw-bold" id="textoApoderadoSeleccionado" style="min-height: 22px;">
            Ningún apoderado seleccionado.
        </p>

        <button type="button"
                id="btnLimpiarApoderado"
                class="btn btn-sm btn-danger mt-2 d-none"
                onclick="limpiarApoderado()">
            <i class="bi bi-x-circle"></i> Cambiar Apoderado
        </button>

        <hr>

        {{-- CAMPOS MANUALES --}}
        <div class="row g-3">

            {{-- NOMBRE COMPLETO --}}
            <div class="col-md-6">
                <label class="form-label">Nombre Completo *</label>
                <input type="text" 
                    id="nombre_retira"
                    name="nombre_retira" 
                    class="form-control"
                    placeholder="Ej: Juan Pérez"
                    required>
            </div>

            {{-- RUN --}}
            <div class="col-md-3">
                <label class="form-label">RUN *</label>
                <input type="text" 
                    id="run_retira"
                    name="run_retira" 
                    class="form-control"
                    placeholder="Ej: 12.345.678-9"
                    required>
            </div>

            {{-- PARENTESCO --}}
            <div class="col-md-3">
                <label class="form-label">Parentesco *</label>
                <input type="text" 
                    id="parentesco_retira"
                    name="parentesco_retira" 
                    class="form-control"
                    placeholder="Ej: Tío, Abuela, Vecino..."
                    required>
            </div>

            {{-- TELÉFONO --}}
            <div class="col-md-4">
                <label class="form-label">Teléfono</label>
                <input type="text" 
                    id="telefono_retira"
                    name="telefono_retira" 
                    class="form-control"
                    placeholder="Ej: +56 9 1234 5678">
            </div>

        </div>

    </div>


    {{-- =========================================================
         SECCIÓN 3: INFORMACIÓN DEL RETIRO
    ========================================================== --}}
    <div class="form-section mt-4">

        <h5 class="form-section-title">Información del Retiro</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date"
                       name="fecha"
                       class="form-control"
                       required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Hora <span class="text-danger">*</span></label>
                <input type="time"
                       name="hora"
                       class="form-control"
                       required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Motivo</label>
                <input type="text"
                       name="motivo"
                       class="form-control"
                       placeholder="Ej: Control médico, emergencia familiar, etc.">
            </div>

        </div>

    </div>


    {{-- =========================================================
         SECCIÓN 4: OBSERVACIONES
    ========================================================== --}}
    <div class="form-section mt-4">

        <h5 class="form-section-title">Observaciones</h5>

        <textarea
            name="observaciones"
            rows="3"
            class="form-control"
            placeholder="Observaciones internas (opcional)..."></textarea>

    </div>


    {{-- =========================================================
         BOTONES
    ========================================================== --}}
    <div class="d-flex gap-2 flex-wrap mt-4">

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Registrar Retiro
        </button>

        <a href="{{ route('inspectoria.retiros.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>

    </div>

</form>

@endif {{-- FIN PERMISO --}}


{{-- =========================================================
     ERRORES
========================================================= --}}
@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- =========================================================
     MODALES ESTÁNDAR
========================================================= --}}
@include('modulos.inspectoria.partials.modal-buscar-alumno')
@include('modulos.inspectoria.partials.modal-buscar-apoderado')


{{-- =========================================================
     JS
========================================================= --}}
<script>

// Selección de alumno
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('seleccionar-alumno')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let curso = e.target.dataset.curso;

        document.getElementById('alumno_id').value = id;
        document.getElementById('textoAlumnoSeleccionado').textContent = `${nombre} (${curso})`;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarAlumno')
        ).hide();
    }
});


// Selección de apoderado
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('seleccionar-apoderado')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let run = e.target.dataset.run;
        let telefono = e.target.dataset.telefono;

        agregarApoderadoSeleccionado(id, nombre, run, telefono);

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarApoderado')
        ).hide();
    }
});


function agregarApoderadoSeleccionado(id, nombre, run, telefono) {

    document.getElementById('apoderado_id').value = id;

    document.getElementById('nombre_retira').value = nombre;
    document.getElementById('run_retira').value = run;
    document.getElementById('telefono_retira').value = telefono ?? '';
    document.getElementById('parentesco_retira').value = '';

    deshabilitarCamposManual(true);

    document.getElementById('textoApoderadoSeleccionado').innerHTML =
        `<span class="text-success fw-bold">${nombre}</span> (seleccionado por modal)`;

    document.getElementById('btnLimpiarApoderado').classList.remove('d-none');
}


function deshabilitarCamposManual(bloquear) {
    let campos = [
        'nombre_retira',
        'run_retira',
        'telefono_retira',
        'parentesco_retira'
    ];

    campos.forEach(id => {
        let el = document.getElementById(id);
        el.readOnly = bloquear;
        el.classList.toggle('bg-light', bloquear);
    });
}


function limpiarApoderado() {

    document.getElementById('apoderado_id').value = '';
    document.getElementById('nombre_retira').value = '';
    document.getElementById('run_retira').value = '';
    document.getElementById('telefono_retira').value = '';
    document.getElementById('parentesco_retira').value = '';

    deshabilitarCamposManual(false);

    document.getElementById('textoApoderadoSeleccionado').innerHTML =
        `Ningún apoderado seleccionado.`;

    document.getElementById('btnLimpiarApoderado').classList.add('d-none');
}

</script>

@endsection
