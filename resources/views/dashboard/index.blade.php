@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Convivencia Escolar')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="text-muted">Resumen general del sistema de convivencia escolar</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small">Incidentes</div>
                        <h3 class="mb-0">156</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small">Alumnos</div>
                        <h3 class="mb-0">1,234</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small">Citaciones</div>
                        <h3 class="mb-0">42</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small">Casos Críticos</div>
                        <h3 class="mb-0">8</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Incidentes por Mes</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 8px;">
                    <div class="text-center">
                        <i class="bi bi-bar-chart" style="font-size: 4rem; color: #dee2e6;"></i>
                        <p class="text-muted mt-3">Gráfico de incidentes por mes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Distribución por Tipo</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 8px;">
                    <div class="text-center">
                        <i class="bi bi-pie-chart" style="font-size: 4rem; color: #dee2e6;"></i>
                        <p class="text-muted mt-3">Gráfico de distribución</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Incidentes Recientes</h5>
                <a href="/modulos/bitacora" class="btn btn-sm btn-primary">Ver Todos</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Alumno</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>08/11/2025</td>
                                <td>Juan Pérez</td>
                                <td>Conflicto</td>
                                <td><span class="badge bg-warning">En Proceso</span></td>
                            </tr>
                            <tr>
                                <td>07/11/2025</td>
                                <td>María González</td>
                                <td>Atraso</td>
                                <td><span class="badge bg-success">Resuelto</span></td>
                            </tr>
                            <tr>
                                <td>07/11/2025</td>
                                <td>Pedro Soto</td>
                                <td>Conducta</td>
                                <td><span class="badge bg-danger">Crítico</span></td>
                            </tr>
                            <tr>
                                <td>06/11/2025</td>
                                <td>Ana Martínez</td>
                                <td>Accidente</td>
                                <td><span class="badge bg-success">Resuelto</span></td>
                            </tr>
                            <tr>
                                <td>06/11/2025</td>
                                <td>Carlos Díaz</td>
                                <td>Conflicto</td>
                                <td><span class="badge bg-warning">En Proceso</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Citaciones Pendientes</h5>
                <a href="/modulos/citaciones" class="btn btn-sm btn-primary">Ver Todas</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Apoderado: Rosa Fernández</h6>
                                <p class="mb-1 text-muted small">Alumno: Javier Fernández - 3° Básico A</p>
                            </div>
                            <small class="text-muted">10/11/2025</small>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Apoderado: Luis Morales</h6>
                                <p class="mb-1 text-muted small">Alumno: Camila Morales - 5° Básico B</p>
                            </div>
                            <small class="text-muted">11/11/2025</small>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Apoderado: Patricia Rojas</h6>
                                <p class="mb-1 text-muted small">Alumno: Diego Rojas - 7° Básico C</p>
                            </div>
                            <small class="text-muted">12/11/2025</small>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Apoderado: Jorge Silva</h6>
                                <p class="mb-1 text-muted small">Alumno: Valentina Silva - 2° Medio A</p>
                            </div>
                            <small class="text-muted">13/11/2025</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
