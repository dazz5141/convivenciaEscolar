@extends('layouts.app')

@section('title', 'Detalle de Citación')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Detalle de Citación</h1>
        <p class="text-muted">Información completa de la citación</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="/modulos/citaciones/1/edit" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>
            Editar
        </a>
        <a href="/modulos/citaciones" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Volver
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="form-section">
            <h5 class="form-section-title">Información de la Citación</h5>
            <div class="detail-item">
                <div class="detail-label">Alumno:</div>
                <div class="detail-value">Javier Fernández - 3° Básico A</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Apoderado:</div>
                <div class="detail-value">Rosa Fernández</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Fecha:</div>
                <div class="detail-value">10 de Noviembre de 2025</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Hora:</div>
                <div class="detail-value">15:00 hrs</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Duración:</div>
                <div class="detail-value">30 minutos</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Motivo:</div>
                <div class="detail-value">Rendimiento académico</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Responsable:</div>
                <div class="detail-value">Orientador</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Estado:</div>
                <div class="detail-value"><span class="badge bg-warning">Pendiente</span></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Observaciones:</div>
                <div class="detail-value">Revisar notas del primer semestre. Conversar sobre estrategias de estudio en casa.</div>
            </div>
        </div>

        <div class="form-section">
            <h5 class="form-section-title">Acta de la Reunión</h5>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                El acta se completará una vez realizada la citación.
            </div>
            <textarea class="form-control" rows="5" placeholder="Resumen de la reunión y acuerdos tomados..."></textarea>
            <button class="btn btn-primary mt-3">
                <i class="bi bi-save me-2"></i>
                Guardar Acta
            </button>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Acciones</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-success btn-sm">
                        <i class="bi bi-check-circle me-2"></i>
                        Marcar como Realizada
                    </button>
                    <button class="btn btn-warning btn-sm">
                        <i class="bi bi-clock me-2"></i>
                        Reagendar
                    </button>
                    <button class="btn btn-danger btn-sm">
                        <i class="bi bi-x-circle me-2"></i>
                        Cancelar Citación
                    </button>
                    <button class="btn btn-info btn-sm">
                        <i class="bi bi-printer me-2"></i>
                        Imprimir Citación
                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Historial</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-circle-fill text-primary me-2" style="font-size: 0.5rem; margin-top: 0.5rem;"></i>
                        <div>
                            <div class="fw-semibold small">Citación creada</div>
                            <div class="text-muted small">05/11/2025 10:00</div>
                            <div class="text-muted small">Por: Inspector General</div>
                        </div>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-circle-fill text-info me-2" style="font-size: 0.5rem; margin-top: 0.5rem;"></i>
                        <div>
                            <div class="fw-semibold small">Notificación enviada</div>
                            <div class="text-muted small">05/11/2025 10:15</div>
                            <div class="text-muted small">Vía: Email y SMS</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
