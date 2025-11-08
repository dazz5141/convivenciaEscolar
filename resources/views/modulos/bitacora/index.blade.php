@extends('layouts.app')

@section('title', 'Bitácora de Incidentes')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Bitácora de Incidentes</h1>
        <p class="text-muted">Registro y seguimiento de incidentes de convivencia escolar</p>
    </div>
    <div>
        <a href="/modulos/bitacora/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Nuevo Incidente
        </a>
    </div>
</div>

<div class="card card-table">
    <div class="card-header">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <input type="text" class="form-control" placeholder="Buscar por alumno...">
            </div>
            <div class="col-12 col-md-3">
                <select class="form-select">
                    <option value="">Todos los tipos</option>
                    <option>Conflicto</option>
                    <option>Conducta</option>
                    <option>Atraso</option>
                    <option>Accidente</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select class="form-select">
                    <option value="">Todos los estados</option>
                    <option>En Proceso</option>
                    <option>Resuelto</option>
                    <option>Crítico</option>
                </select>
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
                        <th>Alumno</th>
                        <th>Curso</th>
                        <th>Tipo</th>
                        <th>Gravedad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#001</td>
                        <td>08/11/2025</td>
                        <td>Juan Pérez Soto</td>
                        <td>3° Básico A</td>
                        <td>Conflicto</td>
                        <td><span class="badge bg-warning">Media</span></td>
                        <td><span class="badge bg-warning">En Proceso</span></td>
                        <td class="table-actions">
                            <a href="/modulos/bitacora/1" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/bitacora/1/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>#002</td>
                        <td>07/11/2025</td>
                        <td>María González López</td>
                        <td>5° Básico B</td>
                        <td>Atraso</td>
                        <td><span class="badge bg-info">Baja</span></td>
                        <td><span class="badge bg-success">Resuelto</span></td>
                        <td class="table-actions">
                            <a href="/modulos/bitacora/2" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/bitacora/2/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>#003</td>
                        <td>07/11/2025</td>
                        <td>Pedro Soto Ramírez</td>
                        <td>7° Básico C</td>
                        <td>Conducta</td>
                        <td><span class="badge bg-danger">Alta</span></td>
                        <td><span class="badge bg-danger">Crítico</span></td>
                        <td class="table-actions">
                            <a href="/modulos/bitacora/3" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/bitacora/3/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>#004</td>
                        <td>06/11/2025</td>
                        <td>Ana Martínez Silva</td>
                        <td>2° Medio A</td>
                        <td>Accidente</td>
                        <td><span class="badge bg-warning">Media</span></td>
                        <td><span class="badge bg-success">Resuelto</span></td>
                        <td class="table-actions">
                            <a href="/modulos/bitacora/4" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/bitacora/4/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>#005</td>
                        <td>06/11/2025</td>
                        <td>Carlos Díaz Muñoz</td>
                        <td>4° Básico A</td>
                        <td>Conflicto</td>
                        <td><span class="badge bg-warning">Media</span></td>
                        <td><span class="badge bg-warning">En Proceso</span></td>
                        <td class="table-actions">
                            <a href="/modulos/bitacora/5" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/bitacora/5/edit" class="btn btn-sm btn-primary" title="Editar">
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
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection
