@extends('layouts.app')

@section('title', 'Seguimiento Emocional')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Seguimiento Emocional</h1>
        <p class="text-muted">Monitoreo del bienestar emocional de los estudiantes</p>
    </div>
    <div>
        <a href="/modulos/seguimiento-emocional/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Nuevo Registro
        </a>
    </div>
</div>

<div class="card card-table">
    <div class="card-header">
        <div class="row g-3">
            <div class="col-12 col-md-5">
                <input type="text" class="form-control" placeholder="Buscar por alumno...">
            </div>
            <div class="col-12 col-md-3">
                <select class="form-select">
                    <option value="">Estado emocional</option>
                    <option>Positivo</option>
                    <option>Neutral</option>
                    <option>Preocupante</option>
                    <option>Crítico</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
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
                        <th>Fecha</th>
                        <th>Alumno</th>
                        <th>Curso</th>
                        <th>Estado Emocional</th>
                        <th>Nivel de Alerta</th>
                        <th>Responsable</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>08/11/2025</td>
                        <td>María González</td>
                        <td>5° Básico B</td>
                        <td>Preocupada</td>
                        <td><span class="badge bg-warning">Media</span></td>
                        <td>Psicólogo</td>
                        <td class="table-actions">
                            <a href="/modulos/seguimiento-emocional/1" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/seguimiento-emocional/1/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>07/11/2025</td>
                        <td>Pedro Soto</td>
                        <td>7° Básico C</td>
                        <td>Ansioso</td>
                        <td><span class="badge bg-danger">Alta</span></td>
                        <td>Orientador</td>
                        <td class="table-actions">
                            <a href="/modulos/seguimiento-emocional/2" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/seguimiento-emocional/2/edit" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-confirm-delete title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>06/11/2025</td>
                        <td>Ana Martínez</td>
                        <td>2° Medio A</td>
                        <td>Positivo</td>
                        <td><span class="badge bg-success">Baja</span></td>
                        <td>Orientador</td>
                        <td class="table-actions">
                            <a href="/modulos/seguimiento-emocional/3" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/modulos/seguimiento-emocional/3/edit" class="btn btn-sm btn-primary" title="Editar">
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
