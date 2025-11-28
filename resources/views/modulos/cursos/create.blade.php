@extends('layouts.app')

@section('title', 'Nuevo Curso')

@section('content')
<div class="page-header">
    <h1 class="page-title">Nuevo Curso</h1>
    <p class="text-muted">Crear un curso para el establecimiento</p>
</div>

@include('components.alerts')

{{-- =========================================================
     Formulario (solo si tiene permiso)
========================================================= --}}
@if(canAccess('cursos', 'create'))

<form action="{{ route('cursos.store') }}" method="POST">
    @csrf

    <div class="form-section">
        <h5 class="form-section-title">Datos del Curso</h5>
        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Año <span class="text-danger">*</span></label>
                <input type="number" name="anio" min="2000" max="2100"
                       class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Nivel <span class="text-danger">*</span></label>
                <input type="text" name="nivel" class="form-control"
                       placeholder="Ej: 1° Básico" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Letra <span class="text-danger">*</span></label>
                <input type="text" name="letra" class="form-control"
                       placeholder="A" required>
            </div>

        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>
            Guardar
        </button>

        <a href="{{ route('cursos.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>
</form>

@else
{{-- Si no tiene permiso --}}
<div class="alert alert-danger mt-4">
    No tienes permiso para crear cursos.
</div>
@endif

@endsection
