@extends('layouts.app')

@section('title', 'Apoderados')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Apoderados</h1>
        <p class="text-muted">Gestión completa de apoderados del establecimiento</p>
    </div>
    <div>
        <a href="{{ route('apoderados.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Nuevo Apoderado
        </a>
    </div>
</div>

<div class="card card-table">

    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-12 col-md-6">
                <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control"
                       placeholder="Buscar por nombre, apellido o RUN...">
            </div>

            <div class="col-12 col-md-4">
                <select name="estado" class="form-select">
                    <option value="">— Estado —</option>
                    <option value="1" {{ request('estado')=='1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ request('estado')=='0' ? 'selected' : '' }}>Deshabilitado</option>
                </select>
            </div>

            <div class="col-12 col-md-2">
                <button class="btn btn-secondary w-100">
                    <i class="bi bi-funnel me-2"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <div class="card-body">

        @if($apoderados->count() == 0)
            <div class="alert alert-info">
                No se encontraron apoderados registrados.
            </div>
        @else

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>RUN</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Alumnos Asociados</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($apoderados as $a)
                    <tr>
                        <td>{{ $a->run }}</td>

                        <td>
                            {{ $a->nombre }} {{ $a->apellido_paterno }} {{ $a->apellido_materno }}
                        </td>

                        <td>{{ $a->telefono ?? '—' }}</td>

                        <td>
                            <span class="badge bg-info">
                                {{ $a->alumnos->count() }} alumno(s)
                            </span>
                        </td>

                        <td>
                            @if($a->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Deshabilitado</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <a href="{{ route('apoderados.show', $a->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('apoderados.edit', $a->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>

                            @if($a->activo)
                                <form action="{{ route('apoderados.disable', $a->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-warning" title="Deshabilitar">
                                        <i class="bi bi-slash-circle"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('apoderados.enable', $a->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-success" title="Habilitar">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $apoderados->links() }}

        @endif
    </div>
</div>

@endsection
