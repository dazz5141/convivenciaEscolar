@extends('layouts.app')

@section('title', 'Nuevo Alumno')

@section('content')
<div class="page-header">
    <h1 class="page-title">Nuevo Alumno</h1>
    <p class="text-muted">Registrar un nuevo estudiante en el sistema</p>
</div>

<form action="/modulos/alumnos" method="POST">
    <div class="form-section">
        <h5 class="form-section-title">Datos Personales</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="rut" class="form-label">RUT <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="rut" name="rut" placeholder="12.345.678-9" required>
            </div>
            <div class="col-md-6">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>
            <div class="col-md-6">
                <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nombres" name="nombres" required>
            </div>
            <div class="col-md-6">
                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
            </div>
            <div class="col-md-6">
                <label for="genero" class="form-label">Género <span class="text-danger">*</span></label>
                <select class="form-select" id="genero" name="genero" required>
                    <option value="">Seleccione</option>
                    <option>Masculino</option>
                    <option>Femenino</option>
                    <option>Otro</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="nacionalidad" class="form-label">Nacionalidad</label>
                <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" value="Chilena">
            </div>
        </div>
    </div>

    <div class="form-section">
        <h5 class="form-section-title">Información Académica</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="curso" class="form-label">Curso <span class="text-danger">*</span></label>
                <select class="form-select" id="curso" name="curso" required>
                    <option value="">Seleccione un curso</option>
                    <option>1° Básico A</option>
                    <option>2° Básico A</option>
                    <option>3° Básico A</option>
                    <option>4° Básico A</option>
                    <option>5° Básico B</option>
                    <option>6° Básico B</option>
                    <option>7° Básico C</option>
                    <option>8° Básico C</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
            </div>
            <div class="col-md-6">
                <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="">Seleccione</option>
                    <option selected>Activo</option>
                    <option>Suspendido</option>
                    <option>Retirado</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="pie" class="form-label">Programa PIE</label>
                <select class="form-select" id="pie" name="pie">
                    <option value="">No participa</option>
                    <option>NEE Transitoria</option>
                    <option>NEE Permanente</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h5 class="form-section-title">Datos del Apoderado</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="apoderado_rut" class="form-label">RUT Apoderado <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="apoderado_rut" name="apoderado_rut" placeholder="12.345.678-9" required>
            </div>
            <div class="col-md-6">
                <label for="apoderado_nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="apoderado_nombre" name="apoderado_nombre" required>
            </div>
            <div class="col-md-6">
                <label for="apoderado_telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="apoderado_telefono" name="apoderado_telefono" placeholder="+56 9 1234 5678" required>
            </div>
            <div class="col-md-6">
                <label for="apoderado_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="apoderado_email" name="apoderado_email" placeholder="apoderado@ejemplo.cl">
            </div>
            <div class="col-12">
                <label for="apoderado_direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="apoderado_direccion" name="apoderado_direccion">
            </div>
        </div>
    </div>

    <div class="form-section">
        <h5 class="form-section-title">Contacto de Emergencia</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="emergencia_nombre" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" id="emergencia_nombre" name="emergencia_nombre">
            </div>
            <div class="col-md-6">
                <label for="emergencia_telefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" id="emergencia_telefono" name="emergencia_telefono" placeholder="+56 9 1234 5678">
            </div>
            <div class="col-12">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3" placeholder="Alergias, medicamentos, condiciones especiales, etc."></textarea>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>
            Guardar Alumno
        </button>
        <a href="/modulos/alumnos" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>
            Cancelar
        </a>
    </div>
</form>
@endsection
