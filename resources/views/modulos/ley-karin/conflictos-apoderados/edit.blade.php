@extends('layouts.app')

@section('title', 'Editar Conflicto con Apoderado')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Conflicto con Apoderado</h1>
</div>

@include('components.alerts')

<form action="{{ route('leykarin.conflictos-apoderados.update', $conflicto) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- =========================================================
         SECCIÓN 1: FUNCIONARIO INVOLUCRADO (NO EDITABLE)
    ========================================================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Funcionario Involucrado</h5>

        <p class="fw-bold mb-1">
            {{ $conflicto->funcionario->nombre_completo ?? '—' }}
        </p>

        <p class="text-muted mb-0">
            {{ $conflicto->funcionario->cargo->nombre ?? '' }}
        </p>
    </div>


    {{-- =========================================================
         SECCIÓN 2: APODERADO INVOLUCRADO (NO EDITABLE)
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Apoderado Involucrado</h5>

        @if($conflicto->apoderado_id && $conflicto->apoderado)
            {{-- Apoderado interno --}}
            <p class="fw-bold mb-1">
                {{ $conflicto->apoderado->nombre_completo }}
            </p>
            <p class="text-muted mb-0">
                {{ $conflicto->apoderado->rut }}
            </p>
        @else
            {{-- Apoderado externo --}}
            <p class="fw-bold mb-1">
                {{ $conflicto->apoderado_nombre ?? '—' }}
            </p>
            <p class="text-muted mb-0">
                {{ $conflicto->apoderado_rut ?? '' }}
            </p>
        @endif
    </div>


    {{-- =========================================================
         SECCIÓN 3: REGISTRADO POR (NO EDITABLE)
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Registrado por</h5>

        <p class="fw-bold">
            {{ $conflicto->registradoPor->nombre_completo ?? '—' }}
            <br>
            <small class="text-muted">{{ $conflicto->registradoPor->rol->nombre ?? '' }}</small>
        </p>
    </div>


    {{-- =========================================================
         SECCIÓN 4: INFORMACIÓN DEL CONFLICTO
    ========================================================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información del Conflicto</h5>

        <div class="row g-3">

            {{-- FECHA (NO EDITABLE) --}}
            <div class="col-md-4">
                <label class="form-label">Fecha</label>
                <input type="date"
                       class="form-control"
                       value="{{ $conflicto->fecha }}"
                       disabled>
            </div>

            {{-- TIPO --}}
            <div class="col-md-8">
                <label class="form-label">Tipo de conflicto *</label>
                <input type="text"
                       name="tipo_conflicto"
                       class="form-control"
                       value="{{ $conflicto->tipo_conflicto }}"
                       placeholder="Ej: Reclamo, discusión, desacuerdo..."
                       required>
            </div>

            {{-- LUGAR --}}
            <div class="col-12">
                <label class="form-label">Lugar del conflicto</label>
                <input type="text"
                       name="lugar_conflicto"
                       class="form-control"
                       value="{{ $conflicto->lugar_conflicto }}"
                       placeholder="Ej: Oficina, pasillo, sala de reuniones...">
            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="col-12">
                <label class="form-label">Descripción del conflicto *</label>
                <textarea name="descripcion"
                          class="form-control"
                          rows="4"
                          required>{{ trim($conflicto->descripcion) }}</textarea>
            </div>

            {{-- ACCIÓN TOMADA --}}
            <div class="col-12">
                <label class="form-label">Acción tomada</label>
                <textarea name="accion_tomada"
                          class="form-control"
                          rows="3">{{ trim($conflicto->accion_tomada) }}</textarea>
            </div>

            {{-- ESTADO --}}
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

            {{-- CONFIDENCIAL --}}
            <div class="col-md-3">
                <label class="form-label">Confidencial</label>
                <select name="confidencial" class="form-select">
                    <option value="0" {{ $conflicto->confidencial == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $conflicto->confidencial == 1 ? 'selected' : '' }}>Sí</option>
                </select>
            </div>

            {{-- DERIVADO LEY KARIN --}}
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
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('leykarin.conflictos-apoderados.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endsection
