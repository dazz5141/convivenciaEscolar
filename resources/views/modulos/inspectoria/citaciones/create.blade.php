@extends('layouts.app')

@section('title', 'Nueva Citación')

@section('content')
<div class="page-header">
    <h1 class="page-title">Nueva Citación de Apoderado</h1>
    <p class="text-muted">Agendar una nueva citación</p>
</div>

<form action="/modulos/citaciones" method="POST">
    <div class="form-section">
        <h5 class="form-section-title">Información de la Citación</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="alumno" class="form-label">Alumno <span class="text-danger">*</span></label>
                <select class="form-select" id="alumno" name="alumno" required>
                    <option value="">Seleccione un alumno</option>
                    <option>Juan Pérez Soto - 3° Básico A</option>
                    <option>María González López - 5° Básico B</option>
                    <option>Pedro Soto Ramírez - 7° Básico C</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="apoderado" class="form-label">Apoderado <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="apoderado" name="apoderado" placeholder="Nombre del apoderado" required>
            </div>
            <div class="col-md-4">
                <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>
            <div class="col-md-4">
                <label for="hora" class="form-label">Hora <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="hora" name="hora" required>
            </div>
            <div class="col-md-4">
                <label for="duracion" class="form-label">Duración (minutos)</label>
                <input type="number" class="form-control" id="duracion" name="duracion" value="30">
            </div>
            <div class="col-md-6">
                <label for="motivo" class="form-label">Motivo <span class="text-danger">*</span></label>
                <select class="form-select" id="motivo" name="motivo" required>
                    <option value="">Seleccione un motivo</option>
                    <option>Rendimiento académico</option>
                    <option>Conducta</option>
                    <option>Asistencia</option>
                    <option>Orientación</option>
                    <option>Otro</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="responsable" class="form-label">Responsable <span class="text-danger">*</span></label>
                <select class="form-select" id="responsable" name="responsable" required>
                    <option value="">Seleccione responsable</option>
                    <option>Inspector General</option>
                    <option>Orientador</option>
                    <option>Profesor Jefe</option>
                    <option>Director</option>
                </select>
            </div>
            <div class="col-12">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3" placeholder="Detalles adicionales de la citación"></textarea>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>
            Guardar Citación
        </button>
        <a href="/modulos/citaciones" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>
            Cancelar
        </a>
    </div>
</form>
@endsection
