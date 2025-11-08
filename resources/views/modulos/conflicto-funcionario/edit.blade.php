@extends('layouts.app')

@section('title', 'Editar Registro')

@section('content')
<div class="page-header">
    <h1 class="page-title">Editar Registro</h1>
</div>

<form action="/modulos/conflicto-funcionario/1" method="POST">
    <div class="form-section">
        <h5 class="form-section-title">Información</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="2025-11-08" required>
            </div>
            <div class="col-12">
                <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required>Información de ejemplo</textarea>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>
            Guardar Cambios
        </button>
        <a href="/modulos/conflicto-funcionario" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>
            Cancelar
        </a>
        <button type="button" class="btn btn-danger ms-auto" data-confirm-delete>
            <i class="bi bi-trash me-2"></i>
            Eliminar
        </button>
    </div>
</form>
@endsection
