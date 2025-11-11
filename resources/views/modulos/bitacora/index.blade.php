@extends('layouts.app')

@section('title', 'Bitácora de Incidentes')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h1 class="page-title">Bitácora de Incidentes</h1>
        <p class="text-muted">Registro y seguimiento de incidentes dentro del establecimiento</p>
    </div>

    <a href="{{ route('bitacora.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i> Nuevo Incidente
    </a>
</div>


{{-- ============================
     FILTROS
============================ --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filtros</h5>
    </div>

    <form method="GET" action="{{ route('bitacora.index') }}">
        <div class="card-body">
            <div class="row g-3">

                {{-- Alumno --}}
                <div class="col-md-3">
                    <label class="form-label">Alumno</label>
                    <select name="alumno_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($alumnos as $a)
                            <option value="{{ $a->id }}" {{ request('alumno_id') == $a->id ? 'selected' : '' }}>
                                {{ $a->apellido_paterno }} {{ $a->apellido_materno }}, {{ $a->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tipo --}}
                <div class="col-md-3">
                    <label class="form-label">Tipo de incidente</label>
                    <input type="text" name="tipo" class="form-control" placeholder="Ej: Conflicto"
                           value="{{ request('tipo') }}">
                </div>

                {{-- Estado --}}
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="estado_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($estados as $e)
                            <option value="{{ $e->id }}" {{ request('estado_id') == $e->id ? 'selected' : '' }}>
                                {{ $e->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Curso --}}
                <div class="col-md-3">
                    <label class="form-label">Curso</label>
                    <select name="curso_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($cursos as $c)
                            <option value="{{ $c->id }}" {{ request('curso_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nombre }}
                            </option>
                        @endforeach
                    </select>
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
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Involucrados</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($incidentes as $i)

                    <tr>
                        <td>{{ \Carbon\Carbon::parse($i->fecha)->format('d/m/Y') }}</td>

                        <td>{{ $i->tipo_incidente }}</td>

                        <td>
                            <span class="badge bg-primary">
                                {{ $i->estado->nombre ?? 'Sin estado' }}
                            </span>
                        </td>

                        {{-- Involucrados resumen --}}
                        <td>
                            @foreach($i->involucrados as $inv)
                                <div class="d-flex justify-content-between align-items-center small py-1 border-bottom">
                                    <div>
                                        <strong>{{ ucfirst($inv->rol) }}:</strong>
                                        {{ $inv->alumno->apellido_paterno }} {{ $inv->alumno->apellido_materno }}
                                    </div>

                                    <div class="text-muted ms-3">
                                        {{ $inv->alumno->curso->nombre ?? '—' }}
                                    </div>
                                </div>
                            @endforeach
                        </td>

                        <td class="table-actions">

                            <a href="{{ route('bitacora.show', $i->id) }}"
                               class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('bitacora.edit', $i->id) }}"
                               class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No se encontraron incidentes con los filtros aplicados.
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

    </div>

    <div class="card-footer">
        {{ $incidentes->links() }}
    </div>
</div>

@endsection
