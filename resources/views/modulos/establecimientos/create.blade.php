@extends('layouts.app')

@section('title', 'Nuevo Establecimiento')

@section('content')

{{-- PERMISO --}}
@if(!canAccess('establecimientos', 'create'))
    @php(abort(403, 'No tienes permisos para crear establecimientos.'))
@endif

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">Nuevo Establecimiento</h1>
        <p class="text-muted">Registrar un nuevo establecimiento educacional</p>
    </div>
</div>

<form action="{{ route('establecimientos.store') }}" method="POST">
    @csrf

    {{-- =========================================================
         INFORMACIÓN GENERAL
    ========================================================== --}}
    <div class="card mb-4">
        <div class="card-body">

            <h5 class="mb-3">Información General</h5>

            <div class="row g-3">

                {{-- RBD --}}
                <div class="col-md-4">
                    <label class="form-label">RBD *</label>
                    <input type="text" name="RBD" class="form-control" required value="{{ old('RBD') }}">
                </div>

                {{-- Nombre --}}
                <div class="col-md-8">
                    <label class="form-label">Nombre del Establecimiento *</label>
                    <input type="text" name="nombre" class="form-control" required value="{{ old('nombre') }}">
                </div>

            </div>

        </div>
    </div>

    {{-- =========================================================
         UBICACIÓN
    ========================================================== --}}
    <div class="card mb-4">
        <div class="card-body">

            <h5 class="mb-3">Ubicación</h5>

            <div class="row g-3">

                {{-- Región --}}
                <div class="col-md-4">
                    <label class="form-label">Región *</label>
                    <select name="region_id" id="region_id" class="form-select" required>
                        <option value="">— Seleccione región —</option>
                        @foreach($regiones as $r)
                            <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Provincia --}}
                <div class="col-md-4">
                    <label class="form-label">Provincia *</label>
                    <select name="provincia_id" id="provincia_id" class="form-select" required disabled>
                        <option value="">— Seleccione provincia —</option>
                    </select>
                </div>

                {{-- Comuna --}}
                <div class="col-md-4">
                    <label class="form-label">Comuna *</label>
                    <select name="comuna_id" id="comuna_id" class="form-select" required disabled>
                        <option value="">— Seleccione comuna —</option>
                    </select>
                </div>

                {{-- Dirección --}}
                <div class="col-md-12">
                    <label class="form-label">Dirección *</label>
                    <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}" required>
                </div>

            </div>

        </div>
    </div>

    {{-- =========================================================
         DEPENDENCIA
    ========================================================== --}}
    <div class="card mb-4">
        <div class="card-body">

            <h5 class="mb-3">Dependencia</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Dependencia *</label>
                    <select name="dependencia_id" class="form-select" required>
                        <option value="">— Seleccione —</option>
                        @foreach($dependencias as $d)
                            <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>

    {{-- BOTONES --}}
    <div class="d-flex gap-3">
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar
        </button>

        <a href="{{ route('establecimientos.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endsection

{{-- JS de selects dinámicos --}}
@section('scripts')
@include('partials.select-territorio-js')
@endsection
