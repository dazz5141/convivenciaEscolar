@extends('layouts.app')

@section('title', 'Editar Establecimiento')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Establecimiento</h1>
</div>

<form action="{{ route('establecimientos.update', $establecimiento->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-section">
        <h5 class="form-section-title">Información General</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">RBD <span class="text-danger">*</span></label>
                <input type="text" name="RBD" class="form-control" value="{{ $establecimiento->RBD }}" required>
            </div>

            <div class="col-md-8">
                <label class="form-label">Nombre del Establecimiento <span class="text-danger">*</span></label>
                <input type="text" name="nombre" class="form-control" value="{{ $establecimiento->nombre }}" required>
            </div>

            <div class="col-12">
                <label class="form-label">Dirección <span class="text-danger">*</span></label>
                <input type="text" name="direccion" class="form-control" value="{{ $establecimiento->direccion }}" required>
            </div>

            <h5 class="form-section-title mt-4">Ubicación y Dependencia</h5>

            <div class="col-md-3">
                <label class="form-label">Dependencia <span class="text-danger">*</span></label>
                <select name="dependencia_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Región <span class="text-danger">*</span></label>
                <select name="region_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Provincia <span class="text-danger">*</span></label>
                <select name="provincia_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Comuna <span class="text-danger">*</span></label>
                <select name="comuna_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                </select>
            </div>

        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('establecimientos.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>
@endsection
