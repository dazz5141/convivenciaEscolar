@extends('layouts.app')

@section('title', 'Nuevo Alumno')

@section('content')

<div class="page-header">
    <h1 class="page-title">Nuevo Alumno</h1>
    <p class="text-muted">Registrar un nuevo estudiante</p>
</div>

<form action="{{ route('alumnos.store') }}" method="POST">
    @csrf

    {{-- DATOS PERSONALES --}}
    <div class="form-section">
        <h5 class="form-section-title">Datos Personales</h5>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">RUN *</label>
                <input type="text" name="run" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Nombres *</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Paterno *</label>
                <input type="text" name="apellido_paterno" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Materno *</label>
                <input type="text" name="apellido_materno" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Sexo</label>
                <select class="form-select" name="sexo_id">
                    <option value="">— Seleccione —</option>
                    @foreach(\App\Models\Sexo::all() as $sx)
                        <option value="{{ $sx->id }}">{{ $sx->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Correo</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control">
            </div>

        </div>
    </div>

    {{-- UBICACION --}}
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

    {{-- DATOS ACADÉMICOS --}}
    <div class="form-section">
        <h5 class="form-section-title">Información Académica</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Curso *</label>
                <select name="curso_id" class="form-select" required>
                    <option value="">— Seleccione curso —</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" class="form-control">
            </div>

        </div>
    </div>

    {{-- APODERADO DEL ALUMNO --}}
    <div class="form-section">
        <h5 class="form-section-title">Apoderado del Alumno</h5>

        <div class="alert alert-info">
            Seleccione el apoderado responsable del alumno.  
            Si no existe, deberá crearlo previamente en el módulo de Apoderados.
        </div>

        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label">Apoderado *</label>
                <select name="apoderado_id" class="form-select" required>
                    <option value="">— Seleccione apoderado —</option>

                    @foreach($apoderados as $ap)
                        <option value="{{ $ap->id }}">
                            {{ $ap->nombre }} {{ $ap->apellido_paterno }} {{ $ap->apellido_materno }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Alumno
        </button>

        <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">
            Cancelar
        </a>
    </div>
</form>

@endsection

@section('scripts')
@include('partials.select-territorio-js')
@endsection
