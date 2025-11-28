@extends('layouts.app')

{{-- PERMISO --}}
@if(!canAccess('funcionarios_edit', 'full'))
    @php(abort(403, 'No tienes permisos para editar funcionarios.'))
@endif

@section('title', 'Editar Funcionario')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Funcionario</h1>
</div>

@include('components.alerts')

<form action="{{ route('funcionarios.update', $funcionario->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-section">
        <h5 class="form-section-title">Datos Personales</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">RUN</label>
                <input type="text" class="form-control" value="{{ $funcionario->run }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control" value="{{ $funcionario->nombre }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Paterno *</label>
                <input type="text" name="apellido_paterno" class="form-control"
                       value="{{ $funcionario->apellido_paterno }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Materno *</label>
                <input type="text" name="apellido_materno" class="form-control"
                       value="{{ $funcionario->apellido_materno }}" required>
            </div>

        </div>
    </div>


    <div class="form-section">
        <h5 class="form-section-title">Información Laboral</h5>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Cargo *</label>
                <select name="cargo_id" class="form-select" required>
                    @foreach($cargos as $c)
                        <option value="{{ $c->id }}"
                            {{ $c->id == $funcionario->cargo_id ? 'selected' : '' }}>
                            {{ $c->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tipo Contrato *</label>
                <select name="tipo_contrato_id" class="form-select" required>
                    @foreach($tiposContrato as $tc)
                        <option value="{{ $tc->id }}"
                            {{ $tc->id == $funcionario->tipo_contrato_id ? 'selected' : '' }}>
                            {{ $tc->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>


    <div class="form-section">
        <h5 class="form-section-title">Ubicación</h5>

        <div class="row g-3">

            {{-- REGIÓN --}}
            <div class="col-md-4">
                <label class="form-label">Región</label>
                <select name="region_id" id="region_id" class="form-select">
                    <option value="">— Seleccione región —</option>

                    @foreach($regiones as $r)
                        <option value="{{ $r->id }}"
                            {{ $r->id == $funcionario->region_id ? 'selected' : '' }}>
                            {{ $r->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- PROVINCIA --}}
            <div class="col-md-4">
                <label class="form-label">Provincia</label>
                <select name="provincia_id" id="provincia_id" class="form-select">
                    <option value="">— Seleccione provincia —</option>
                </select>
            </div>

            {{-- COMUNA --}}
            <div class="col-md-4">
                <label class="form-label">Comuna</label>
                <select name="comuna_id" id="comuna_id" class="form-select">
                    <option value="">— Seleccione comuna —</option>
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" value="{{ $funcionario->direccion }}">
            </div>

        </div>
    </div>


    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('funcionarios.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>

@endsection

@section('scripts')
@include('partials.select-territorio-js', [
    'provinciaActual' => $funcionario->provincia_id,
    'comunaActual' => $funcionario->comuna_id
])
@endsection
