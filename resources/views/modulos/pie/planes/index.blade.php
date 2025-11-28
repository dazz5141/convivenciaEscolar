@extends('layouts.app')

@section('title', 'Planes Individuales PIE')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h1 class="page-title">Planes Individuales PIE</h1>
        <p class="text-muted">Listado de planes individuales asignados a estudiantes PIE</p>
    </div>

    {{-- ============================
         BOTÓN NUEVO PLAN (PERMISO)
    ============================= --}}
    @if(canAccess('pie','create'))
        <a href="{{ route('pie.planes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Nuevo Plan
        </a>
    @endif
</div>


{{-- =========================================================
     PERMISOS — SI NO PUEDE VER, BLOQUEAMOS TODA LA VISTA
========================================================= --}}
@if(!canAccess('pie','view'))
    <div class="alert alert-warning mt-3">
        <i class="bi bi-exclamation-triangle me-2"></i>
        No tienes permisos para ver los planes individuales PIE.
    </div>
    @return
@endif



{{-- ============================
     FILTROS
============================ --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filtros</h5>
    </div>

    <form method="GET" action="{{ route('pie.planes.index') }}">
        <div class="card-body">
            <div class="row g-3">

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

                <div class="col-md-4">
                    <label class="form-label">Fecha Inicio</label>
                    <input type="date" 
                           name="fecha_inicio" 
                           class="form-control" 
                           value="{{ request('fecha_inicio') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha Término</label>
                    <input type="date" 
                           name="fecha_termino" 
                           class="form-control" 
                           value="{{ request('fecha_termino') }}">
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
                        <th>Inicio</th>
                        <th>Término</th>
                        <th>Estudiante</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($planes as $p)

                    <tr>
                        <td>{{ \Carbon\Carbon::parse($p->fecha_inicio)->format('d/m/Y') }}</td>

                        <td>
                            {{ $p->fecha_termino 
                                ? \Carbon\Carbon::parse($p->fecha_termino)->format('d/m/Y')
                                : '—' }}
                        </td>

                        <td>
                            {{ $p->estudiante->alumno->apellido_paterno }}
                            {{ $p->estudiante->alumno->apellido_materno }},
                            {{ $p->estudiante->alumno->nombre }}
                        </td>

                        <td>
                            {{-- Ver --}}
                            <a href="{{ route('pie.planes.show', $p->id) }}"
                               class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>

                            {{-- Historial --}}
                            <a href="{{ route('pie.historial.show', ['tipo' => 'plan', 'id' => $p->id]) }}"
                                class="btn btn-sm btn-secondary"
                                title="Ver en historial">
                                <i class="bi bi-clock-history"></i>
                            </a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            No se encontraron planes con los filtros aplicados.
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

    </div>

    <div class="card-footer">
        {{ $planes->links() }}
    </div>
</div>

@endsection
