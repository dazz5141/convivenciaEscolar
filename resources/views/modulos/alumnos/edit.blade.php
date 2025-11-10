@extends('layouts.app')

@section('title', 'Editar Alumno')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Alumno</h1>
</div>

<form action="{{ route('alumnos.update', $alumno->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- DATOS PERSONALES --}}
    <div class="form-section">
        <h5 class="form-section-title">Datos Personales</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">RUN *</label>
                <input type="text" name="run" class="form-control" value="{{ $alumno->run }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Nombres *</label>
                <input type="text" name="nombre" class="form-control" value="{{ $alumno->nombre }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Paterno *</label>
                <input type="text" name="apellido_paterno" class="form-control" value="{{ $alumno->apellido_paterno }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Apellido Materno *</label>
                <input type="text" name="apellido_materno" class="form-control" value="{{ $alumno->apellido_materno }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control"
                       value="{{ $alumno->fecha_nacimiento }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Sexo</label>
                <select class="form-select" name="sexo_id">
                    <option value="">— Seleccione —</option>
                    @foreach(\App\Models\Sexo::all() as $sx)
                        <option value="{{ $sx->id }}" {{ $sx->id == $alumno->sexo_id ? 'selected' : '' }}>
                            {{ $sx->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Correo</label>
                <input type="email" name="email" class="form-control" value="{{ $alumno->email }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ $alumno->telefono }}">
            </div>

            <div class="col-12">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" value="{{ $alumno->direccion }}">
            </div>

        </div>
    </div>

    {{-- UBICACIÓN --}}
    <div class="form-section">
        <h5 class="form-section-title">Ubicación</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Región</label>
                <select name="region_id" id="region_id" class="form-select">
                    @foreach($regiones as $r)
                        <option value="{{ $r->id }}" {{ $r->id == $alumno->region_id ? 'selected' : '' }}>
                            {{ $r->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Provincia</label>
                <select name="provincia_id" id="provincia_id" class="form-select">
                    @if($alumno->provincia)
                        <option value="{{ $alumno->provincia->id }}" selected>
                            {{ $alumno->provincia->nombre }}
                        </option>
                    @endif
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Comuna</label>
                <select name="comuna_id" id="comuna_id" class="form-select">
                    @if($alumno->comuna)
                        <option value="{{ $alumno->comuna->id }}" selected>
                            {{ $alumno->comuna->nombre }}
                        </option>
                    @endif
                </select>
            </div>

        </div>
    </div>

    {{-- INFORMACIÓN ACADÉMICA --}}
    <div class="form-section">
        <h5 class="form-section-title">Información Académica</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Curso *</label>
                <select name="curso_id" class="form-select" required>
                    <option value="">— Seleccione curso —</option>

                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}"
                            {{ $curso->id == $alumno->curso_id ? 'selected' : '' }}>
                            {{ $curso->nivel }} {{ $curso->letra }} — {{ $curso->anio }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" class="form-control"
                       value="{{ $alumno->fecha_ingreso }}">
            </div>

        </div>
    </div>

    {{-- APODERADO --}}
    <div class="form-section">
        <h5 class="form-section-title">Apoderado del Alumno</h5>

        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label">Apoderado *</label>
                <select name="apoderado_id" class="form-select" required>
                    <option value="">— Seleccione apoderado —</option>

                    @foreach($apoderados as $ap)
                        <option value="{{ $ap->id }}"
                            {{ optional($alumno->apoderados->first())->id == $ap->id ? 'selected' : '' }}>
                            {{ $ap->nombre }} {{ $ap->apellido_paterno }} {{ $ap->apellido_materno }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- BOTONES --}}
    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>

</form>

@endsection

@section('scripts')
@include('partials.select-territorio-js', [
    'provinciaActual' => $alumno->provincia_id,
    'comunaActual'    => $alumno->comuna_id
])
@endsection
