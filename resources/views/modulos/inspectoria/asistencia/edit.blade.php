@extends('layouts.app')

@section('title', 'Editar Registro de Asistencia')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Registro de Asistencia</h1>
</div>

@include('components.alerts')

{{-- =========================================================
     PERMISO: SOLO QUIEN TIENE edit EN ATRASOS
========================================================= --}}
@if(canAccess('atrasos', 'edit'))

<form action="{{ route('inspectoria.asistencia.update', $evento) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- =========================================================
         SECCIÓN 1: ALUMNO INVOLUCRADO (NO EDITABLE)
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno</h5>

        <p class="fw-bold">
            {{ $evento->alumno->nombre_completo }}
            <br>
            <small class="text-muted">
                {{ $evento->alumno->curso->nombre ?? '' }}
            </small>
        </p>
    </div>



    {{-- =========================================================
         SECCIÓN 2: TIPO DE ASISTENCIA
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Tipo de Asistencia</h5>

        <select name="tipo_id" class="form-select" required>
            @foreach ($tipos as $t)
                <option value="{{ $t->id }}"
                    {{ $evento->tipo_id == $t->id ? 'selected' : '' }}>
                    {{ $t->nombre }}
                </option>
            @endforeach
        </select>
    </div>



    {{-- =========================================================
         SECCIÓN 3: INFORMACIÓN DEL EVENTO
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información del Evento</h5>

        <div class="row g-3">

            {{-- FECHA (NO EDITABLE) --}}
            <div class="col-md-4">
                <label class="form-label">Fecha</label>
                <input type="date"
                       class="form-control"
                       value="{{ $evento->fecha }}"
                       disabled>
            </div>

            {{-- HORA (editable) --}}
            <div class="col-md-4">
                <label class="form-label">Hora</label>
                <input type="time"
                       name="hora"
                       class="form-control"
                       value="{{ $evento->hora }}">
            </div>

            {{-- OBSERVACIONES --}}
            <div class="col-12">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones"
                          class="form-control"
                          rows="3">{{ trim($evento->observaciones) }}</textarea>
            </div>

        </div>
    </div>



    {{-- =========================================================
         BOTONES
    ========================================================== --}}
    <div class="d-flex gap-2 mt-4">
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('inspectoria.asistencia.index') }}"
           class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endif {{-- FIN PERMISO --}}

@endsection
