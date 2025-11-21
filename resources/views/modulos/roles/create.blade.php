@extends('layouts.app')

@section('title', 'Crear Rol')

@section('content')

<div class="page-header">
    <h1 class="page-title">Crear Nuevo Rol</h1>
</div>

@include('components.alerts')

<form action="{{ route('roles.store') }}" method="POST">
    @csrf

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">Nombre del Rol *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" required>
                @error('nombre') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap mt-3">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar
        </button>

        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endsection
