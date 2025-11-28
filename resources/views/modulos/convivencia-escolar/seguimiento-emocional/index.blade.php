@extends('layouts.app')

@section('title', 'Seguimiento Emocional')

@section('content')

@ver('seguimientos')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Seguimiento Emocional</h1>
        <p class="text-muted">Registro y control del bienestar emocional de los estudiantes</p>
    </div>

    <div>
        @crear('seguimientos')
        <a href="{{ route('convivencia.seguimiento.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Nuevo Seguimiento
        </a>
        @endcrear
    </div>
</div>

<div class="card card-table">

    {{-- =========================================================
         FILTROS
    ========================================================= --}}
    <div class="card-header">
        <form method="GET" action="{{ route('convivencia.seguimiento.index') }}">
            <div class="row g-3">

                {{-- Buscar alumno (modal) --}}
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <input type="text"
                               class="form-control"
                               id="inputAlumnoSeleccionado"
                               value="{{ $alumnoSeleccionado->nombre_completo ?? '' }}"
                               placeholder="Seleccione un alumno..."
                               readonly>

                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBuscarAlumno">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <input type="hidden"
                           name="alumno_id"
                           id="alumno_id"
                           value="{{ request('alumno_id') }}">
                </div>

                {{-- Modal alumno (reutilizado) --}}
                @include('modulos.convivencia-escolar.partials.modal-buscar-alumno')

                {{-- Estado --}}
                <div class="col-12 col-md-2">
                    <select name="estado_id" class="form-select">
                        <option value="">Estado</option>
                        @foreach($estados as $e)
                            <option value="{{ $e->id }}" {{ request('estado_id') == $e->id ? 'selected' : '' }}>
                                {{ $e->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nivel emocional --}}
                <div class="col-12 col-md-3">
                    <select name="nivel_emocional_id" class="form-select">
                        <option value="">Nivel emocional</option>
                        @foreach($niveles as $n)
                            <option value="{{ $n->id }}" {{ request('nivel_emocional_id') == $n->id ? 'selected' : '' }}>
                                {{ $n->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botón filtrar --}}
                <div class="col-12 col-md-2">
                    <button class="btn btn-secondary w-100">
                        <i class="bi bi-funnel me-2"></i> Filtrar
                    </button>
                </div>

            </div>
        </form>
    </div>


    {{-- =========================================================
         TABLA
    ========================================================= --}}
    <div class="card-body">

        @if($seguimientos->count() == 0)
            <div class="text-center text-muted py-4">
                <i class="bi bi-info-circle fs-2"></i>
                <p class="mt-2">No se encontraron registros con los filtros aplicados</p>
            </div>
        @else

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno / Curso</th>
                        <th>Nivel</th>
                        <th>Estado</th>
                        <th>Evaluado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($seguimientos as $s)
                    <tr>

                        {{-- FECHA --}}
                        <td>{{ \Carbon\Carbon::parse($s->fecha)->format('d/m/Y') }}</td>

                        {{-- ALUMNO --}}
                        <td>
                            {{ $s->alumno->nombre_completo }}<br>
                            <small class="text-muted">{{ $s->alumno->curso->nombre ?? '' }}</small>
                        </td>

                        {{-- NIVEL --}}
                        <td>
                            <span class="badge bg-info">
                                {{ $s->nivel->nombre ?? 'No asignado' }}
                            </span>
                        </td>

                        {{-- ESTADO --}}
                        <td>
                            <span class="badge bg-{{ $s->estado->color ?? 'secondary' }}">
                                {{ $s->estado->nombre }}
                            </span>
                        </td>

                        {{-- EVALUADOR --}}
                        <td>
                            {{ $s->evaluador->nombre_completo ?? '—' }}
                        </td>

                        {{-- ACCIONES --}}
                        <td class="table-actions">

                            @ver('seguimientos')
                            <a href="{{ route('convivencia.seguimiento.show', $s->id) }}"
                               class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            @endver

                            @editar('seguimientos')
                            <a href="{{ route('convivencia.seguimiento.edit', $s->id) }}"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endeditar

                        </td>

                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-3">
            {{ $seguimientos->links() }}
        </div>

        @endif

    </div>
</div>

@endver

@endsection


{{-- =========================================================
     JS PARA MODAL DE ALUMNO
========================================================= --}}
<script>
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('seleccionar-alumno')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;

        document.getElementById('alumno_id').value = id;
        document.getElementById('inputAlumnoSeleccionado').value = nombre;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarAlumno')
        ).hide();
    }
});
</script>
