@extends('layouts.app')

@section('title', 'Nuevo Informe PIE')

@section('content')

<div class="page-header">
    <h1 class="page-title">Nuevo Informe PIE</h1>
    <p class="text-muted">Crear un informe asociado a un estudiante del Programa de Integración Escolar.</p>
</div>

@include('components.alerts')

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
                data-bs-target="#modalBuscarAlumnoPIE">
            <i class="bi bi-search"></i> Buscar Estudiante PIE
        </button>

        <input type="hidden" name="estudiante_pie_id" id="estudiante_pie_id" required>

        <p class="fw-bold" id="textoAlumnoSeleccionado" style="min-height: 22px;">
            No se ha seleccionado estudiante.
        </p>
    </div>

    {{-- =============================
        TIPO DE INFORME
    ============================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Tipo de Informe</h5>

        <input list="tipos_sugeridos"
            name="tipo"
            class="form-control"
            placeholder="Ej: Informe técnico, seguimiento, informe anual, etc."
            required>

        <datalist id="tipos_sugeridos">
            @foreach($tipos as $t)
                <option value="{{ $t }}">
            @endforeach
        </datalist>
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
        <textarea name="contenido"
                  class="form-control"
                  rows="6"
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
     MODAL (MISMO QUE FUNCIONA)
=========================== --}}
@include('modulos.pie.partials.modal-buscar-estudiante-pie')

{{-- ===========================
     JS SELECCIÓN (MISMO PATRÓN)
=========================== --}}
<script>
document.addEventListener('click', function(e) {
    // Seleccionar Estudiante PIE desde el modal
    if (e.target.classList.contains('seleccionar-estudiante-pie')) {
        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let curso = e.target.dataset.curso;

        document.getElementById('estudiante_pie_id').value = id;
        document.getElementById('textoAlumnoSeleccionado').textContent = `${nombre} (${curso})`;

        bootstrap.Modal.getInstance(document.getElementById('modalBuscarAlumnoPIE')).hide();
    }
});
</script>

@endsection
