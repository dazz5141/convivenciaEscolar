@extends('layouts.app')

@section('title', 'Gestión de Alumnos')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Gestión de Alumnos</h1>
        <p class="text-muted">Administración de estudiantes del establecimiento</p>
    </div>
    <div>
        <a href="/modulos/alumnos/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Nuevo Alumno
        </a>
    </div>
</div>

<div class="card card-table">
    <div class="card-header">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <input type="text" class="form-control" placeholder="Buscar por nombre o RUT...">
            </div>
            <div class="col-12 col-md-3">
                <select class="form-select">
                    <option value="">Todos los cursos</option>
                    <option>1° Básico A</option>
                    <option>2° Básico A</option>
                    <option>3° Básico A</option>
                    <option>4° Básico A</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select class="form-select">
                    <option value="">Estado</option>
                    <option>Activo</option>
                    <option>Retirado</option>
                    <option>Suspendido</option>
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
                        <th>RUT</th>
                        <th>Nombre Completo</th>
                        <th>Curso</th>
                        <th>Apoderado</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>12.345.678-9</td>
                        <td>Juan Pérez Soto</td>
                        <td>3° Básico A</td>
                        <td>Rosa Soto Díaz</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="table-actions">
                            <a href="/modulos/alumnos/1" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/alumnos/1/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>23.456.789-0</td>
                        <td>María González López</td>
                        <td>5° Básico B</td>
                        <td>Luis González Pérez</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="table-actions">
                            <a href="/modulos/alumnos/2" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/alumnos/2/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>34.567.890-1</td>
                        <td>Pedro Soto Ramírez</td>
                        <td>7° Básico C</td>
                        <td>Carmen Ramírez Silva</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="table-actions">
                            <a href="/modulos/alumnos/3" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/alumnos/3/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>45.678.901-2</td>
                        <td>Ana Martínez Silva</td>
                        <td>2° Medio A</td>
                        <td>Jorge Martínez Rojas</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="table-actions">
                            <a href="/modulos/alumnos/4" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/alumnos/4/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>56.789.012-3</td>
                        <td>Carlos Díaz Muñoz</td>
                        <td>4° Básico A</td>
                        <td>Patricia Muñoz Torres</td>
                        <td><span class="badge bg-warning">Suspendido</span></td>
                        <td class="table-actions">
                            <a href="/modulos/alumnos/5" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/alumnos/5/edit" class="btn btn-sm btn-primary" title="Editar">
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
