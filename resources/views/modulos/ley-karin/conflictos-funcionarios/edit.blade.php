@extends('layouts.app')

@section('title', 'Editar Conflicto entre Funcionarios')

@section('content')

{{-- ============================================================
      PERMISO: EDITAR
============================================================== --}}
@if(!canAccess('conflictos_funcionarios', 'edit'))
    <div class="alert alert-danger mb-4">
        <i class="bi bi-shield-lock-fill me-2"></i>
        No tienes permisos para editar conflictos entre funcionarios.
    </div>
@endif


<div class="page-header">
    <h1 class="page-title">Editar Conflicto entre Funcionarios</h1>
</div>

@include('components.alerts')

<form action="{{ route('leykarin.conflictos-funcionarios.update', $conflicto) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- =========================================================
         SECCIÓN 1: FUNCIONARIOS INVOLUCRADOS (NO EDITABLE)
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Funcionarios Involucrados</h5>

        <p class="fw-bold mb-1">
            {{ $conflicto->funcionario1->nombre_completo ?? '—' }}
        </p>

        <p class="fw-bold mb-0">
            <span class="text-muted">vs</span>
            {{ $conflicto->funcionario2->nombre_completo ?? '—' }}
        </p>
    </div>


    {{-- =========================================================
         SECCIÓN 2: REGISTRADO POR (NO EDITABLE)
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Registrado por</h5>

        <p class="fw-bold">
            {{ $conflicto->registradoPor->nombre_completo ?? '—' }}<br>
            <small class="text-muted">
                {{ $conflicto->registradoPor->rol->nombre ?? '' }}
            </small>
        </p>
    </div>


    {{-- =========================================================
         SECCIÓN 3: INFORMACIÓN DEL CONFLICTO
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información del Conflicto</h5>

        <div class="row g-3">

            {{-- Fecha (NO editable) --}}
            <div class="col-md-4">
                <label class="form-label">Fecha</label>
                <input type="date"
                       class="form-control"
                       value="{{ $conflicto->fecha }}"
                       disabled>
            </div>

            {{-- Tipo --}}
            <div class="col-md-8">
                <label class="form-label">Tipo de conflicto *</label>
                <input  type="text"
                        name="tipo_conflicto"
                        class="form-control"
                        placeholder="Ej: Discusión, maltrato, desacuerdo..."
                        value="{{ $conflicto->tipo_conflicto }}"
                        required>
            </div>

            {{-- Lugar --}}
            <div class="col-12">
                <label class="form-label">Lugar del conflicto</label>
                <input type="text"
                       name="lugar_conflicto"
                       class="form-control"
                       value="{{ $conflicto->lugar_conflicto }}"
                       placeholder="Ej: Oficina, sala de profesores, pasillo...">
            </div>

            {{-- Descripción --}}
            <div class="col-12">
                <label class="form-label">Descripción detallada *</label>
                <textarea name="descripcion"
                          class="form-control"
                          rows="4"
                          required>{{ trim($conflicto->descripcion) }}</textarea>
            </div>

            {{-- Acuerdos previos --}}
            <div class="col-12">
                <label class="form-label">Acuerdos previos (si existen)</label>
                <textarea name="acuerdos_previos"
                          class="form-control"
                          rows="3">{{ trim($conflicto->acuerdos_previos) }}</textarea>
            </div>

            {{-- Estado --}}
            <div class="col-md-6">
                <label class="form-label">Estado del caso</label>
                <select name="estado_id" class="form-select">
                    <option value="">Seleccione...</option>
                    @foreach ($estados as $e)
                        <option value="{{ $e->id }}"
                            {{ $conflicto->estado_id == $e->id ? 'selected' : '' }}>
                            {{ $e->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Confidencial --}}
            <div class="col-md-3">
                <label class="form-label">Confidencial</label>
                <select name="confidencial" class="form-select">
                    <option value="0" {{ $conflicto->confidencial == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $conflicto->confidencial == 1 ? 'selected' : '' }}>Sí</option>
                </select>
            </div>

            {{-- Derivado Ley Karin --}}
            <div class="col-md-3">
                <label class="form-label">Derivado Ley Karin</label>
                <select name="derivado_ley_karin" class="form-select">
                    <option value="0" {{ $conflicto->derivado_ley_karin == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $conflicto->derivado_ley_karin == 1 ? 'selected' : '' }}>Sí</option>
                </select>
            </div>

        </div>
    </div>


    {{-- =========================================================
         BOTONES
    ========================================================== --}}
    <div class="d-flex gap-2 mt-4">

        {{-- Sólo si tiene permiso --}}
        @if(canAccess('conflictos_funcionarios','edit'))
            <button class="btn btn-primary">
                <i class="bi bi-save me-2"></i>
                Guardar Cambios
            </button>
        @endif

        <a href="{{ route('leykarin.conflictos-funcionarios.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>
            Cancelar
        </a>

    </div>

</form>

@endsection
