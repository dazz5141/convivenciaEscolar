@extends('layouts.app')

@section('title', 'Accidentes Escolares')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Accidentes Escolares</h1>
        <p class="text-muted">Registro y seguimiento de accidentes escolares</p>
    </div>
    <div>
        <a href="{{ route('inspectoria.accidentes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Registrar Accidente
        </a>
    </div>
</div>

<div class="card card-table">

    {{-- FILTROS --}}
    <div class="card-header">
        <form method="GET" action="{{ route('inspectoria.accidentes.index') }}">
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

                {{-- Modal --}}
                @include('modulos.inspectoria.partials.modal-buscar-alumno')

                {{-- Tipo de accidente --}}
                <div class="col-12 col-md-3">
                    <select name="tipo" class="form-select">
                        <option value="">Tipo de accidente</option>
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
        @if($accidentes->count() == 0)
            <div class="text-center text-muted py-4">
                <i class="bi bi-info-circle fs-2"></i>
                <p class="mt-2">No hay accidentes registrados</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno</th>
                        <th>Tipo</th>
                        <th>Derivación</th>
                        <th>Registrado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accidentes as $acc)
                        <tr>
                            <td>{{ date('d/m/Y H:i', strtotime($acc->fecha)) }}</td>

                            <td>
                                {{ $acc->alumno->nombre_completo }}<br>
                                <small class="text-muted">{{ $acc->alumno->curso->nombre ?? '' }}</small>
                            </td>

                            <td>{{ $acc->tipo->nombre }}</td>

                            <td>
                                @if($acc->derivacion_salud)
                                    <span class="badge bg-danger">Derivado a salud</span>
                                @else
                                    <span class="badge bg-success">Sin derivación</span>
                                @endif
                            </td>

                            <td>
                                {{ 
                                    $acc->funcionario?->nombre_completo 
                                    ?? 
                                    $acc->usuario?->nombre_completo 
                                    ?? 
                                    'Sin registro' 
                                }}
                            </td>

                            <td class="table-actions">
                                <a href="{{ route('inspectoria.accidentes.show', $acc) }}" 
                                   class="btn btn-sm btn-info" 
                                   title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('inspectoria.accidentes.edit', $acc) }}" 
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
            {{ $accidentes->links() }}
        </div>

        @endif
    </div>

</div>

@endsection

{{-- JS SELECCIÓN DE ALUMNO --}}
<script>
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('seleccionar-alumno')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;

        // Guardar ID
        document.getElementById('alumno_id').value = id;

        // Mostrar nombre en el input
        document.getElementById('inputAlumnoSeleccionado').value = nombre;

        // Cerrar modal
        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarAlumno')
        ).hide();
    }
});
</script>
