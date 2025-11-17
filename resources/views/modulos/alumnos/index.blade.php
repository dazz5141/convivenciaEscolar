@extends('layouts.app')

@section('title', 'Gestión de Alumnos')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Gestión de Alumnos</h1>
        <p class="text-muted">Administración de estudiantes del establecimiento</p>
    </div>
    <div>
        <a href="{{ route('alumnos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Nuevo Alumno
        </a>
    </div>
</div>

<div class="card card-table">
    <div class="card-header">
        <form method="GET" action="{{ route('alumnos.index') }}">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control" placeholder="Buscar por nombre o RUT...">
                </div>

                <div class="col-12 col-md-3">
                    <select name="curso_id" class="form-select">
                        <option value="">Todos los cursos</option>
                        @foreach($cursos as $c)
                            <option value="{{ $c->id }}" {{ request('curso_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <select name="estado" class="form-select">
                        <option value="">Estado</option>
                        <option value="1" {{ request('estado') === "1" ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ request('estado') === "0" ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <button class="btn btn-secondary w-100">
                        <i class="bi bi-funnel me-2"></i>Filtrar
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
                        <th>RUN</th>
                        <th>Nombre Completo</th>
                        <th>Curso</th>
                        <th>Apoderados</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($alumnos as $al)
                    <tr>
                        <td>{{ $al->run }}</td>
                        <td>{{ $al->nombre }} {{ $al->apellido_paterno }} {{ $al->apellido_materno }}</td>
                        <td>{{ $al->curso->nombre }}</td>
                        <td>
                            @foreach($al->apoderados as $ap)
                                <span class="badge bg-info"> {{ $ap->nombre_completo }} ({{ $ap->pivot->tipo }})</span>
                            @endforeach
                        </td>
                        <td>
                            <span class="badge bg-{{ $al->activo ? 'success' : 'secondary' }}">
                                {{ $al->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="table-actions">
                            <a href="{{ route('alumnos.show', $al->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('alumnos.edit', $al->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>

                            @if($al->activo)
                                <form action="{{ route('alumnos.disable', $al->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-warning" onclick="return confirm('Deshabilitar alumno?')">
                                        <i class="bi bi-slash-circle"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('alumnos.enable', $al->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Habilitar alumno?')">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No se encontraron alumnos.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        {{ $alumnos->links() }}
    </div>
</div>
@endsection
