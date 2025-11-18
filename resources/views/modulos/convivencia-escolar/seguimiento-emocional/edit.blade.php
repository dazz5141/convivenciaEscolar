@extends('layouts.app')

@section('title', 'Editar Seguimiento Emocional')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h1 class="page-title">Editar Seguimiento #{{ $seguimiento->id }}</h1>
        <p class="text-muted">Modificar la información del seguimiento emocional registrado</p>
    </div>

    <a href="{{ route('convivencia.seguimiento.show', $seguimiento->id) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Volver
    </a>
</div>

<form action="{{ route('convivencia.seguimiento.update', $seguimiento->id) }}" method="POST">
    @csrf
    @method('PUT')


    {{-- ===========================
         SECCIÓN 1: ALUMNO
    ============================ --}}
    <div class="form-section">
        <h5 class="form-section-title">Alumno Evaluado</h5>

        <button type="button" class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal" data-bs-target="#modalBuscarAlumno">
            <i class="bi bi-search"></i> Cambiar Alumno
        </button>

        <input type="hidden" name="alumno_id" id="inputAlumnoSeleccionado"
               value="{{ $seguimiento->alumno_id }}">

        <p class="fw-bold" id="textoAlumnoSeleccionado">
            {{ $seguimiento->alumno->apellido_paterno }}
            {{ $seguimiento->alumno->apellido_materno }},
            {{ $seguimiento->alumno->nombre }}
            ({{ $seguimiento->alumno->curso->nombre ?? 'Sin curso' }})
        </p>

        @error('alumno_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>



    {{-- ===========================
         SECCIÓN 2: INFORMACIÓN BASE
    ============================ --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Información del Seguimiento</h5>

        <div class="row g-3">

            {{-- Fecha --}}
            <div class="col-md-4">
                <label class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date"
                       name="fecha"
                       class="form-control"
                       value="{{ \Carbon\Carbon::parse($seguimiento->fecha)->format('Y-m-d') }}"
                       required>
            </div>

            {{-- Nivel emocional --}}
            <div class="col-md-4">
                <label class="form-label">Nivel emocional</label>
                <select name="nivel_emocional_id" class="form-select">
                    <option value="">Seleccione...</option>
                    @foreach($niveles as $n)
                        <option value="{{ $n->id }}"
                            {{ $seguimiento->nivel_emocional_id == $n->id ? 'selected' : '' }}>
                            {{ $n->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Estado --}}
            <div class="col-md-4">
                <label class="form-label">Estado <span class="text-danger">*</span></label>
                <select name="estado_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($estados as $e)
                        <option value="{{ $e->id }}"
                            {{ $seguimiento->estado_id == $e->id ? 'selected' : '' }}>
                            {{ $e->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>



    {{-- ===========================
         SECCIÓN 3: COMENTARIOS
    ============================ --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Comentario</h5>

        <textarea name="comentario" class="form-control" rows="4"
                  placeholder="Ingrese observaciones...">{{ $seguimiento->comentario }}</textarea>
    </div>



    {{-- ===========================
         SECCIÓN 4: FUNCIONARIO
    ============================ --}}
    <div class="form-section mt-4">
        <h5 class="form-section-title">Funcionario Evaluador</h5>

        <button type="button" class="btn btn-outline-primary mb-3"
                data-bs-toggle="modal" data-bs-target="#modalBuscarFuncionario">
            <i class="bi bi-search"></i> Cambiar Funcionario
        </button>

        <input type="hidden" name="evaluado_por" id="inputFuncionarioSeleccionado"
               value="{{ $seguimiento->evaluado_por }}">

        <p class="fw-bold" id="textoFuncionarioSeleccionado">
            {{ $seguimiento->evaluador->apellido_paterno ?? '' }}
            {{ $seguimiento->evaluador->apellido_materno ?? '' }},
            {{ $seguimiento->evaluador->nombre ?? '' }}
        </p>

        @error('evaluado_por')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>



    {{-- ===========================
         BOTONES
    ============================ --}}
    <div class="d-flex gap-2 flex-wrap mt-4">
        <button class="btn btn-primary">
            <i class="bi bi-save me-2"></i> Guardar Cambios
        </button>

        <a href="{{ route('convivencia.seguimiento.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i> Cancelar
        </a>
    </div>

</form>




{{-- ===================================================================
     MODAL: BUSCAR ALUMNO
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
     MODAL: BUSCAR FUNCIONARIO
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
     JAVASCRIPT DE BÚSQUEDA
=================================================================== --}}
<script>

// Buscar general
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


// Eventos búsqueda
document.getElementById('inputBuscarAlumno').addEventListener('keyup', () => {
    buscar('/api-interna/buscar/alumnos', 'inputBuscarAlumno', 'resultadoAlumnos', 'alumno');
});

document.getElementById('inputBuscarFuncionario').addEventListener('keyup', () => {
    buscar('/api-interna/buscar/funcionarios', 'inputBuscarFuncionario', 'resultadoFuncionarios', 'funcionario');
});


// Selección
document.addEventListener('click', function(e){

    if (e.target.classList.contains('seleccionar')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let extra = e.target.dataset.extra;
        let tipo = e.target.dataset.tipo;

        // Alumno
        if (tipo === 'alumno') {

            document.getElementById('inputAlumnoSeleccionado').value = id;

            document.getElementById('textoAlumnoSeleccionado').textContent =
                `${nombre} (${extra})`;

            bootstrap.Modal.getInstance(
                document.getElementById('modalBuscarAlumno')
            ).hide();
        }

        // Funcionario
        if (tipo === 'funcionario') {

            document.getElementById('inputFuncionarioSeleccionado').value = id;

            document.getElementById('textoFuncionarioSeleccionado').textContent =
                `${nombre} (${extra})`;

            bootstrap.Modal.getInstance(
                document.getElementById('modalBuscarFuncionario')
            ).hide();
        }
    }
});

</script>

@endsection
