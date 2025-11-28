@extends('layouts.app')

@section('title', 'Nuevo Incidente')

@section('content')
<div class="page-header">
    <h1 class="page-title">Nuevo Incidente</h1>
    <p class="text-muted">Registrar un nuevo incidente en la bitácora</p>
</div>

@include('components.alerts')

@crear('bitacora')
<form action="{{ route('convivencia.bitacora.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- =======================
         SECCIÓN 1: INFORMACIÓN BÁSICA
    ======================== --}}
    <div class="form-section">
        <h5 class="form-section-title">Información del Incidente</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date" name="fecha" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tipo de Incidente <span class="text-danger">*</span></label>
                <input type="text" name="tipo_incidente" class="form-control" placeholder="Ej: Conflicto, Atraso, Agresión..." required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Estado Inicial <span class="text-danger">*</span></label>
                <select name="estado_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Descripción <span class="text-danger">*</span></label>
                <textarea name="descripcion" class="form-control" rows="4" placeholder="Describa lo ocurrido..." required></textarea>
            </div>
        </div>
    </div>


    {{-- =======================
         SECCIÓN 2: ALUMNOS INVOLUCRADOS
    ======================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Alumnos Involucrados</h5>

        <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalBuscarAlumno">
            <i class="bi bi-search"></i> Buscar Alumno
        </button>

        <p class="text-muted small mb-2">
            Puede agregar varios alumnos y asignar un rol a cada uno.
        </p>

        <div id="alumnosSeleccionados"></div>
    </div>


    {{-- =======================
         SECCIÓN 3: REPORTANTE
    ======================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Funcionario que Reporta</h5>

        <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalBuscarFuncionario">
            <i class="bi bi-search"></i> Buscar Funcionario
        </button>

        <input type="hidden" name="reportado_por" id="inputFuncionarioSeleccionado">
        <p class="fw-bold" id="textoFuncionarioSeleccionado"></p>
    </div>



    {{-- =======================
         SECCIÓN 4: ARCHIVOS
    ======================== --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Adjuntar Evidencias</h5>

        <input type="file" name="archivos[]" class="form-control" multiple>
        <p class="text-muted small mt-1">Puede subir fotos, PDFs u otros documentos.</p>
    </div>


    {{-- =======================
         BOTONES
    ======================== --}}
    <div class="d-flex gap-2 flex-wrap mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>Guardar Incidente
        </button>

        <a href="{{ route('convivencia.bitacora.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
    </div>

</form>
@endcrear



{{-- ===================================================================
    MODAL BUSCAR ALUMNO
=================================================================== --}}
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


{{-- ===================================================================
    MODAL BUSCAR FUNCIONARIO
=================================================================== --}}
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


{{-- ===================================================================
    JAVASCRIPT DINÁMICO
=================================================================== --}}
<script>

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

// Eventos de búsqueda
document.getElementById('inputBuscarAlumno').addEventListener('keyup', () => {
    buscar('/api-interna/buscar/alumnos', 'inputBuscarAlumno', 'resultadoAlumnos', 'alumno');
});

document.getElementById('inputBuscarFuncionario').addEventListener('keyup', () => {
    buscar('/api-interna/buscar/funcionarios', 'inputBuscarFuncionario', 'resultadoFuncionarios', 'funcionario');
});


// Delegación para seleccionar resultados
document.addEventListener('click', function(e){

    if (e.target.classList.contains('seleccionar')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let extra = e.target.dataset.extra;
        let tipo = e.target.dataset.tipo;

        // ==============================
        //      ALUMNO
        // ==============================
        if (tipo === 'alumno') {

            // Evitar duplicados
            if (document.querySelector(`input[name="alumnos[]"][value="${id}"]`)) {
                alert("Este alumno ya fue agregado.");
                return;
            }

            document.getElementById('alumnosSeleccionados').insertAdjacentHTML('beforeend', `
                <div class="alert alert-primary d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${nombre}</strong> (${extra})
                        <input type="hidden" name="alumnos[]" value="${id}">
                        
                        <select name="rol[]" class="form-select mt-2" required>
                            <option value="victima">Víctima</option>
                            <option value="agresor">Agresor</option>
                            <option value="testigo">Testigo</option>
                        </select>
                    </div>

                    <button class="btn btn-sm btn-danger eliminar">X</button>
                </div>
            `);

            bootstrap.Modal.getInstance(document.getElementById('modalBuscarAlumno')).hide();
        }

        // ==============================
        //      FUNCIONARIO
        // ==============================
        if (tipo === 'funcionario') {

            document.getElementById('inputFuncionarioSeleccionado').value = id;
            document.getElementById('textoFuncionarioSeleccionado').textContent =
                `${nombre} (${extra})`;

            bootstrap.Modal.getInstance(document.getElementById('modalBuscarFuncionario')).hide();
        }
    }

    // Eliminar alumno seleccionado
    if (e.target.classList.contains('eliminar')) {
        e.target.closest('.alert').remove();
    }
});

</script>

@endsection
