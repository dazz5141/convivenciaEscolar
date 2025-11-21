@extends('layouts.app')

@section('title', 'Editar Accidente')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Accidente Escolar</h1>
</div>

@include('components.alerts')

<form action="{{ route('inspectoria.accidentes.update', $accidente) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-section">
        <h5 class="form-section-title">Información del Accidente</h5>

        <div class="row g-3">

            {{-- FECHA (NO EDITABLE) --}}
            <div class="col-md-3">
                <label class="form-label">Fecha</label>
                <input type="date" class="form-control" 
                       value="{{ $accidente->fecha->format('Y-m-d') }}" disabled>
            </div>

            {{-- TIPO --}}
            <div class="col-md-3">
                <label class="form-label">Tipo de Accidente *</label>
                <select name="tipo_accidente_id" class="form-select" required>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" 
                            {{ $accidente->tipo_accidente_id == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- LUGAR --}}
            <div class="col-md-6">
                <label class="form-label">Lugar *</label>
                <input type="text" name="lugar" class="form-control" 
                       value="{{ $accidente->lugar }}" required>
            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="col-md-12">
                <label class="form-label">Descripción *</label>
                <textarea name="descripcion" rows="4" class="form-control" required>{{ trim($accidente->descripcion) }}</textarea>
            </div>

            {{-- ATENCIÓN INMEDIATA --}}
            <div class="col-md-12">
                <label class="form-label">Atención inmediata</label>
                <textarea name="atencion_inmediata" rows="3" class="form-control">{{ trim($accidente->atencion_inmediata) }}</textarea>
            </div>

            {{-- DERIVACIÓN --}}
            <div class="col-md-12">
                <label class="form-label">Derivación a centro de salud</label>
                <textarea name="derivacion_salud" rows="3" class="form-control">{{ trim($accidente->derivacion_salud) }}</textarea>
            </div>

        </div>
    </div>

    <div class="d-flex gap-2 mt-3">
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('inspectoria.accidentes.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endsection
