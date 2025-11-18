@extends('layouts.app')

@section('title', 'Editar Medida Restaurativa')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Medida Restaurativa</h1>
</div>

<form action="{{ route('convivencia.medidas.update', $medida->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- =========================================================
         SECCIÓN 1: ALUMNO (NO EDITABLE)
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno Involucrado</h5>

        @if ($medida->alumno)
            <p class="fw-bold">
                {{ $medida->alumno->nombre_completo }}<br>
                <small class="text-muted">{{ $medida->alumno->curso->nombre ?? '' }}</small>
            </p>
        @else
            <p class="text-muted">No hay alumno asociado.</p>
        @endif
    </div>



    {{-- =========================================================
         SECCIÓN 2: DETALLES DE LA MEDIDA
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Detalles de la Medida</h5>

        <div class="row g-3">

            {{-- Tipo de Medida --}}
            <div class="col-md-6">
                <label class="form-label">Tipo de Medida *</label>
                <select name="tipo_medida_id" class="form-select" required>
                    @foreach ($tipos as $t)
                        <option value="{{ $t->id }}"
                            {{ $medida->tipo_medida_id == $t->id ? 'selected' : '' }}>
                            {{ $t->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Estado de Cumplimiento --}}
            <div class="col-md-6">
                <label class="form-label">Estado de Cumplimiento *</label>
                <select name="cumplimiento_estado_id" class="form-select" required>
                    @foreach ($estadosCumplimiento as $e)
                        <option value="{{ $e->id }}"
                            {{ $medida->cumplimiento_estado_id == $e->id ? 'selected' : '' }}>
                            {{ $e->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>



    {{-- =========================================================
         SECCIÓN 3: RESPONSABLE
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Responsable</h5>

        <select name="responsable_id" class="form-select" required>
            @foreach ($responsables as $r)
                <option value="{{ $r->id }}"
                    {{ $medida->responsable_funcionario == $r->id ? 'selected' : '' }}>
                    {{ $r->nombre }} {{ $r->apellido_paterno }}
                </option>
            @endforeach
        </select>
    </div>



    {{-- =========================================================
         SECCIÓN 4: FECHAS
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Fechas</h5>

        <div class="row g-3">
            {{-- Inicio --}}
            <div class="col-md-6">
                <label class="form-label">Fecha Inicio</label>
                <input type="date"
                       name="fecha_inicio"
                       class="form-control"
                       value="{{ $medida->fecha_inicio }}">
            </div>

            {{-- Fin --}}
            <div class="col-md-6">
                <label class="form-label">Fecha Fin</label>
                <input type="date"
                       name="fecha_fin"
                       class="form-control"
                       value="{{ $medida->fecha_fin }}">
            </div>
        </div>
    </div>



    {{-- =========================================================
         SECCIÓN 5: OBSERVACIONES
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Observaciones</h5>

        <textarea name="observaciones"
                  class="form-control"
                  rows="4"
                  placeholder="Detalles adicionales sobre la medida...">{{ $medida->observaciones }}</textarea>
    </div>



    {{-- =========================================================
         BOTONES
    ========================================================== --}}
    <div class="d-flex gap-2 mt-4">
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('convivencia.medidas.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endsection
