@extends('layouts.app')

@section('title', 'Retiros Anticipados')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Retiros Anticipados</h1>
        <p class="text-muted">Registro general de retiros de alumnos autorizados</p>
    </div>

    {{-- =====================================
         PERMISO: CREAR RETIRO
    ===================================== --}}
    @if(canAccess('retiros', 'create'))
    <div>
        <a href="{{ route('inspectoria.retiros.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Registrar Retiro
        </a>
    </div>
    @endif
</div>

<div class="card card-table">

    {{-- ===========================
        FILTROS
    =========================== --}}
    <div class="card-header">
        <form method="GET" action="{{ route('inspectoria.retiros.index') }}">
            <div class="row g-3">

                {{-- Buscar alumno --}}
                <div class="col-12 col-md-6">

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

                {{-- Fecha --}}
                <div class="col-12 col-md-3">
                    <input type="date"
                        name="fecha"
                        value="{{ request('fecha') }}"
                        class="form-control">
                </div>

                {{-- Botón Filtrar --}}
                <div class="col-12 col-md-3">
                    <button class="btn btn-secondary w-100">
                        <i class="bi bi-funnel me-2"></i>Filtrar
                    </button>
                </div>

            </div>
        </form>
    </div>


    {{-- ===========================
         TABLA DE RETIROS
    ============================ --}}
    <div class="card-body">
        @if($retiros->count() == 0)
            <div class="text-center text-muted py-4">
                <i class="bi bi-info-circle fs-2"></i>
                <p class="mt-2">No hay retiros registrados</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno / Curso</th>
                        <th>Retirado Por</th>
                        <th>Motivo</th>
                        <th>Funcionario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($retiros as $ret)
                        <tr>

                            {{-- Fecha / Hora --}}
                            <td>
                                {{ date('d/m/Y', strtotime($ret->fecha)) }}<br>
                                <small class="text-muted">
                                    {{ date('H:i', strtotime($ret->hora)) }}
                                </small>
                            </td>

                            {{-- Alumno --}}
                            <td>
                                {{ $ret->alumno->nombre_completo }}<br>
                                <small class="text-muted">{{ $ret->alumno->curso->nombre ?? '' }}</small>
                            </td>

                            {{-- Quién retira --}}
                            <td>
                                @if($ret->apoderado)
                                    {{ $ret->apoderado->nombre_completo }}
                                    <br>
                                    <small class="text-muted">Apoderado</small>
                                @else
                                    {{ $ret->nombre_retira ?? '—' }}<br>
                                    <small class="text-muted">{{ $ret->parentesco_retira ?? '' }}</small>
                                @endif
                            </td>

                            {{-- Motivo --}}
                            <td>{{ $ret->motivo ?? '—' }}</td>

                            {{-- Funcionario --}}
                            <td>
                                {{ $ret->funcionarioEntrega->nombre }} 
                                {{ $ret->funcionarioEntrega->apellido_paterno }}
                            </td>

                            {{-- Acciones --}}
                            <td class="table-actions">

                                {{-- PERMISO: VER --}}
                                @if(canAccess('retiros', 'view'))
                                <a href="{{ route('inspectoria.retiros.show', $ret) }}"
                                class="btn btn-sm btn-info"
                                title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endif

                                {{-- PERMISO: EDITAR --}}
                                @if(canAccess('retiros', 'edit'))
                                <a href="{{ route('inspectoria.retiros.edit', $ret) }}"
                                class="btn btn-sm btn-primary"
                                title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif

                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-3">
            {{ $retiros->links() }}
        </div>

        @endif
    </div>

</div>

@endsection


{{-- ===========================
     JS SELECCIÓN DE ALUMNO
=========================== --}}
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
