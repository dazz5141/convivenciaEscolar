@extends('layouts.app')

@section('title', 'Detalle Incidente')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Detalle de Incidente #001</h1>
        <p class="text-muted">Información completa del incidente registrado</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="/modulos/bitacora/1/edit" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>
            Editar
        </a>
        <a href="/modulos/bitacora" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Volver
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="form-section">
            <h5 class="form-section-title">Información del Incidente</h5>
            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">08 de Noviembre de 2025</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Hora:</div>
                <div class="detail-value">10:30 hrs</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Alumno:</div>
                <div class="detail-value">Juan Pérez Soto</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Curso:</div>
                <div class="detail-value">3° Básico A</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Tipo de Incidente:</div>
                <div class="detail-value">Conflicto entre alumnos</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Gravedad:</div>
                <div class="detail-value"><span class="badge bg-warning">Media</span></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Lugar:</div>
                <div class="detail-value">Patio principal</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Descripción:</div>
                <div class="detail-value">
                    Discusión entre dos alumnos durante el recreo. Se intervino de inmediato por el inspector de patio. Los alumnos fueron separados y llevados a inspectoría para mediar el conflicto.
                </div>
            </div>
        </div>

        <div class="form-section">
            <h5 class="form-section-title">Testigos y Reportantes</h5>
            <div class="detail-item">
                <div class="detail-label">Reportado por:</div>
                <div class="detail-value">María Fernández</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Cargo:</div>
                <div class="detail-value">Inspector de Patio</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Testigos:</div>
                <div class="detail-value">Carlos Ramírez (Alumno), Pedro Torres (Profesor)</div>
            </div>
        </div>

        <div class="form-section">
            <h5 class="form-section-title">Seguimiento y Acciones</h5>
            <div class="detail-item">
                <div class="detail-label">Estado:</div>
                <div class="detail-value"><span class="badge bg-warning">En Proceso</span></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Responsable:</div>
                <div class="detail-value">Inspector General</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Acciones Tomadas:</div>
                <div class="detail-value">
                    Se separó a los alumnos. Se citó a los apoderados para reunión el 10/11/2025. Se realizará mediación con orientador.
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Apoderado Notificado:</div>
                <div class="detail-value">
                    <i class="bi bi-check-circle-fill text-success me-1"></i> Sí
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Historial</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-circle-fill text-primary me-2" style="font-size: 0.5rem; margin-top: 0.5rem;"></i>
                        <div>
                            <div class="fw-semibold">Incidente creado</div>
                            <div class="text-muted small">08/11/2025 10:45</div>
                            <div class="text-muted small">Por: María Fernández</div>
                        </div>
                    </div>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-circle-fill text-warning me-2" style="font-size: 0.5rem; margin-top: 0.5rem;"></i>
                        <div>
                            <div class="fw-semibold">Apoderado notificado</div>
                            <div class="text-muted small">08/11/2025 11:00</div>
                            <div class="text-muted small">Por: Inspector General</div>
                        </div>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-circle-fill text-info me-2" style="font-size: 0.5rem; margin-top: 0.5rem;"></i>
                        <div>
                            <div class="fw-semibold">Citación programada</div>
                            <div class="text-muted small">08/11/2025 11:30</div>
                            <div class="text-muted small">Por: Inspector General</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Documentos Adjuntos</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                        <span>reporte_incidente.pdf</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-file-earmark-image text-primary me-2"></i>
                        <span>foto_evidencia.jpg</span>
                    </a>
                </div>
                <button class="btn btn-sm btn-outline-primary w-100 mt-3">
                    <i class="bi bi-paperclip me-2"></i>
                    Adjuntar Documento
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
