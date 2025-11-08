@extends('layouts.app')

@section('title', 'Nuevo Incidente')

@section('content')
<div class="page-header">
    <h1 class="page-title">Nuevo Incidente</h1>
    <p class="text-muted">Registrar un nuevo incidente en la bitácora</p>
</div>

<form action="/modulos/bitacora" method="POST">
    <div class="form-section">
        <h5 class="form-section-title">Información del Incidente</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="fecha" class="form-label">Fecha del Incidente <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>
            <div class="col-md-6">
                <label for="hora" class="form-label">Hora del Incidente <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="hora" name="hora" required>
            </div>
            <div class="col-md-6">
                <label for="alumno" class="form-label">Alumno Involucrado <span class="text-danger">*</span></label>
                <select class="form-select" id="alumno" name="alumno" required>
                    <option value="">Seleccione un alumno</option>
                    <option>Juan Pérez Soto - 3° Básico A</option>
                    <option>María González López - 5° Básico B</option>
                    <option>Pedro Soto Ramírez - 7° Básico C</option>
                    <option>Ana Martínez Silva - 2° Medio A</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="tipo" class="form-label">Tipo de Incidente <span class="text-danger">*</span></label>
                <select class="form-select" id="tipo" name="tipo" required>
                    <option value="">Seleccione un tipo</option>
                    <option>Conflicto entre alumnos</option>
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
                    <option>Media</option>
                    <option>Alta</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="lugar" class="form-label">Lugar del Incidente</label>
                <input type="text" class="form-control" id="lugar" name="lugar" placeholder="Ej: Patio, Sala de clases">
            </div>
            <div class="col-12">
                <label for="descripcion" class="form-label">Descripción del Incidente <span class="text-danger">*</span></label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Describa detalladamente lo ocurrido..." required></textarea>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h5 class="form-section-title">Testigos y Reportantes</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="reportante" class="form-label">Reportado por <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="reportante" name="reportante" placeholder="Nombre del reportante" required>
            </div>
            <div class="col-md-6">
                <label for="cargo_reportante" class="form-label">Cargo del Reportante</label>
                <input type="text" class="form-control" id="cargo_reportante" name="cargo_reportante" placeholder="Ej: Profesor, Inspector">
            </div>
            <div class="col-12">
                <label for="testigos" class="form-label">Testigos</label>
                <textarea class="form-control" id="testigos" name="testigos" rows="2" placeholder="Nombres de testigos presentes (separados por coma)"></textarea>
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
                    <option>En Proceso</option>
                    <option>Resuelto</option>
                    <option>Crítico</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="responsable" class="form-label">Responsable del Seguimiento</label>
                <select class="form-select" id="responsable" name="responsable">
                    <option value="">Seleccione responsable</option>
                    <option>Inspector General</option>
                    <option>Orientador</option>
                    <option>Psicólogo</option>
                    <option>Director</option>
                </select>
            </div>
            <div class="col-12">
                <label for="acciones" class="form-label">Acciones Tomadas</label>
                <textarea class="form-control" id="acciones" name="acciones" rows="3" placeholder="Describa las acciones tomadas o medidas aplicadas"></textarea>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="notificar_apoderado" name="notificar_apoderado">
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
            Guardar Incidente
        </button>
        <a href="/modulos/bitacora" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>
            Cancelar
        </a>
    </div>
</form>
@endsection
