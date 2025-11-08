@extends('layouts.app')

@section('title', 'Detalle del Alumno')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Juan Pérez Soto</h1>
        <p class="text-muted">RUT: 12.345.678-9</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="/modulos/alumnos/1/edit" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>
            Editar
        </a>
        <a href="/modulos/alumnos" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Volver
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="form-section">
            <h5 class="form-section-title">Datos Personales</h5>
            <div class="detail-item">
                <div class="detail-label">RUT:</div>
                <div class="detail-value">12.345.678-9</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Nombre Completo:</div>
                <div class="detail-value">Juan Pérez Soto</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Fecha de Nacimiento:</div>
                <div class="detail-value">15 de Marzo de 2015</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Género:</div>
                <div class="detail-value">Masculino</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Nacionalidad:</div>
                <div class="detail-value">Chilena</div>
            </div>
        </div>

        <div class="form-section">
            <h5 class="form-section-title">Información Académica</h5>
            <div class="detail-item">
                <div class="detail-label">Curso:</div>
                <div class="detail-value">3° Básico A</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Fecha de Ingreso:</div>
                <div class="detail-value">01 de Marzo de 2021</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Estado:</div>
                <div class="detail-value"><span class="badge bg-success">Activo</span></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Programa PIE:</div>
                <div class="detail-value">No participa</div>
            </div>
        </div>

        <div class="form-section">
            <h5 class="form-section-title">Datos del Apoderado</h5>
            <div class="detail-item">
                <div class="detail-label">RUT:</div>
                <div class="detail-value">11.111.111-1</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">Rosa Soto Díaz</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Teléfono:</div>
                <div class="detail-value">+56 9 8765 4321</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Email:</div>
                <div class="detail-value">rosa.soto@ejemplo.cl</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Dirección:</div>
                <div class="detail-value">Av. Los Heroes 1234, Santiago</div>
            </div>
        </div>

        <div class="form-section">
            <h5 class="form-section-title">Contacto de Emergencia</h5>
            <div class="detail-item">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">Pedro Pérez</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Teléfono:</div>
                <div class="detail-value">+56 9 7654 3210</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Observaciones:</div>
                <div class="detail-value">Alérgico a maní y mariscos.</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Estadísticas</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Asistencia</span>
                        <span class="fw-bold">92%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: 92%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Incidentes</span>
                        <span class="fw-bold">3</span>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Citaciones</span>
                        <span class="fw-bold">2</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/modulos/bitacora/create" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-journal-text me-2"></i>
                        Registrar Incidente
                    </a>
                    <a href="/modulos/citaciones/create" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-calendar-check me-2"></i>
                        Crear Citación
                    </a>
                    <a href="/modulos/seguimiento-emocional/create" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-heart-pulse me-2"></i>
                        Seguimiento Emocional
                    </a>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Historial Reciente</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-circle-fill text-warning me-2" style="font-size: 0.5rem; margin-top: 0.5rem;"></i>
                        <div>
                            <div class="fw-semibold small">Incidente registrado</div>
                            <div class="text-muted small">08/11/2025</div>
                        </div>
                    </div>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-circle-fill text-success me-2" style="font-size: 0.5rem; margin-top: 0.5rem;"></i>
                        <div>
                            <div class="fw-semibold small">Citación completada</div>
                            <div class="text-muted small">05/11/2025</div>
                        </div>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-circle-fill text-info me-2" style="font-size: 0.5rem; margin-top: 0.5rem;"></i>
                        <div>
                            <div class="fw-semibold small">Asistencia registrada</div>
                            <div class="text-muted small">08/11/2025</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
