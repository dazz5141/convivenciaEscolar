@extends('layouts.app')

@section('title', 'Citaciones a Apoderados')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Citaciones a Apoderados</h1>
        <p class="text-muted">Registro general de citaciones realizadas por Inspectoría</p>
    </div>

    {{-- =====================================================
         PERMISO: crear citación
    ====================================================== --}}
    @if(canAccess('citaciones', 'create'))
    <div>
        <a href="{{ route('inspectoria.citaciones.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Registrar Citación
        </a>
    </div>
    @endif
</div>

<div class="card card-table">

    {{-- FILTROS --}}
    <div class="card-header">
        <form method="GET" action="{{ route('inspectoria.citaciones.index') }}">
            <div class="row g-3">

                {{-- Filtro Alumno --}}
                <div class="col-12 col-md-4">

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

                    <input type="hidden" name="alumno_id" id="alumno_id"
                           value="{{ request('alumno_id') }}">
                </div>

                {{-- Modal --}}
                @include('modulos.inspectoria.partials.modal-buscar-alumno')

                {{-- Estado --}}
                <div class="col-12 col-md-3">
                    <select name="estado" class="form-select">
                        <option value="">Estado</option>
                        @foreach($estados as $e)
                            <option value="{{ $e->id }}"
                                {{ request('estado') == $e->id ? 'selected' : '' }}>
                                {{ $e->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Fecha --}}
                <div class="col-12 col-md-3">
                    <input type="date"
                           name="fecha"
                           value="{{ request('fecha') }}"
                           class="form-control">
                </div>

                <div class="col-12 col-md-2">
                    <button class="btn btn-secondary w-100">
                        <i class="bi bi-funnel me-2"></i>Filtrar
                    </button>
                </div>

            </div>
        </form>
    </div>

    {{-- TABLA --}}
    <div class="card-body">
        @if($citaciones->count() == 0)

            <div class="text-center text-muted py-4">
                <i class="bi bi-info-circle fs-2"></i>
                <p class="mt-2">No hay citaciones registradas</p>
            </div>

        @else

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno / Curso</th>
                        <th>Apoderado Citado</th>
                        <th>Motivo</th>
                        <th>Estado</th>
                        <th>Funcionario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($citaciones as $c)
                        <tr>

                            {{-- Fecha --}}
                            <td>{{ \Carbon\Carbon::parse($c->fecha_citacion)->format('d/m/Y H:i') }}</td>

                            {{-- Alumno --}}
                            <td>
                                {{ $c->alumno->nombre_completo }}<br>
                                <small class="text-muted">
                                    {{ $c->alumno->curso->nombre ?? '' }}
                                </small>
                            </td>

                            {{-- Apoderado --}}
                            <td>
                                @if($c->apoderado)
                                    {{ $c->apoderado->nombre_completo }}
                                @else
                                    <span class="text-muted">No registrado</span>
                                @endif
                            </td>

                            {{-- Motivo --}}
                            <td>
                                @if($c->motivo)
                                    <span title="{{ $c->motivo }}">
                                        {{ \Illuminate\Support\Str::limit($c->motivo, 50) }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td>
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ $c->estado->nombre }}
                                </span>
                            </td>

                            {{-- Funcionario --}}
                            <td>{{ $c->funcionario->nombre_completo }}</td>

                            {{-- ACCIONES --}}
                            <td class="table-actions">

                                {{-- PERMISO: ver --}}
                                @if(canAccess('citaciones', 'view'))
                                <a href="{{ route('inspectoria.citaciones.show', $c) }}"
                                   class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endif

                                {{-- PERMISO: editar --}}
                                @if(canAccess('citaciones', 'edit'))
                                <a href="{{ route('inspectoria.citaciones.edit', $c) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $citaciones->links() }}
        </div>

        @endif
    </div>

</div>

@endsection


{{-- JS selección alumno --}}
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
