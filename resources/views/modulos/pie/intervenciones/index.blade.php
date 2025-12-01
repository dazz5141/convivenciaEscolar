@extends('layouts.app')

@section('title', 'Intervenciones PIE')

@section('content')

{{-- =========================================================
     ENCABEZADO
========================================================= --}}
<div class="page-header d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h1 class="page-title">Intervenciones PIE</h1>
        <p class="text-muted">Registro de intervenciones realizadas a estudiantes PIE</p>
    </div>

    {{-- Botón nuevo (BLINDADO) --}}
    @if(canAccess('pie-intervenciones','create'))
        <a href="{{ route('pie.intervenciones.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Nueva Intervención
        </a>
    @endif
</div>


{{-- =========================================================
     VALIDAR PERMISOS
========================================================= --}}
@if(!canAccess('pie-intervenciones','view'))
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        No tienes permisos para ver las intervenciones PIE.
    </div>
    @return
@endif



{{-- =========================================================
     FILTROS
========================================================= --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filtros</h5>
    </div>

    <form method="GET" action="{{ route('pie.intervenciones.index') }}">
        <div class="card-body">
            <div class="row g-3">

                {{-- Estudiante PIE --}}
                <div class="col-md-4">
                    <label class="form-label">Estudiante PIE</label>
                    <select name="estudiante_pie_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($estudiantes as $e)
                            <option value="{{ $e->id }}"
                                {{ request('estudiante_pie_id') == $e->id ? 'selected' : '' }}>
                                {{ $e->alumno->apellido_paterno }}
                                {{ $e->alumno->apellido_materno }},
                                {{ $e->alumno->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tipo --}}
                <div class="col-md-4">
                    <label class="form-label">Tipo de Intervención</label>
                    <select name="tipo_intervencion_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($tipos as $t)
                            <option value="{{ $t->id }}"
                                {{ request('tipo_intervencion_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Fecha --}}
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



{{-- =========================================================
     TABLA DE INTERVENCIONES
========================================================= --}}
<div class="card card-table">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Estudiante</th>
                        <th>Tipo</th>
                        <th>Profesional</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($intervenciones as $i)
                    <tr>
                        {{-- Fecha --}}
                        <td>{{ \Carbon\Carbon::parse($i->fecha)->format('d/m/Y') }}</td>

                        {{-- Estudiante --}}
                        <td>
                            {{ $i->estudiante->alumno->apellido_paterno }}
                            {{ $i->estudiante->alumno->apellido_materno }},
                            {{ $i->estudiante->alumno->nombre }}
                        </td>

                        {{-- Tipo --}}
                        <td>{{ $i->tipo->nombre }}</td>

                        {{-- Profesional --}}
                        <td>
                            {{ $i->profesional->funcionario->apellido_paterno }}
                            {{ $i->profesional->funcionario->apellido_materno }},
                            {{ $i->profesional->funcionario->nombre }}
                        </td>

                        {{-- Acciones --}}
                        <td class="d-flex gap-1">

                            {{-- Ver detalle --}}
                            @if(canAccess('intervenciones','view'))
                            <a href="{{ route('pie.intervenciones.show', $i->id) }}"
                               class="btn btn-sm btn-info"
                               title="Ver detalle">
                                <i class="bi bi-eye"></i>
                            </a>
                            @endif

                            {{-- Historial --}}
                            <a href="{{ route('pie.historial.show', ['tipo' => 'intervencion', 'id' => $i->id]) }}"
                               class="btn btn-sm btn-secondary"
                               title="Ver en historial">
                                <i class="bi bi-clock-history"></i>
                            </a>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No se encontraron intervenciones con los filtros aplicados.
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

    </div>

    <div class="card-footer">
        {{ $intervenciones->links() }}
    </div>
</div>

@endsection
