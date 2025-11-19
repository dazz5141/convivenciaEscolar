@extends('layouts.app')

@section('title', 'Derivaciones')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Derivaciones</h1>
        <p class="text-muted">Registro de derivaciones internas y externas de estudiantes</p>
    </div>

    <div>
        <a href="{{ route('convivencia.derivaciones.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Nueva Derivación
        </a>
    </div>
</div>

<div class="card card-table">

    {{-- =========================================================
         FILTROS
    ========================================================= --}}
    <div class="card-header">
        <form method="GET" action="{{ route('convivencia.derivaciones.index') }}">
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

                {{-- Tipo --}}
                <div class="col-12 col-md-2">
                    <select name="tipo" class="form-select">
                        <option value="">Tipo</option>
                        <option value="interna" {{ request('tipo') == 'interna' ? 'selected' : '' }}>Interna</option>
                        <option value="externa" {{ request('tipo') == 'externa' ? 'selected' : '' }}>Externa</option>
                    </select>
                </div>

                {{-- Estado --}}
                <div class="col-12 col-md-2">
                    <select name="estado" class="form-select">
                        <option value="">Estado</option>
                        <option value="Enviada" {{ request('estado') == 'Enviada' ? 'selected' : '' }}>Enviada</option>
                        <option value="En revisión" {{ request('estado') == 'En revisión' ? 'selected' : '' }}>En revisión</option>
                        <option value="Cerrada" {{ request('estado') == 'Cerrada' ? 'selected' : '' }}>Cerrada</option>
                    </select>
                </div>

                {{-- Fecha --}}
                <div class="col-12 col-md-2">
                    <input type="date"
                           name="fecha"
                           value="{{ request('fecha') }}"
                           class="form-control">
                </div>

                {{-- Botón --}}
                <div class="col-12 col-md-1">
                    <button class="btn btn-secondary w-100">
                        <i class="bi bi-funnel me-2"></i>Filtrar
                    </button>
                </div>

            </div>
        </form>
    </div>

    {{-- =========================================================
         TABLA
    ========================================================= --}}
    <div class="card-body">

        @if($derivaciones->count() == 0)
            <div class="text-center text-muted py-4">
                <i class="bi bi-info-circle fs-2"></i>
                <p class="mt-2">No hay derivaciones registradas</p>
            </div>
        @else

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno / Curso</th>
                        <th>Tipo</th>
                        <th>Destino</th>
                        <th>Estado</th>
                        <th>Funcionario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($derivaciones as $d)
                    <tr>

                        {{-- FECHA --}}
                        <td>{{ \Carbon\Carbon::parse($d->fecha)->format('d/m/Y') }}</td>

                        {{-- ALUMNO --}}
                        <td>
                            {{ $d->alumno->nombre_completo }} <br>
                            <small class="text-muted">
                                {{ $d->alumno->curso->nombre ?? '' }}
                            </small>
                        </td>

                        {{-- TIPO --}}
                        <td>
                            <span class="badge bg-info">
                                {{ ucfirst($d->tipo) }}
                            </span>
                        </td>

                        {{-- DESTINO --}}
                        <td>{{ $d->destino }}</td>

                        {{-- ESTADO --}}
                        <td>
                            <span class="badge bg-primary">{{ $d->estado ?? '—' }}</span>
                        </td>

                        {{-- FUNCIONARIO --}}
                        <td>
                            {{ $d->funcionario->apellido_paterno }}
                            {{ $d->funcionario->apellido_materno }},
                            {{ $d->funcionario->nombre }}
                        </td>

                        {{-- ACCIONES --}}
                        <td class="table-actions">
                            <a href="{{ route('convivencia.derivaciones.show', $d->id) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('convivencia.derivaciones.edit', $d->id) }}"
                               class="btn btn-sm btn-primary"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>

                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-3">
            {{ $derivaciones->links() }}
        </div>

        @endif

    </div>
</div>

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
