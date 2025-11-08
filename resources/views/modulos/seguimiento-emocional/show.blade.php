@extends('layouts.app')

@section('title', 'Detalle del Registro')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-3 mb-md-0">
        <h1 class="page-title">Detalle del Registro</h1>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="/modulos/seguimiento-emocional/1/edit" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>
            Editar
        </a>
        <a href="/modulos/seguimiento-emocional" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Volver
        </a>
    </div>
</div>

<div class="form-section">
    <h5 class="form-section-title">Información</h5>
    <div class="detail-item">
        <div class="detail-label">Fecha:</div>
        <div class="detail-value">08 de Noviembre de 2025</div>
    </div>
    <div class="detail-item">
        <div class="detail-label">Descripción:</div>
        <div class="detail-value">Información de ejemplo del registro</div>
    </div>
    <div class="detail-item">
        <div class="detail-label">Estado:</div>
        <div class="detail-value"><span class="badge bg-success">Activo</span></div>
    </div>
</div>
@endsection
