@extends('layouts.app')

@section('title', 'Editar Denuncia Ley Karin')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Denuncia</h1>
    <p class="text-muted">Modifique la información permitida de esta denuncia.</p>
</div>

@include('components.alerts')

<form action="{{ route('leykarin.denuncias.update', $denuncia) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-section">

        <h5 class="form-section-title">Información general (solo lectura)</h5>

        <div class="detail-item">
            <div class="detail-label">Denunciante:</div>
            <div class="detail-value">{{ $denuncia->denunciante_nombre }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Denunciado:</div>
            <div class="detail-value">{{ $denuncia->denunciado_nombre }}</div>
        </div>

        <div class="detail-item mb-4">
            <div class="detail-label">Tipo de denuncia:</div>
            <div class="detail-value">{{ $denuncia->tipo?->nombre }}</div>
        </div>

    </div>


    {{-- CAMPO EDITABLE --}}
    <div class="form-section">
        <h5 class="form-section-title">Descripción detallada *</h5>

        <textarea name="descripcion" class="form-control" rows="6" required>
{{ old('descripcion', $denuncia->descripcion) }}
</textarea>
    </div>


    {{-- BOTONES --}}
    <div class="d-flex gap-2 flex-wrap mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('leykarin.denuncias.show', $denuncia) }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endsection
