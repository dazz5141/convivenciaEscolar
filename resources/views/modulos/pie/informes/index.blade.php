@extends('layouts.app')

@section('title', 'Informes PIE')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h1 class="page-title">Informes PIE</h1>
        <p class="text-muted">Listado de informes generados para estudiantes PIE</p>
    </div>

    <a href="{{ route('pie.informes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i> Nuevo Informe
    </a>
</div>


{{-- ============================
     FILTROS
============================ --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filtros</h5>
    </div>

    <form method="GET" action="{{ route('pie.informes.index') }}">
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Estudiante PIE</label>
                    <select name="estudiante_pie_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($estudiantes as $e)
                            <option value="{{ $e->id }}" {{ request('estudiante_pie_id') == $e->id ? 'selected' : '' }}>
                                {{ $e->alumno->apellido_paterno }} {{ $e->alumno->apellido_materno }}, {{ $e->alumno->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo de Informe</label>
                    <input type="text" name="tipo" class="form-control" value="{{ request('tipo') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
                </div>

            </div>
        </div>

        <div class="card-footer text-end">
            <button class="btn btn-secondary">
                <i class="bi bi-funnel me-2"></i> Aplicar Filtros
            </button>
        </div>
    </form>
</div>



{{-- ============================
     TABLA
============================ --}}
<div class="card card-table">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Estudiante</th>
                        <th>Tipo</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($informes as $i)

                    <tr>
                        <td>{{ \Carbon\Carbon::parse($i->fecha)->format('d/m/Y') }}</td>

                        <td>
                            {{ $i->estudiante->alumno->apellido_paterno }}
                            {{ $i->estudiante->alumno->apellido_materno }},
                            {{ $i->estudiante->alumno->nombre }}
                        </td>

                        <td>{{ $i->tipo }}</td>

                        <td>
                            <a href="{{ route('pie.informes.show', $i->id) }}"
                               class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('pie.historial.show', ['tipo' => 'informe', 'id' => $informe->id]) }}"
                                class="btn btn-sm btn-secondary"
                                title="Ver en historial">
                                <i class="bi bi-clock-history"></i>
                            </a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            No se encontraron informes con los filtros aplicados.
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

    </div>

    <div class="card-footer">
        {{ $informes->links() }}
    </div>
</div>

@endsection
