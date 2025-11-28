@extends('layouts.app')

@section('title', 'Editar Apoderado')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Apoderado</h1>
</div>

@include('components.alerts')

{{-- 🔐 Permiso: editar apoderados --}}
@if(!canAccess('apoderados','edit'))
    <div class="alert alert-danger">
        No tienes permisos para editar apoderados.
    </div>
    @php return; @endphp
@endif

<form action="{{ route('apoderados.update', $apoderado->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-section">
        <h5 class="form-section-title">Datos Personales</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">RUN</label>
                <input type="text" name="run" class="form-control" value="{{ $apoderado->run }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Nombres *</label>
                <input type="text" name="nombre" class="form-control"
                       value="{{ $apoderado->nombre }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Paterno *</label>
                <input type="text" name="apellido_paterno" class="form-control"
                       value="{{ $apoderado->apellido_paterno }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Materno *</label>
                <input type="text" name="apellido_materno" class="form-control"
                       value="{{ $apoderado->apellido_materno }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control"
                       value="{{ $apoderado->telefono }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control"
                       value="{{ $apoderado->email }}">
            </div>

            <div class="col-12">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control"
                       value="{{ $apoderado->direccion }}">
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
                        <option value="{{ $r->id }}" {{ $r->id == $apoderado->region_id ? 'selected' : '' }}>
                            {{ $r->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Provincia</label>
                <select name="provincia_id" id="provincia_id" class="form-select">
                    <option value="">— Seleccione provincia —</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Comuna</label>
                <select name="comuna_id" id="comuna_id" class="form-select">
                    <option value="">— Seleccione comuna —</option>
                </select>
            </div>

        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('apoderados.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endsection

@section('scripts')
@include('partials.select-territorio-js', [
    'provinciaActual' => $apoderado->provincia_id,
    'comunaActual'    => $apoderado->comuna_id
])
@endsection
