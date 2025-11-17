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
                       value="{{ $alumno->fecha_nacimiento ? $alumno->fecha_nacimiento->format('Y-m-d') : '' }}">
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
                       value="{{ $alumno->fecha_ingreso ? $alumno->fecha_ingreso->format('Y-m-d') : '' }}">
            </div>

        </div>
    </div>

    {{-- APODERADO --}}
    <div class="form-section">
        <h5 class="form-section-title">Apoderado del Alumno</h5>

        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label">Apoderado *</label>

                <input type="hidden"
                    name="apoderado_id"
                    id="apoderado_id"
                    value="{{ optional($alumno->apoderados->first())->id }}"
                    required>

                 <button type="button"
                        class="btn btn-outline-primary mb-2"
                        data-bs-toggle="modal"
                        data-bs-target="#modalBuscarApoderado">
                    <i class="bi bi-search"></i> Buscar Apoderado
                </button>

                <p id="textoApoderadoSeleccionado" class="fw-bold text-primary">
                    {{ $alumno->apoderados->first()
                        ? $alumno->apoderados->first()->apellido_paterno
                        . ' '
                        . $alumno->apoderados->first()->apellido_materno
                        . ', '
                        . $alumno->apoderados->first()->nombre
                        : 'Ningún apoderado seleccionado' }}
                </p>
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
<!-- MODAL BUSCAR APODERADO -->
<div class="modal fade" id="modalBuscarApoderado" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Buscar Apoderado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <input type="text" id="inputBuscarApoderado" class="form-control mb-3"
               placeholder="Buscar por RUN, nombre o apellido...">

        <table class="table table-hover">
          <thead>
            <tr>
              <th>RUN</th>
              <th>Nombre</th>
              <th>Teléfono</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="resultadoApoderados">
            <tr><td colspan="4" class="text-center">Ingrese un criterio de búsqueda.</td></tr>
          </tbody>
        </table>

      </div>

    </div>
  </div>
</div>

@endsection

@section('scripts')
@include('partials.select-territorio-js', [
    'provinciaActual' => $alumno->provincia_id,
    'comunaActual'    => $alumno->comuna_id
])
<script>
function buscarApoderados() {
    let q = document.getElementById('inputBuscarApoderado').value;

    if (q.length < 2) return;

    fetch('/api-interna/buscar/apoderados?q=' + q)
        .then(res => res.json())
        .then(data => {
            let html = "";

            if (data.length === 0) {
                html = `<tr><td colspan="4" class="text-center">Sin resultados.</td></tr>`;
            } else {
                data.forEach(ap => {
                    html += `
                    <tr>
                        <td>${ap.run}</td>
                        <td>${ap.nombre_completo}</td>
                        <td>${ap.telefono}</td>
                        <td>
                            <button class="btn btn-sm btn-success seleccionar-apoderado"
                                data-id="${ap.id}"
                                data-nombre="${ap.nombre_completo}">
                                Seleccionar
                            </button>
                        </td>
                    </tr>`;
                });
            }

            document.getElementById('resultadoApoderados').innerHTML = html;
        });
}

document.getElementById('inputBuscarApoderado').addEventListener('keyup', buscarApoderados);

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('seleccionar-apoderado')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;

        document.getElementById('apoderado_id').value = id;
        document.getElementById('textoApoderadoSeleccionado').textContent = nombre;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarApoderado')
        ).hide();
    }
});
</script>

@endsection
