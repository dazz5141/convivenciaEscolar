@extends('layouts.app')

@section('title', 'Crear Registro')

@section('content')
<div class="page-header">
    <h1 class="page-title">Crear Nuevo Registro</h1>
</div>

<form action="/modulos/pie" method="POST">
    <div class="form-section">
        <h5 class="form-section-title">Información</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>
            <div class="col-12">
                <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>
            Guardar
        </button>
        <a href="/modulos/pie" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>
            Cancelar
        </a>
    </div>
</form>
@endsection
