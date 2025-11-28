@extends('layouts.app')

@section('title', 'Estudiantes PIE')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h1 class="page-title">Estudiantes PIE</h1>
        <p class="text-muted">Listado de alumnos integrados al Programa de Integración Escolar</p>
    </div>

    {{-- =====================================================
         PERMISO: crear estudiante PIE
    ====================================================== --}}
    @if(canAccess('pie-estudiantes', 'create'))
    <a href="{{ route('pie.estudiantes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i> Nuevo Estudiante PIE
    </a>
    @endif
</div>


<div class="card card-table">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Curso</th>
                    <th>Diagnóstico</th>
                    <th>Fecha Ingreso</th>
                    <th>Estado</th>
                    <th width="140">Acciones</th>
                </tr>
                </thead>

                <tbody>
                @forelse($estudiantes as $e)
                    <tr>

                        {{-- ALUMNO --}}
                        <td>
                            {{ $e->alumno->apellido_paterno }}
                            {{ $e->alumno->apellido_materno }},
                            {{ $e->alumno->nombre }}
                        </td>

                        {{-- CURSO --}}
                        <td>{{ $e->alumno->curso->nombre ?? '—' }}</td>

                        {{-- DIAGNOSTICO --}}
                        <td>{{ $e->diagnostico ?? 'No indica' }}</td>

                        {{-- FECHA INGRESO --}}
                        <td>{{ $e->fecha_ingreso ? $e->fecha_ingreso->format('d/m/Y') : '—' }}</td>

                        {{-- ESTADO PIE --}}
                        <td>
                            @if ($e->fecha_egreso)
                                <span class="badge bg-danger">Egresado</span>
                            @else
                                <span class="badge bg-success">Activo</span>
                            @endif
                        </td>

                        {{-- ACCIONES --}}
                        <td class="table-actions">

                            {{-- PERMISO: ver estudiante PIE --}}
                            @if(canAccess('pie-estudiantes', 'view'))
                            <a href="{{ route('pie.estudiantes.show', $e->id) }}"
                               class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            @endif

                            {{-- PERMISO: ver historial --}}
                            @if(canAccess('pie-historial', 'view'))
                            <a href="{{ route('pie.historial.index', $e->id) }}"
                               class="btn btn-sm btn-primary" title="Historial">
                                <i class="bi bi-clock-history"></i>
                            </a>
                            @endif

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No existen estudiantes PIE registrados.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>

    <div class="card-footer">
        {{ $estudiantes->links() }}
    </div>
</div>

@endsection
