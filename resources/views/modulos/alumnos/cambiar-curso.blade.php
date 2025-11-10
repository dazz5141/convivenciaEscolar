@extends('layouts.app')

@section('title', 'Cambiar de Curso')

@section('content')

<div class="page-header">
    <h1 class="page-title">Cambiar de Curso: {{ $alumno->nombre }}</h1>
</div>

<form action="{{ route('alumnos.cambiarCurso', $alumno->id) }}" method="POST">
    @csrf

    <div class="form-section">

        <div class="mb-3">
            <label class="form-label">Curso Actual</label>
            <input type="text" class="form-control" readonly
                   value="{{ $alumno->curso->nivel }} {{ $alumno->curso->letra }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Nuevo Curso</label>
            <select name="curso_id" class="form-select" required>
                <option value="">Seleccione...</option>
                @foreach($cursos as $c)
                    <option value="{{ $c->id }}">{{ $c->nivel }} {{ $c->letra }} - {{ $c->anio }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Motivo (opcional)</label>
            <textarea name="motivo" class="form-control" rows="3"></textarea>
        </div>

    </div>

    <button class="btn btn-primary">
        <i class="bi bi-check-circle me-2"></i>Guardar Cambio
    </button>

    <a href="{{ route('alumnos.show', $alumno->id) }}" class="btn btn-secondary">
        Cancelar
    </a>

</form>

@endsection
