@extends('layouts.app')

@section('title', 'Libro de Novedades')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Libro de Novedades</h1>
        <p class="text-muted">Registro general de novedades de Inspectoría</p>
    </div>
    <div>
        <a href="{{ route('inspectoria.novedades.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Registrar Novedad
        </a>
    </div>
</div>

<div class="card card-table">

    {{-- FILTROS --}}
    <div class="card-header">
        <form method="GET" action="{{ route('inspectoria.novedades.index') }}">
            <div class="row g-3">

                {{-- Buscar alumno --}}
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

                    <input type="hidden" name="alumno_id" id="alumno_id" value="{{ request('alumno_id') }}">
                </div>

                {{-- Modal de alumno --}}
                @include('modulos.inspectoria.partials.modal-buscar-alumno')

                {{-- Tipo de novedad --}}
                <div class="col-12 col-md-3">
                    <select name="tipo" class="form-select">
                        <option value="">Tipo de novedad</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}"
                                {{ request('tipo') == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Fecha --}}
                <div class="col-12 col-md-2">
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
        @if($novedades->count() == 0)
            <div class="text-center text-muted py-4">
                <i class="bi bi-info-circle fs-2"></i>
                <p class="mt-2">No hay novedades registradas</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno / Curso</th>
                        <th>Tipo</th>
                        <th>Funcionario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($novedades as $nov)
                        <tr>
                            <td>{{ date('d/m/Y H:i', strtotime($nov->fecha)) }}</td>

                            <td>
                                @if($nov->alumno)
                                    {{ $nov->alumno->nombre_completo }}<br>
                                    <small class="text-muted">{{ $nov->alumno->curso->nombre ?? '' }}</small>
                                @elseif($nov->curso)
                                    Curso: {{ $nov->curso->nombre }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>{{ $nov->tipo->nombre }}</td>

                            <td>
                                {{ $nov->funcionario->nombre }} 
                                {{ $nov->funcionario->apellido_paterno }}
                            </td>

                            <td class="table-actions">
                                <a href="{{ route('inspectoria.novedades.show', $nov) }}" 
                                   class="btn btn-sm btn-info" 
                                   title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('inspectoria.novedades.edit', $nov) }}" 
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
            {{ $novedades->links() }}
        </div>

        @endif
    </div>

</div>

@endsection


{{-- JS PARA SELECCIÓN DE ALUMNO --}}
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
