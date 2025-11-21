@extends('layouts.app')

@section('title', 'Editar Novedad de Inspectoría')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Novedad de Inspectoría</h1>
</div>

@include('components.alerts')

<form action="{{ route('inspectoria.novedades.update', $novedad) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- =========================================================
         SECCIÓN 1: ALUMNO INVOLUCRADO (NO EDITABLE)
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno Involucrado</h5>

        @if ($novedad->alumno)
            <p class="fw-bold">
                {{ $novedad->alumno->nombre_completo }}
                <br>
                <small class="text-muted">{{ $novedad->alumno->curso->nombre ?? '' }}</small>
            </p>
        @else
            <p class="text-muted">No hay alumno asociado.</p>
        @endif
    </div>



    {{-- =========================================================
         SECCIÓN 2: TIPO DE NOVEDAD
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Tipo de Novedad</h5>

        <select name="tipo_novedad_id" class="form-select" required>
            @foreach ($tipos as $t)
                <option value="{{ $t->id }}"
                    {{ $novedad->tipo_novedad_id == $t->id ? 'selected' : '' }}>
                    {{ $t->nombre }}
                </option>
            @endforeach
        </select>
    </div>



    {{-- =========================================================
         SECCIÓN 3: INFORMACIÓN DE LA NOVEDAD
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información de la Novedad</h5>

        <div class="row g-3">

            {{-- FECHA (NO EDITABLE) --}}
            <div class="col-md-4">
                <label class="form-label">Fecha y hora</label>
                <input type="datetime-local"
                       class="form-control"
                       value="{{ \Carbon\Carbon::parse($novedad->fecha)->format('Y-m-d\TH:i') }}"
                       disabled>
            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="col-12">
                <label class="form-label">Descripción *</label>
                <textarea name="descripcion"
                          rows="4"
                          class="form-control"
                          required>{{ trim($novedad->descripcion) }}</textarea>
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

        <a href="{{ route('inspectoria.novedades.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endsection
