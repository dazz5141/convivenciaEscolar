@extends('layouts.app')

@section('title', 'Seguimiento Emocional')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h1 class="page-title">Seguimiento Emocional</h1>
        <p class="text-muted">Registro y seguimiento del bienestar emocional de los estudiantes</p>
    </div>

    <a href="{{ route('seguimiento.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i> Nuevo Seguimiento
    </a>
</div>


{{-- ===========================
     FILTROS
=========================== --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filtros</h5>
    </div>

    <form method="GET" action="{{ route('seguimiento.index') }}">
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

                {{-- Estado --}}
                <div class="col-md-3">
                    <label class="form-label">Estado del seguimiento</label>
                    <select name="estado_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($estados as $e)
                            <option value="{{ $e->id }}" {{ request('estado_id') == $e->id ? 'selected' : '' }}>
                                {{ $e->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nivel emocional --}}
                <div class="col-md-3">
                    <label class="form-label">Nivel emocional</label>
                    <select name="nivel_emocional_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($niveles as $n)
                            <option value="{{ $n->id }}" {{ request('nivel_emocional_id') == $n->id ? 'selected' : '' }}>
                                {{ $n->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Fecha --}}
                <div class="col-md-3">
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



{{-- ===========================
     TABLA
=========================== --}}
<div class="card card-table">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno</th>
                        <th>Nivel</th>
                        <th>Estado</th>
                        <th>Evaluado por</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($seguimientos as $s)

                    <tr>
                        {{-- Fecha --}}
                        <td>{{ \Carbon\Carbon::parse($s->fecha)->format('d/m/Y') }}</td>

                        {{-- Alumno --}}
                        <td>
                            {{ $s->alumno->apellido_paterno }} {{ $s->alumno->apellido_materno }},
                            {{ $s->alumno->nombre }}
                            <br>
                            <span class="text-muted small">
                                {{ $s->alumno->curso->nombre ?? '—' }}
                            </span>
                        </td>

                        {{-- Nivel emocional --}}
                        <td>
                            <span class="badge bg-info">
                                {{ $s->nivel->nombre ?? 'No asignado' }}
                            </span>
                        </td>

                        {{-- Estado --}}
                        <td>
                            <span class="badge bg-{{ $s->estado->color ?? 'secondary' }}">
                                {{ $s->estado->nombre }}
                            </span>
                        </td>

                        {{-- Evaluador --}}
                        <td>
                            {{ $s->evaluador->apellido_paterno ?? '' }}
                            {{ $s->evaluador->apellido_materno ?? '' }},
                            {{ $s->evaluador->nombre ?? '' }}
                        </td>

                        <td class="table-actions">

                            <a href="{{ route('seguimiento.show', $s->id) }}"
                               class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('seguimiento.edit', $s->id) }}"
                               class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No se encontraron seguimientos con los filtros aplicados.
                        </td>
                    </tr>

                @endforelse

                </tbody>
            </table>
        </div>

    </div>

    <div class="card-footer">
        {{ $seguimientos->links() }}
    </div>
</div>

@endsection
