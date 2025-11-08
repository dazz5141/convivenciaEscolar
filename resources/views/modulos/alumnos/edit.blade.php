@extends('layouts.app')

@section('title', 'Editar Alumno')

@section('content')
<div class="page-header">
    <h1 class="page-title">Editar Alumno</h1>
    <p class="text-muted">Modificar información del estudiante</p>
</div>

<form action="/modulos/alumnos/1" method="POST">
    <div class="form-section">
        <h5 class="form-section-title">Datos Personales</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="rut" class="form-label">RUT <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="rut" name="rut" value="12.345.678-9" required>
            </div>
            <div class="col-md-6">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="2015-03-15" required>
            </div>
            <div class="col-md-6">
                <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nombres" name="nombres" value="Juan" required>
            </div>
            <div class="col-md-6">
                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="Pérez Soto" required>
            </div>
            <div class="col-md-6">
                <label for="genero" class="form-label">Género <span class="text-danger">*</span></label>
                <select class="form-select" id="genero" name="genero" required>
                    <option value="">Seleccione</option>
                    <option selected>Masculino</option>
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
                    <option selected>3° Básico A</option>
                    <option>4° Básico A</option>
                    <option>5° Básico B</option>
                    <option>6° Básico B</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="2021-03-01" required>
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
                    <option selected>No participa</option>
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
                <input type="text" class="form-control" id="apoderado_rut" name="apoderado_rut" value="11.111.111-1" required>
            </div>
            <div class="col-md-6">
                <label for="apoderado_nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="apoderado_nombre" name="apoderado_nombre" value="Rosa Soto Díaz" required>
            </div>
            <div class="col-md-6">
                <label for="apoderado_telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="apoderado_telefono" name="apoderado_telefono" value="+56 9 8765 4321" required>
            </div>
            <div class="col-md-6">
                <label for="apoderado_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="apoderado_email" name="apoderado_email" value="rosa.soto@ejemplo.cl">
            </div>
            <div class="col-12">
                <label for="apoderado_direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="apoderado_direccion" name="apoderado_direccion" value="Av. Los Heroes 1234, Santiago">
            </div>
        </div>
    </div>

    <div class="form-section">
        <h5 class="form-section-title">Contacto de Emergencia</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="emergencia_nombre" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" id="emergencia_nombre" name="emergencia_nombre" value="Pedro Pérez">
            </div>
            <div class="col-md-6">
                <label for="emergencia_telefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" id="emergencia_telefono" name="emergencia_telefono" value="+56 9 7654 3210">
            </div>
            <div class="col-12">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3">Alérgico a maní y mariscos.</textarea>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>
            Guardar Cambios
        </button>
        <a href="/modulos/alumnos" class="btn btn-secondary">
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
