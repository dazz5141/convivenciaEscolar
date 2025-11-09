@extends('layouts.app')

@section('title', 'Funcionarios')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Funcionarios</h1>
        <p class="text-muted">Gestión completa de funcionarios del establecimiento</p>
    </div>
    <div>
        <a href="{{ route('funcionarios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Nuevo Funcionario
        </a>
    </div>
</div>

<div class="card card-table">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-12 col-md-6">
                <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control" placeholder="Buscar por nombre, apellido o RUN...">
            </div>

            <div class="col-12 col-md-4">
                <select name="estado" class="form-select">
                    <option value="">— Estado —</option>
                    <option value="1" {{ request('estado')=='1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ request('estado')=='0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="col-12 col-md-2">
                <button class="btn btn-secondary w-100">
                    <i class="bi bi-funnel me-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>

    <div class="card-body">

        @if($funcionarios->count() == 0)
            <div class="alert alert-info">
                No se encontraron funcionarios registrados.
            </div>
        @else

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>RUN</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Tipo Contrato</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($funcionarios as $f)
                    <tr>
                        <td>{{ $f->run }}</td>

                        <td>
                            {{ $f->nombre }} {{ $f->apellido_paterno }} {{ $f->apellido_materno }}
                        </td>

                        <td>{{ $f->cargo->nombre }}</td>

                        <td>{{ $f->tipoContrato->nombre }}</td>

                        <td>
                            @if($f->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <a href="{{ route('funcionarios.show', $f->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('funcionarios.edit', $f->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>

                            @if($f->activo)
                                <form action="{{ route('funcionarios.disable', $f->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-warning" title="Deshabilitar">
                                        <i class="bi bi-slash-circle"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('funcionarios.enable', $f->id) }}" method="POST" class="d-inline">
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

        {{ $funcionarios->links() }}

        @endif
    </div>
</div>

@endsection
