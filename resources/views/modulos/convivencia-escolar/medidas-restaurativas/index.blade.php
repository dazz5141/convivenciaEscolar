@extends('layouts.app')

@section('title', 'Medidas Restaurativas')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Medidas Restaurativas</h1>
        <p class="text-muted">Registro de acciones restaurativas asociadas a incidentes o alumnos</p>
    </div>

    <div>
        <a href="{{ route('convivencia.medidas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Registrar Medida
        </a>
    </div>
</div>


<div class="card card-table">

    {{-- ============================
            FILTROS
    ============================ --}}
    <div class="card-header">
        <form method="GET" action="{{ route('convivencia.medidas.index') }}">
            <div class="row g-3">

                {{-- FILTRO: ALUMNO CON MODAL --}}
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

                    <input type="hidden" name="alumno_id" id="alumno_id" value="{{ request('alumno_id') }}">
                </div>

                {{-- Modal Alumno --}}
                @include('modulos.convivencia-escolar.partials.modal-buscar-alumno')


                {{-- Tipo Medida --}}
                <div class="col-12 col-md-3">
                    <select name="tipo_medida_id" class="form-select">
                        <option value="">Tipo de medida</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}"
                                {{ request('tipo_medida_id') == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Estado Cumplimiento --}}
                <div class="col-12 col-md-3">
                    <select name="cumplimiento_estado_id" class="form-select">
                        <option value="">Estado de cumplimiento</option>
                        @foreach($estadosCumplimiento as $estado)
                            <option value="{{ $estado->id }}"
                                {{ request('cumplimiento_estado_id') == $estado->id ? 'selected' : '' }}>
                                {{ $estado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botón --}}
                <div class="col-12 col-md-2">
                    <button class="btn btn-secondary w-100">
                        <i class="bi bi-funnel me-2"></i> Filtrar
                    </button>
                </div>

            </div>
        </form>
    </div>



    {{-- ============================
            TABLA
    ============================ --}}
    <div class="card-body">
        @if($medidas->count() == 0)
            <div class="text-center text-muted py-4">
                <i class="bi bi-info-circle fs-2"></i>
                <p class="mt-2">No se encontraron medidas registradas</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Alumno</th>
                        <th>Responsable</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($medidas as $m)
                        <tr>

                            {{-- Fechas --}}
                            <td>{{ $m->fecha_inicio ? date('d/m/Y', strtotime($m->fecha_inicio)) : '—' }}</td>
                            <td>{{ $m->fecha_fin ? date('d/m/Y', strtotime($m->fecha_fin)) : '—' }}</td>

                            {{-- Tipo --}}
                            <td>{{ $m->tipoMedida->nombre }}</td>

                            {{-- Estado --}}
                            <td>
                                <span class="badge bg-primary">{{ $m->cumplimiento->nombre }}</span>
                            </td>

                            {{-- Alumno --}}
                            <td>
                                {{ $m->alumno->nombre_completo ?? '—' }}  
                                <br>
                                <small class="text-muted">
                                    {{ $m->alumno->curso->nombre ?? '' }}
                                </small>
                            </td>

                            {{-- Responsable --}}
                            <td>
                                {{ $m->responsable->nombre }} 
                                {{ $m->responsable->apellido_paterno }}
                            </td>

                            {{-- Acciones --}}
                            <td class="table-actions">

                                <a href="{{ route('convivencia.medidas.show', $m) }}"
                                   class="btn btn-sm btn-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('convivencia.medidas.edit', $m) }}"
                                   class="btn btn-sm btn-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-3">
            {{ $medidas->links() }}
        </div>

        @endif
    </div>

</div>

@endsection


{{-- ============================
        JS Selección Alumno
============================ --}}
<script>
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('seleccionar-alumno')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let curso = e.target.dataset.curso;

        document.getElementById('alumno_id').value = id;
        document.getElementById('inputAlumnoSeleccionado').value =
            `${nombre} (${curso})`;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarAlumno')
        ).hide();
    }
});
</script>
