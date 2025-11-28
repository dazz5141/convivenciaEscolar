@extends('layouts.app')

@section('title', 'Nuevo Apoderado')

@section('content')

<div class="page-header">
    <h1 class="page-title">Crear Nuevo Apoderado</h1>
</div>

@include('components.alerts')

{{-- 🔐 Permiso: crear apoderados --}}
@if(!canAccess('apoderados','create'))
    <div class="alert alert-danger">
        No tienes permisos para crear apoderados.
    </div>
    @php return; @endphp
@endif

<form action="{{ route('apoderados.store') }}" method="POST">
    @csrf

    <div class="form-section">
        <h5 class="form-section-title">Datos Personales</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">RUN *</label>
                <input type="text" name="run" class="form-control" required value="{{ old('run') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Nombres *</label>
                <input type="text" name="nombre" class="form-control" required value="{{ old('nombre') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Paterno *</label>
                <input type="text" name="apellido_paterno" class="form-control"
                       required value="{{ old('apellido_paterno') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Materno *</label>
                <input type="text" name="apellido_materno" class="form-control"
                       required value="{{ old('apellido_materno') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>

            <div class="col-12">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
            </div>

        </div>
    </div>

    <div class="form-section">
        <h5 class="form-section-title">Ubicación</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Región</label>
                <select name="region_id" id="region_id" class="form-select">
                    <option value="">— Seleccione región —</option>
                    @foreach($regiones as $r)
                        <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Provincia</label>
                <select name="provincia_id" id="provincia_id" class="form-select" disabled>
                    <option value="">— Seleccione provincia —</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Comuna</label>
                <select name="comuna_id" id="comuna_id" class="form-select" disabled>
                    <option value="">— Seleccione comuna —</option>
                </select>
            </div>

        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar
        </button>

        <a href="{{ route('apoderados.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endsection

@section('scripts')
@include('partials.select-territorio-js')
@endsection
