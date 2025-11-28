@extends('layouts.app')

@section('title', 'Establecimientos')

@section('content')

{{-- PERMISO --}}
@if(!canAccess('establecimientos', 'view'))
    @php(abort(403, 'No tienes permisos para ver establecimientos.'))
@endif

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Establecimientos</h1>
        <p class="text-muted">Administración de establecimientos registrados en el sistema</p>
    </div>

    <a href="{{ route('establecimientos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i> Nuevo Establecimiento
    </a>
</div>

<div class="card card-table mt-3">

    {{-- FILTROS --}}
    <div class="card-header">
        <form method="GET" action="{{ route('establecimientos.index') }}">

            <div class="row g-3 align-items-end">

                {{-- Buscar --}}
                <div class="col-md-6">
                    <input type="text"
                           name="buscar"
                           class="form-control"
                           placeholder="Buscar por nombre o RBD..."
                           value="{{ request('buscar') }}">
                </div>

                {{-- Estado --}}
                <div class="col-md-3">
                    <select name="estado" class="form-select">
                        <option value="">— Estado —</option>
                        <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                {{-- Botón Filtrar --}}
                <div class="col-md-3 text-end">
                    <button class="btn btn-secondary w-100">
                        <i class="bi bi-funnel me-1"></i> Filtrar
                    </button>
                </div>

            </div>

        </form>
    </div>

    {{-- TABLA --}}
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="col-rbd">RBD</th>
                        <th>Nombre</th>
                        <th>Dependencia</th>
                        <th>Región</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($establecimientos as $est)
                    <tr>
                        <td class="col-rbd">{{ $est->RBD }}</td>
                        <td>{{ $est->nombre }}</td>
                        <td>{{ $est->dependencia->nombre }}</td>
                        <td>{{ $est->region->nombre }}</td>

                        <td>
                            @if($est->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>

                        <td class="text-end">

                            {{-- VER --}}
                            <a href="{{ route('establecimientos.show', $est->id) }}"
                               class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>

                            {{-- EDITAR --}}
                            <a href="{{ route('establecimientos.edit', $est->id) }}"
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>

                            {{-- ACTIVAR / DESACTIVAR --}}
                            @if($est->activo)
                                <form action="{{ route('establecimientos.disable', $est->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-danger"
                                            title="Deshabilitar">
                                        <i class="bi bi-slash-circle"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('establecimientos.enable', $est->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-success"
                                            title="Habilitar">
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

    </div>
</div>

@endsection
