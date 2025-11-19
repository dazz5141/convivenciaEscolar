@extends('layouts.app')

@section('title', 'Nueva Derivación')

@section('content')

<div class="page-header">
    <h1 class="page-title">Nueva Derivación</h1>
    <p class="text-muted">Registrar una nueva derivación interna o externa</p>
</div>

<form action="{{ route('convivencia.derivaciones.store') }}" method="POST">
    @csrf

    @php
        $tipoEntidad = request('tipo_entidad') ?? null;   // seguimiento | medida
        $entidadId   = request('entidad_id') ?? null;

        $alumno = null;

        // Si viene desde seguimiento
        if ($tipoEntidad === 'seguimiento' && $entidadId) {
            $origen = \App\Models\SeguimientoEmocional::with('alumno')->find($entidadId);
            $alumno = $origen?->alumno;
        }

        // Si viene desde medida
        if ($tipoEntidad === 'medida' && $entidadId) {
            $origen = \App\Models\MedidaRestaurativa::with('alumno')->find($entidadId);
            $alumno = $origen?->alumno;
        }
    @endphp

    {{-- =========================================================
         SECCIÓN 1: DATOS DEL ALUMNO
    ========================================================= --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno Derivado</h5>

        @if($alumno)
            {{-- Viene desde Seguimiento o Medida --}}
            <input type="hidden" name="alumno_id" value="{{ $alumno->id }}">

            {{-- Campos polimórficos --}}
            <input type="hidden" name="tipo_entidad" value="{{ $tipoEntidad }}">
            <input type="hidden" name="entidad_id" value="{{ $entidadId }}">

            <p class="fw-bold">
                {{ $alumno->nombre_completo }}
                <br>
                <small class="text-muted">{{ $alumno->curso->nombre ?? '' }}</small>
            </p>

        @else
            {{-- Búsqueda manual de alumno (tu modal normal) --}}

            <button type="button" 
                    class="btn btn-outline-primary mb-3"
                    data-bs-toggle="modal"
                    data-bs-target="#modalBuscarAlumno">
                <i class="bi bi-search"></i> Buscar Alumno
            </button>

            <input type="hidden" name="alumno_id" id="alumno_id">
            <input type="hidden" name="tipo_entidad" value="{{ $tipoEntidad }}">
            <input type="hidden" name="entidad_id" value="{{ $entidadId }}">
            <p class="fw-bold" id="textoAlumnoSeleccionado"></p>
        @endif
    </div>

    {{-- =========================================================
         SECCIÓN 2: INFORMACIÓN DE LA DERIVACIÓN
    ========================================================= --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información de la Derivación</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date" name="fecha" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tipo de derivación <span class="text-danger">*</span></label>
                <select name="tipo" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="interna">Interna</option>
                    <option value="externa">Externa</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Estado <span class="text-danger">*</span></label>
                <select name="estado" class="form-select" required>
                    <option value="Enviada">Enviada</option>
                    <option value="En revisión">En revisión</option>
                    <option value="Cerrada">Cerrada</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Destino <span class="text-danger">*</span></label>
                <input type="text"
                       name="destino"
                       class="form-control"
                       placeholder="Ej: Psicólogo, Asistente Social, Centro Externo..."
                       required>
            </div>

            <div class="col-12">
                <label class="form-label">Motivo</label>
                <textarea name="motivo" class="form-control" rows="4"
                          placeholder="Describa el motivo de la derivación..."></textarea>
            </div>

        </div>
    </div>


    {{-- =========================================================
         SECCIÓN 3: FUNCIONARIO QUE REGISTRA
    ========================================================= --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Funcionario Responsable</h5>

        <button type="button" class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal"
                data-bs-target="#modalBuscarFuncionario">
            <i class="bi bi-search"></i> Buscar Funcionario
        </button>

        <input type="hidden" name="registrado_por" id="funcionario_id">
        <p class="fw-bold" id="textoFuncionarioSeleccionado"></p>
    </div>


    {{-- =========================================================
         BOTONES
    ========================================================= --}}
    <div class="d-flex gap-2 flex-wrap mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>Guardar Derivación
        </button>

        <a href="{{ route('convivencia.derivaciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
    </div>

</form>



{{-- =========================================================
     MODAL BUSCAR ALUMNO
========================================================= --}}
@include('modulos.convivencia-escolar.partials.modal-buscar-alumno')


{{-- =========================================================
     MODAL BUSCAR FUNCIONARIO
========================================================= --}}
@include('modulos.convivencia-escolar.partials.modal-buscar-funcionario')



{{-- =========================================================
     JS DINÁMICO
========================================================= --}}
<script>

document.addEventListener('click', function(e) {

    // Selección desde modal (compartido en los 3 módulos)
    if (e.target.classList.contains('seleccionar-alumno')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;

        document.getElementById('alumno_id').value = id;
        document.getElementById('textoAlumnoSeleccionado').textContent = nombre;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarAlumno')
        ).hide();
    }

    if (e.target.classList.contains('seleccionar-funcionario')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;

        document.getElementById('funcionario_id').value = id;
        document.getElementById('textoFuncionarioSeleccionado').textContent = nombre;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarFuncionario')
        ).hide();
    }
});

</script>

@endsection
