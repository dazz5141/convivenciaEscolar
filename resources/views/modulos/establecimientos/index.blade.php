@extends('layouts.app')

@section('title', 'Establecimientos')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Establecimientos</h1>
        <p class="text-muted">Gestión de establecimientos del sistema</p>
    </div>

    @if(auth()->user()->rol_id === 1)
        <a href="{{ route('establecimientos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Nuevo Establecimiento
        </a>
    @endif
</div>

<div class="card card-table">

    {{-- FILTROS --}}
    <div class="card-header">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <input type="text" class="form-control" placeholder="Buscar por nombre o RBD...">
            </div>
            <div class="col-12 col-md-4">
                <select class="form-select">
                    <option value="">Todos</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <button class="btn btn-secondary w-100">
                    <i class="bi bi-funnel me-2"></i>Filtrar
                </button>
            </div>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>RBD</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($establecimientos as $est)
                        <tr>
                            <td>{{ $est->id }}</td>
                            <td>{{ $est->RBD }}</td>
                            <td>{{ $est->nombre }}</td>
                            <td>{{ $est->direccion }}</td>

                            <td>
                                @if($est->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Deshabilitado</span>
                                @endif
                            </td>

                            <td class="text-end table-actions">

                                {{-- VER --}}
                                <a href="{{ route('establecimientos.show', $est->id) }}"
                                   class="btn btn-sm btn-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- EDITAR SOLO ADMIN GENERAL --}}
                                @if(auth()->user()->rol_id === 1)
                                <a href="{{ route('establecimientos.edit', $est->id) }}"
                                   class="btn btn-sm btn-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif

                                {{-- HABILITAR / DESHABILITAR --}}
                                @if(auth()->user()->rol_id === 1)
                                    @if($est->activo)
                                        <form action="{{ route('establecimientos.disable', $est->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-sm btn-warning" title="Deshabilitar">
                                                <i class="bi bi-slash-circle"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('establecimientos.enable', $est->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-sm btn-success" title="Habilitar">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay registros disponibles.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

    {{-- PAGINACIÓN (opcional futuro) --}}
    <div class="card-footer">
        <nav>
            <ul class="pagination mb-0 justify-content-center">
                <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                <li class="page-item active"><a class="page-link">1</a></li>
                <li class="page-item disabled"><a class="page-link">Siguiente</a></li>
            </ul>
        </nav>
    </div>

</div>

@endsection
