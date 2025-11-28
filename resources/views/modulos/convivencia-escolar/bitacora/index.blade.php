@extends('layouts.app')

@section('title', 'Bitácora de Incidentes')

@section('content')

@ver('bitacora')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Bitácora de Incidentes</h1>
        <p class="text-muted">Registro y seguimiento de incidentes dentro del establecimiento</p>
    </div>

    <div>
        @crear('bitacora')
        <a href="{{ route('convivencia.bitacora.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Nuevo Incidente
        </a>
        @endcrear
    </div>
</div>

<div class="card card-table">

    {{-- =========================================================
         FILTROS
    ========================================================= --}}
    <div class="card-header">
        <form method="GET" action="{{ route('convivencia.bitacora.index') }}">
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

                {{-- Modal alumno --}}
                @include('modulos.convivencia-escolar.partials.modal-buscar-alumno')

                {{-- Tipo de incidente --}}
                <div class="col-12 col-md-3">
                    <input type="text"
                           name="tipo"
                           class="form-control"
                           placeholder="Tipo de incidente..."
                           value="{{ request('tipo') }}">
                </div>

                {{-- Estado --}}
                <div class="col-12 col-md-2">
                    <select name="estado_id" class="form-select">
                        <option value="">Estado</option>
                        @foreach($estados as $e)
                            <option value="{{ $e->id }}"
                                {{ request('estado_id') == $e->id ? 'selected' : '' }}>
                                {{ $e->nombre }}
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

        @if($incidentes->count() == 0)
            <div class="text-center text-muted py-4">
                <i class="bi bi-info-circle fs-2"></i>
                <p class="mt-2">No hay incidentes registrados</p>
            </div>
        @else

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno / Curso</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Involucrados</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($incidentes as $i)
                    <tr>

                        {{-- FECHA --}}
                        <td>{{ \Carbon\Carbon::parse($i->fecha)->format('d/m/Y') }}</td>

                        {{-- ALUMNO PRINCIPAL --}}
                        <td>
                            @php 
                                $alumnoPrincipal = $i->involucrados->first()->alumno ?? null; 
                            @endphp

                            @if($alumnoPrincipal)
                                {{ $alumnoPrincipal->nombre_completo }} <br>
                                <small class="text-muted">
                                    {{ $alumnoPrincipal->curso->nombre ?? '' }}
                                </small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- TIPO --}}
                        <td>{{ $i->tipo_incidente }}</td>

                        {{-- ESTADO --}}
                        <td>
                            <span class="badge bg-primary">
                                {{ $i->estado->nombre ?? 'Sin estado' }}
                            </span>
                        </td>

                        {{-- INVOLUCRADOS --}}
                        <td>
                            @foreach($i->involucrados as $inv)
                                <div class="small">
                                    <strong>{{ ucfirst($inv->rol) }}:</strong>
                                    {{ $inv->alumno->nombre_completo }}
                                </div>
                            @endforeach
                        </td>

                        {{-- ACCIONES --}}
                        <td class="table-actions">

                            {{-- Ver --}}
                            @ver('bitacora')
                            <a href="{{ route('convivencia.bitacora.show', $i->id) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            @endver

                            {{-- Editar --}}
                            @editar('bitacora')
                            <a href="{{ route('convivencia.bitacora.edit', $i->id) }}"
                               class="btn btn-sm btn-primary"
                               title="Editar">
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
            {{ $incidentes->links() }}
        </div>

        @endif

    </div>
</div>

@endver

@endsection


{{-- =========================================================
     JS SELECCIÓN ALUMNO
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
