@extends('layouts.app')

@section('title', 'Editar Establecimiento')

@section('content')

{{-- PERMISO --}}
@if(!canAccess('establecimientos', 'edit'))
    @php(abort(403, 'No tienes permisos para editar establecimientos.'))
@endif

<div class="page-header">
    <h1 class="page-title">Editar Establecimiento</h1>
</div>

@include('components.alerts')

<form action="{{ route('establecimientos.update', $establecimiento->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-section">
        <h5 class="form-section-title">Información General</h5>

        <div class="row g-3">

            {{-- RBD --}}
            <div class="col-md-4">
                <label class="form-label">RBD <span class="text-danger">*</span></label>
                <input type="text"
                       name="RBD"
                       class="form-control"
                       value="{{ $establecimiento->RBD }}"
                       required>
            </div>

            {{-- Nombre --}}
            <div class="col-md-8">
                <label class="form-label">Nombre del Establecimiento <span class="text-danger">*</span></label>
                <input type="text"
                       name="nombre"
                       class="form-control"
                       value="{{ $establecimiento->nombre }}"
                       required>
            </div>

            {{-- Dirección --}}
            <div class="col-12">
                <label class="form-label">Dirección <span class="text-danger">*</span></label>
                <input type="text"
                       name="direccion"
                       class="form-control"
                       value="{{ $establecimiento->direccion }}"
                       required>
            </div>

            {{-- Título ubicación --}}
            <h5 class="form-section-title mt-4">Ubicación y Dependencia</h5>

            {{-- Dependencia --}}
            <div class="col-md-3">
                <label class="form-label">Dependencia <span class="text-danger">*</span></label>
                <select name="dependencia_id" class="form-select" required>
                    <option value="">Seleccione...</option>

                    @foreach($dependencias as $d)
                        <option value="{{ $d->id }}"
                            {{ $d->id == $establecimiento->dependencia_id ? 'selected' : '' }}>
                            {{ $d->nombre }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- Región --}}
            <div class="col-md-3">
                <label class="form-label">Región <span class="text-danger">*</span></label>
                <select name="region_id" id="region_id" class="form-select" required>
                    <option value="">Seleccione...</option>

                    @foreach($regiones as $r)
                        <option value="{{ $r->id }}"
                            {{ $r->id == $establecimiento->region_id ? 'selected' : '' }}>
                            {{ $r->nombre }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- Provincia --}}
            <div class="col-md-3">
                <label class="form-label">Provincia <span class="text-danger">*</span></label>
                <select name="provincia_id" id="provincia_id" class="form-select" required>
                    <option value="">Seleccione...</option>

                    @foreach($provincias as $p)
                        <option value="{{ $p->id }}"
                            {{ $p->id == $establecimiento->provincia_id ? 'selected' : '' }}>
                            {{ $p->nombre }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- Comuna --}}
            <div class="col-md-3">
                <label class="form-label">Comuna <span class="text-danger">*</span></label>
                <select name="comuna_id" id="comuna_id" class="form-select" required>
                    <option value="">Seleccione...</option>

                    @foreach($comunas as $c)
                        <option value="{{ $c->id }}"
                            {{ $c->id == $establecimiento->comuna_id ? 'selected' : '' }}>
                            {{ $c->nombre }}
                        </option>
                    @endforeach

                </select>
            </div>

        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('establecimientos.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endsection

@section('scripts')
@include('partials.select-territorio-js', [
    'provinciaActual' => $establecimiento->provincia_id,
    'comunaActual' => $establecimiento->comuna_id
])
@endsection
