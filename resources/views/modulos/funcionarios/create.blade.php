@extends('layouts.app')

@section('title', 'Crear Funcionario')

@section('content')

<div class="page-header">
    <h1 class="page-title">Crear Nuevo Funcionario</h1>
</div>

<form action="{{ route('funcionarios.store') }}" method="POST">
    @csrf

    <div class="form-section">
        <h5 class="form-section-title">Datos Personales</h5>
        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">RUN *</label>
                <input type="text" name="run" class="form-control" value="{{ old('run') }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control" required value="{{ old('nombre') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Paterno *</label>
                <input type="text" name="apellido_paterno" class="form-control" required value="{{ old('apellido_paterno') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Materno *</label>
                <input type="text" name="apellido_materno" class="form-control" required value="{{ old('apellido_materno') }}">
            </div>

        </div>
    </div>


    <div class="form-section">
        <h5 class="form-section-title">Información Laboral</h5>
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Cargo *</label>
                <select name="cargo_id" class="form-select" required>
                    <option value="">— Seleccione —</option>
                    @foreach($cargos as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tipo Contrato *</label>
                <select name="tipo_contrato_id" class="form-select" required>
                    <option value="">— Seleccione —</option>
                    @foreach($tiposContrato as $tc)
                        <option value="{{ $tc->id }}">{{ $tc->nombre }}</option>
                    @endforeach
                </select>
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

            <div class="col-md-12">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
            </div>

        </div>
    </div>



    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar
        </button>

        <a href="{{ route('funcionarios.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    let regionSelect = document.getElementById('region_id');
    let provinciaSelect = document.getElementById('provincia_id');
    let comunaSelect = document.getElementById('comuna_id');

    // Cuando cambia la región
    regionSelect.addEventListener('change', function() {

        let regionId = this.value;
        provinciaSelect.innerHTML = '<option value="">Cargando...</option>';
        provinciaSelect.disabled = true;
        comunaSelect.innerHTML = '<option value="">— Seleccione comuna —</option>';
        comunaSelect.disabled = true;

        if (!regionId) return;

        fetch(`/api-interna/provincias/${regionId}`)
            .then(response => response.json())
            .then(data => {
                provinciaSelect.innerHTML = '<option value="">— Seleccione provincia —</option>';
                data.forEach(item => {
                    provinciaSelect.innerHTML += `<option value="${item.id}">${item.nombre}</option>`;
                });
                provinciaSelect.disabled = false;
            });
    });

    // Cuando cambia la provincia
    provinciaSelect.addEventListener('change', function() {

        let provinciaId = this.value;
        comunaSelect.innerHTML = '<option value="">Cargando...</option>';
        comunaSelect.disabled = true;

        if (!provinciaId) return;

        fetch(`/api-interna/comunas/${provinciaId}`)
            .then(response => response.json())
            .then(data => {
                comunaSelect.innerHTML = '<option value="">— Seleccione comuna —</option>';
                data.forEach(item => {
                    comunaSelect.innerHTML += `<option value="${item.id}">${item.nombre}</option>`;
                });
                comunaSelect.disabled = false;
            });
    });

});
</script>
@endsection

@endsection
