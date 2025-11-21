@extends('layouts.app')

@section('title', 'Editar Curso')

@section('content')
<div class="page-header">
    <h1 class="page-title">Editar Curso</h1>
</div>

@include('components.alerts')

<form action="{{ route('cursos.update', $curso->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-section">
        <h5 class="form-section-title">Datos del Curso</h5>
        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Año <span class="text-danger">*</span></label>
                <input type="number" name="anio" min="2000" max="2100"
                       class="form-control"
                       value="{{ $curso->anio }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Nivel <span class="text-danger">*</span></label>
                <input type="text" name="nivel" class="form-control"
                       value="{{ $curso->nivel }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Letra <span class="text-danger">*</span></label>
                <input type="text" name="letra" class="form-control"
                       value="{{ $curso->letra }}" required>
            </div>

        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>
            Guardar Cambios
        </button>

        <a href="{{ route('cursos.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>

        @if($curso->activo)
            <form action="{{ route('cursos.disable', $curso->id) }}" method="POST"
                  class="ms-auto d-inline">
                @csrf @method('PUT')
                <button class="btn btn-danger" onclick="return confirm('¿Deshabilitar curso?')">
                    <i class="bi bi-slash-circle me-2"></i> Deshabilitar
                </button>
            </form>
        @else
            <form action="{{ route('cursos.enable', $curso->id) }}" method="POST"
                  class="ms-auto d-inline">
                @csrf @method('PUT')
                <button class="btn btn-success" onclick="return confirm('¿Habilitar curso?')">
                    <i class="bi bi-check-circle me-2"></i> Habilitar
                </button>
            </form>
        @endif
    </div>
</form>
@endsection
