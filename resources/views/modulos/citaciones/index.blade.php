@extends('layouts.app')

@section('title', 'Citaciones de Apoderados')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Citaciones de Apoderados</h1>
        <p class="text-muted">Gestión y seguimiento de citaciones a apoderados</p>
    </div>
    <div>
        <a href="/modulos/citaciones/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Nueva Citación
        </a>
    </div>
</div>

<div class="card card-table">
    <div class="card-header">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <input type="text" class="form-control" placeholder="Buscar por apoderado o alumno...">
            </div>
            <div class="col-12 col-md-3">
                <select class="form-select">
                    <option value="">Todos los estados</option>
                    <option>Pendiente</option>
                    <option>Confirmada</option>
                    <option>Realizada</option>
                    <option>Cancelada</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <input type="date" class="form-control" placeholder="Fecha">
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
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Apoderado</th>
                        <th>Alumno</th>
                        <th>Motivo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>10/11/2025</td>
                        <td>15:00</td>
                        <td>Rosa Fernández</td>
                        <td>Javier Fernández - 3° Básico A</td>
                        <td>Rendimiento académico</td>
                        <td><span class="badge bg-warning">Pendiente</span></td>
                        <td class="table-actions">
                            <a href="/modulos/citaciones/1" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/citaciones/1/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>11/11/2025</td>
                        <td>16:30</td>
                        <td>Luis Morales</td>
                        <td>Camila Morales - 5° Básico B</td>
                        <td>Conducta</td>
                        <td><span class="badge bg-info">Confirmada</span></td>
                        <td class="table-actions">
                            <a href="/modulos/citaciones/2" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/citaciones/2/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>12/11/2025</td>
                        <td>14:00</td>
                        <td>Patricia Rojas</td>
                        <td>Diego Rojas - 7° Básico C</td>
                        <td>Asistencia</td>
                        <td><span class="badge bg-warning">Pendiente</span></td>
                        <td class="table-actions">
                            <a href="/modulos/citaciones/3" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/citaciones/3/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>05/11/2025</td>
                        <td>15:30</td>
                        <td>Jorge Silva</td>
                        <td>Valentina Silva - 2° Medio A</td>
                        <td>Orientación vocacional</td>
                        <td><span class="badge bg-success">Realizada</span></td>
                        <td class="table-actions">
                            <a href="/modulos/citaciones/4" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/citaciones/4/edit" class="btn btn-sm btn-primary" title="Editar">
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
                <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection
