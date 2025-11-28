@extends('layouts.app')

@section('title', 'Cursos')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Cursos</h1>
        <p class="text-muted">Gestión de cursos del establecimiento</p>
    </div>
    <div>
        @if(canAccess('cursos', 'create'))
        <a href="{{ route('cursos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Nuevo Curso
        </a>
        @endif
    </div>
</div>

<div class="card card-table">
    <div class="card-header">
        <form method="GET" action="{{ route('cursos.index') }}">
            <div class="row g-3">

                <div class="col-md-3">
                    <select name="anio" class="form-select">
                        <option value="">Año</option>
                        @foreach($anios as $a)
                            <option value="{{ $a->anio }}" {{ request('anio') == $a->anio ? 'selected' : '' }}>
                                {{ $a->anio }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="nivel" class="form-select">
                        <option value="">Nivel</option>
                        @foreach($niveles as $n)
                            <option value="{{ $n->nivel }}" {{ request('nivel') == $n->nivel ? 'selected' : '' }}>
                                {{ $n->nivel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="estado" class="form-select">
                        <option value="">Estado</option>
                        <option value="1" {{ request('estado') === "1" ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ request('estado') === "0" ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-secondary w-100">
                        <i class="bi bi-funnel me-2"></i> Filtrar
                    </button>
                </div>

            </div>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Año</th>
                        <th>Nivel</th>
                        <th>Letra</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($cursos as $c)
                    <tr>
                        <td>{{ $c->anio }}</td>
                        <td>{{ $c->nivel }}</td>
                        <td>{{ $c->letra }}</td>
                        <td>
                            <span class="badge bg-{{ $c->activo ? 'success' : 'secondary' }}">
                                {{ $c->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>

                        <td class="table-actions">

                            {{-- Ver --}}
                            @if(canAccess('cursos', 'view'))
                            <a href="{{ route('cursos.show', $c->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            @endif

                            {{-- Editar --}}
                            @if(canAccess('cursos', 'edit'))
                            <a href="{{ route('cursos.edit', $c->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No hay cursos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        {{ $cursos->links() }}
    </div>
</div>
@endsection
