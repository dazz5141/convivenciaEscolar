@extends('layouts.app')

@section('title', 'Editar Incidente')

@section('content')
<div class="page-header">
    <h1 class="page-title">Editar Incidente #001</h1>
    <p class="text-muted">Modificar información del incidente registrado</p>
</div>

<form action="/modulos/bitacora/1" method="POST">
    <div class="form-section">
        <h5 class="form-section-title">Información del Incidente</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="fecha" class="form-label">Fecha del Incidente <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="2025-11-08" required>
            </div>
            <div class="col-md-6">
                <label for="hora" class="form-label">Hora del Incidente <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="hora" name="hora" value="10:30" required>
            </div>
            <div class="col-md-6">
                <label for="alumno" class="form-label">Alumno Involucrado <span class="text-danger">*</span></label>
                <select class="form-select" id="alumno" name="alumno" required>
                    <option value="">Seleccione un alumno</option>
                    <option selected>Juan Pérez Soto - 3° Básico A</option>
                    <option>María González López - 5° Básico B</option>
                    <option>Pedro Soto Ramírez - 7° Básico C</option>
                    <option>Ana Martínez Silva - 2° Medio A</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="tipo" class="form-label">Tipo de Incidente <span class="text-danger">*</span></label>
                <select class="form-select" id="tipo" name="tipo" required>
                    <option value="">Seleccione un tipo</option>
                    <option selected>Conflicto entre alumnos</option>
                    <option>Problema de conducta</option>
                    <option>Atraso reiterado</option>
                    <option>Accidente escolar</option>
                    <option>Otros</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="gravedad" class="form-label">Nivel de Gravedad <span class="text-danger">*</span></label>
                <select class="form-select" id="gravedad" name="gravedad" required>
                    <option value="">Seleccione gravedad</option>
                    <option>Baja</option>
                    <option selected>Media</option>
                    <option>Alta</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="lugar" class="form-label">Lugar del Incidente</label>
                <input type="text" class="form-control" id="lugar" name="lugar" value="Patio principal">
            </div>
            <div class="col-12">
                <label for="descripcion" class="form-label">Descripción del Incidente <span class="text-danger">*</span></label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required>Discusión entre dos alumnos durante el recreo. Se intervino de inmediato por el inspector de patio.</textarea>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h5 class="form-section-title">Testigos y Reportantes</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="reportante" class="form-label">Reportado por <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="reportante" name="reportante" value="María Fernández" required>
            </div>
            <div class="col-md-6">
                <label for="cargo_reportante" class="form-label">Cargo del Reportante</label>
                <input type="text" class="form-control" id="cargo_reportante" name="cargo_reportante" value="Inspector de Patio">
            </div>
            <div class="col-12">
                <label for="testigos" class="form-label">Testigos</label>
                <textarea class="form-control" id="testigos" name="testigos" rows="2">Carlos Ramírez (Alumno), Pedro Torres (Profesor)</textarea>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h5 class="form-section-title">Seguimiento</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="">Seleccione estado</option>
                    <option selected>En Proceso</option>
                    <option>Resuelto</option>
                    <option>Crítico</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="responsable" class="form-label">Responsable del Seguimiento</label>
                <select class="form-select" id="responsable" name="responsable">
                    <option value="">Seleccione responsable</option>
                    <option selected>Inspector General</option>
                    <option>Orientador</option>
                    <option>Psicólogo</option>
                    <option>Director</option>
                </select>
            </div>
            <div class="col-12">
                <label for="acciones" class="form-label">Acciones Tomadas</label>
                <textarea class="form-control" id="acciones" name="acciones" rows="3">Se separó a los alumnos. Se citó a los apoderados para reunión el 10/11/2025.</textarea>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="notificar_apoderado" name="notificar_apoderado" checked>
                    <label class="form-check-label" for="notificar_apoderado">
                        Notificar al apoderado
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>
            Guardar Cambios
        </button>
        <a href="/modulos/bitacora" class="btn btn-secondary">
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
