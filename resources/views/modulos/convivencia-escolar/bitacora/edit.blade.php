@extends('layouts.app')

@section('title', 'Editar Incidente')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h1 class="page-title">Editar Incidente #{{ $incidente->id }}</h1>
        <p class="text-muted">Actualizar información del incidente registrado</p>
    </div>

    <a href="{{ route('convivencia.bitacora.show', $incidente->id) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Volver
    </a>
</div>

@include('components.alerts')

<form action="{{ route('convivencia.bitacora.update', $incidente->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- ================================
     INFORMACIÓN DEL INCIDENTE
    ================================= --}}
    <div class="form-section">
        <h5 class="form-section-title">Información del Incidente</h5>

        <div class="row g-3">

            {{-- Fecha --}}
            <div class="col-md-3">
                <label class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date"
                    name="fecha"
                    class="form-control"
                    value="{{ \Carbon\Carbon::parse($incidente->fecha)->format('Y-m-d') }}"
                    required>
            </div>

            {{-- Tipo de incidente --}}
            <div class="col-md-5">
                <label class="form-label">Tipo de Incidente <span class="text-danger">*</span></label>
                <input type="text"
                    name="tipo_incidente"
                    value="{{ $incidente->tipo_incidente }}"
                    class="form-control"
                    placeholder="Ej: Conflicto, Atraso, Agresión..."
                    required>
            </div>

            {{-- Estado --}}
            <div class="col-md-4">
                <label class="form-label">Estado <span class="text-danger">*</span></label>
                <select name="estado_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach(\App\Models\EstadoIncidente::orderBy('nombre')->get() as $estado)
                        <option value="{{ $estado->id }}"
                            {{ $incidente->estado_id == $estado->id ? 'selected' : '' }}>
                            {{ $estado->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Descripción --}}
            <div class="col-12">
                <label class="form-label">Descripción <span class="text-danger">*</span></label>
                <textarea name="descripcion"
                        class="form-control"
                        rows="3"
                        placeholder="Describa lo ocurrido..."
                        required>{{ $incidente->descripcion }}</textarea>
            </div>

        </div>
    </div>



    {{-- ================================
         INVOLUCRADOS
    ================================= --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Alumnos Involucrados</h5>

        <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalBuscarAlumno">
            <i class="bi bi-search"></i> Agregar Alumno
        </button>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th width="200">Rol</th>
                        <th width="50">Quitar</th>
                    </tr>
                </thead>
                <tbody id="tabla-involucrados">

                    @foreach($incidente->involucrados as $inv)
                        <tr>
                            <td>
                                {{ $inv->alumno->apellido_paterno }}
                                {{ $inv->alumno->apellido_materno }},
                                {{ $inv->alumno->nombre }}
                                <span class="text-muted small">
                                    ({{ $inv->alumno->curso->nombre }})
                                </span>

                                <input type="hidden" name="alumnos[]" value="{{ $inv->alumno_id }}">
                            </td>

                            <td>
                                <select name="rol[]" class="form-select" required>
                                    <option value="victima"  {{ $inv->rol == 'victima' ? 'selected' : '' }}>Víctima</option>
                                    <option value="agresor" {{ $inv->rol == 'agresor' ? 'selected' : '' }}>Agresor</option>
                                    <option value="testigo" {{ $inv->rol == 'testigo' ? 'selected' : '' }}>Testigo</option>
                                </select>
                            </td>

                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger btnQuitar">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>



    {{-- ================================
         FUNCIONARIO QUE REPORTA
    ================================= --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Funcionario que Reporta</h5>

        <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalBuscarFuncionario">
            <i class="bi bi-search"></i> Cambiar Funcionario
        </button>

        <input type="hidden" name="reportado_por" id="inputFuncionarioSeleccionado"
               value="{{ $incidente->reportado_por }}">

        <p class="fw-bold" id="textoFuncionarioSeleccionado">
            {{ $incidente->reportadoPor->apellido_paterno }}
            {{ $incidente->reportadoPor->apellido_materno }},
            {{ $incidente->reportadoPor->nombre }}
        </p>
    </div>



    {{-- ================================
         DOCUMENTOS ADJUNTOS
    ================================= --}}
    <div class="form-section">
        <h5 class="form-section-title">Documentos Adjuntos</h5>

        <div class="list-group mb-3">
            @foreach($incidente->documentos as $doc)
                <a href="{{ Storage::url($doc->ruta_archivo) }}" target="_blank"
                   class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-file-earmark me-2"></i>
                    {{ $doc->nombre_archivo }}
                </a>
            @endforeach
        </div>

        <label class="form-label">Agregar nuevos archivos</label>
        <input type="file" name="archivos[]" class="form-control" multiple>
    </div>



    {{-- BOTONES --}}
    <div class="d-flex gap-2 flex-wrap mt-4">
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('convivencia.bitacora.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>




{{-- =================================================================================
      MODAL: BUSCAR ALUMNO
================================================================================= --}}
<div class="modal fade" id="modalBuscarAlumno" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buscar Alumno</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="text" id="inputBuscarAlumno" class="form-control mb-3"
               placeholder="Buscar por RUN, nombre o apellido...">

        <table class="table table-hover">
          <thead>
            <tr>
              <th>RUN</th>
              <th>Nombre</th>
              <th>Curso</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="resultadoAlumnos">
            <tr><td colspan="4" class="text-center">Ingrese un criterio de búsqueda.</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>



{{-- =================================================================================
      MODAL: BUSCAR FUNCIONARIO
================================================================================= --}}
<div class="modal fade" id="modalBuscarFuncionario" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buscar Funcionario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="text" id="inputBuscarFuncionario" class="form-control mb-3"
               placeholder="Buscar por RUN, nombre o apellido...">

        <table class="table table-hover">
          <thead>
            <tr>
              <th>RUN</th>
              <th>Nombre</th>
              <th>Cargo</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="resultadoFuncionarios">
            <tr><td colspan="4" class="text-center">Ingrese un criterio de búsqueda.</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>




{{-- =================================================================================
      JAVASCRIPT
================================================================================= --}}
<script>

// ======================================================
// ELIMINAR ALUMNO EXISTENTE
// ======================================================
document.addEventListener('click', function (e) {
    if (e.target.closest('.btnQuitar')) {

        // eliminamos la fila
        let fila = e.target.closest('tr');
        fila.remove();

        // limpiamos inputs vacíos del DOM
        document.querySelectorAll('input[name="alumnos[]"]').forEach(i => {
            if (!i.value) i.closest('tr')?.remove();
        });
    }
});


// ======================================================
// FUNCIÓN DE BÚSQUEDA
// ======================================================
function buscar(url, inputId, resultadoId, tipo) {
    let q = document.getElementById(inputId).value;

    if (q.length < 2) return;

    fetch(url + '?q=' + q)
        .then(res => res.json())
        .then(data => {

            let html = "";

            if (data.length === 0) {
                document.getElementById(resultadoId).innerHTML =
                    `<tr><td colspan="4" class="text-center">Sin resultados.</td></tr>`;
                return;
            }

            data.forEach(d => {

                html += `
                    <tr>
                        <td>${d.run}</td>
                        <td>${d.nombre_completo}</td>
                        <td>${d.curso ?? d.cargo ?? ''}</td>
                        <td>
                            <button class="btn btn-sm btn-success seleccionar"
                                data-id="${d.id}"
                                data-nombre="${d.nombre_completo}"
                                data-extra="${d.curso ?? d.cargo ?? ''}"
                                data-tipo="${tipo}">
                                Seleccionar
                            </button>
                        </td>
                    </tr>`;
            });

            document.getElementById(resultadoId).innerHTML = html;
        });
}


// ======================================================
// EVENTOS PARA BUSCAR
// ======================================================
document.getElementById('inputBuscarAlumno').addEventListener('keyup', () => {
    buscar('/api-interna/buscar/alumnos', 'inputBuscarAlumno', 'resultadoAlumnos', 'alumno');
});

document.getElementById('inputBuscarFuncionario').addEventListener('keyup', () => {
    buscar('/api-interna/buscar/funcionarios', 'inputBuscarFuncionario', 'resultadoFuncionarios', 'funcionario');
});


// ======================================================
// SELECCIONAR ALUMNO / FUNCIONARIO
// ======================================================
document.addEventListener('click', function(e){

    if (e.target.classList.contains('seleccionar')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let extra = e.target.dataset.extra;
        let tipo = e.target.dataset.tipo;

        // ---------------------------
        //      ALUMNO
        // ---------------------------
        if (tipo === 'alumno') {

            // **Evitar Duplicados**
            if (document.querySelector(`input[name="alumnos[]"][value="${id}"]`)) {
                alert("Este alumno ya está agregado.");
                return;
            }

            let fila = `
                <tr>
                    <td>
                        ${nombre} (${extra})
                        <input type="hidden" name="alumnos[]" value="${id}">
                    </td>

                    <td>
                        <select name="rol[]" class="form-select" required>
                            <option value="victima">Víctima</option>
                            <option value="agresor">Agresor</option>
                            <option value="testigo">Testigo</option>
                        </select>
                    </td>

                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger btnQuitar">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </td>
                </tr>
            `;

            document.getElementById('tabla-involucrados')
                .insertAdjacentHTML('beforeend', fila);

            bootstrap.Modal.getInstance(document.getElementById('modalBuscarAlumno')).hide();
        }


        // ---------------------------
        //      FUNCIONARIO
        // ---------------------------
        if (tipo === 'funcionario') {

            document.getElementById('inputFuncionarioSeleccionado').value = id;

            document.getElementById('textoFuncionarioSeleccionado').textContent =
                `${nombre} (${extra})`;

            bootstrap.Modal.getInstance(document.getElementById('modalBuscarFuncionario')).hide();
        }
    }
});
</script>

@endsection
