@extends('layouts.app')

@section('title', 'Editar Retiro Anticipado')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Retiro Anticipado</h1>
</div>

@include('components.alerts')

<form action="{{ route('inspectoria.retiros.update', ['retiro' => $retiro->id]) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- =========================================================
         SECCIÓN 1: ALUMNO (NO EDITABLE)
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno involucrado</h5>

        <p class="fw-bold">
            {{ $retiro->alumno->nombre_completo }}
            <br>
            <small class="text-muted">{{ $retiro->alumno->curso->nombre ?? '' }}</small>
        </p>
    </div>


    {{-- =========================================================
         SECCIÓN 2: DATOS DE QUIÉN RETIRA (APODERADO o MANUAL)
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Persona que retira</h5>

        @if ($retiro->apoderado)

            {{-- APODERADO REGISTRADO --}}
            <p class="fw-bold">
                {{ $retiro->apoderado->nombre_completo }}  
                <br>
                <small class="text-muted">Apoderado registrado</small>
            </p>

            <input type="hidden" name="apoderado_id" value="{{ $retiro->apoderado->id }}">

        @else

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre Completo *</label>
                    <input type="text"
                        name="nombre_retira"
                        value="{{ old('nombre_retira', $retiro->nombre_retira) }}"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">RUN *</label>
                    <input type="text"
                        name="run_retira"
                        value="{{ old('run_retira', $retiro->run_retira) }}"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Parentesco *</label>
                    <input type="text"
                        name="parentesco_retira"
                        value="{{ old('parentesco_retira', $retiro->parentesco_retira) }}"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input type="text"
                        name="telefono_retira"
                        value="{{ old('telefono_retira', $retiro->telefono_retira) }}"
                        class="form-control">
                </div>

            </div>

        @endif
    </div>


    {{-- =========================================================
         SECCIÓN 3: INFORMACIÓN DEL RETIRO
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información del Retiro</h5>

        <div class="row g-3">

            {{-- Fecha (NO editable) --}}
            <div class="col-md-4">
                <label class="form-label">Fecha</label>
                <input type="date"
                       class="form-control"
                       value="{{ $retiro->fecha }}"
                       disabled>
            </div>

            {{-- Hora (SI editable) --}}
            <div class="col-md-4">
                <label class="form-label">Hora *</label>
                <input type="time"
                       name="hora"
                       class="form-control"
                       value="{{ old('hora', $retiro->hora) }}"
                       required>
            </div>

            {{-- Motivo --}}
            <div class="col-md-12">
                <label class="form-label">Motivo</label>
                <input type="text"
                       name="motivo"
                       value="{{ old('motivo', $retiro->motivo) }}"
                       class="form-control"
                       placeholder="Ej: Control médico, emergencia familiar...">
            </div>

        </div>
    </div>


    {{-- =========================================================
         SECCIÓN 4: OBSERVACIONES
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Observaciones</h5>

        <textarea name="observaciones"
                  rows="3"
                  class="form-control"
                  placeholder="Información interna (opcional)...">{{ old('observaciones', $retiro->observaciones) }}</textarea>
    </div>


    {{-- =========================================================
         BOTONES
    ========================================================== --}}
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('inspectoria.retiros.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endsection
