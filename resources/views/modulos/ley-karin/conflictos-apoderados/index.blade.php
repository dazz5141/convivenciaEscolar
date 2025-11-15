@extends('layouts.app')

@section('title', 'Conflictos con Apoderados')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Conflictos con Apoderados</h1>
        <p class="text-muted">Gestión de Conflictos con Apoderados_LOWER</p>
    </div>
    <div>
        <a href="/modulos/conflicto-apoderado/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Nuevo Registro
        </a>
    </div>
</div>

<div class="card card-table">
    <div class="card-header">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <input type="text" class="form-control" placeholder="Buscar...">
            </div>
            <div class="col-12 col-md-4">
                <input type="date" class="form-control">
            </div>
            <div class="col-12 col-md-2">
                <button class="btn btn-secondary w-100">
                    <i class="bi bi-funnel me-2"></i>Filtrar
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#001</td>
                        <td>08/11/2025</td>
                        <td>Registro de ejemplo</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="table-actions">
                            <a href="/modulos/conflicto-apoderado/1" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/conflicto-apoderado/1/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <nav>
            <ul class="pagination mb-0 justify-content-center">
                <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection
