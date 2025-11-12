@extends('layouts.app')

@section('title', 'Derivaciones PIE')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h1 class="page-title">Derivaciones PIE</h1>
        <p class="text-muted">Listado de derivaciones realizadas en el Programa de Integración Escolar.</p>
    </div>

    <a href="{{ route('pie.derivaciones.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i> Nueva Derivación
    </a>
</div>


{{-- =====================================
     FILTROS
===================================== --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filtros</h5>
    </div>

    <form method="GET" action="{{ route('pie.derivaciones.index') }}">
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
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="En curso" {{ request('estado') == 'En curso' ? 'selected' : '' }}>En curso</option>
                        <option value="Cerrada" {{ request('estado') == 'Cerrada' ? 'selected' : '' }}>Cerrada</option>
                        <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    </select>
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



{{-- =====================================
     TABLA
===================================== --}}
<div class="card card-table">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Estudiante</th>
                        <th>Destino</th>
                        <th>Estado</th>
                        <th width="100">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($derivaciones as $d)

                    <tr>
                        <td>{{ \Carbon\Carbon::parse($d->fecha)->format('d/m/Y') }}</td>

                        <td>
                            {{ $d->estudiante->alumno->apellido_paterno }}
                            {{ $d->estudiante->alumno->apellido_materno }},
                            {{ $d->estudiante->alumno->nombre }}
                        </td>

                        <td>{{ $d->destino }}</td>

                        <td>
                            <span class="badge bg-{{ $d->estado == 'Cerrada' ? 'secondary' : ($d->estado == 'En curso' ? 'info' : 'warning') }}">
                                {{ $d->estado ?? '—' }}
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('pie.derivaciones.show', $d->id) }}"
                               class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('pie.historial.show', ['tipo' => 'derivacion', 'id' => $d->id]) }}"
                                class="btn btn-sm btn-secondary"
                                title="Ver en historial">
                                <i class="bi bi-clock-history"></i>
                            </a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No se encontraron derivaciones con los filtros aplicados.
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

    </div>

    <div class="card-footer">
        {{ $derivaciones->links() }}
    </div>
</div>

@endsection
