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

<script>
// ============================================================
// BÚSQUEDA AJAX DE ALUMNOS (API INTERNA)
// ============================================================
document.getElementById('inputBuscarAlumno').addEventListener('keyup', () => {
    let q = document.getElementById('inputBuscarAlumno').value;

    // mínimo 2 caracteres
    if (q.length < 2) return;

    fetch('/api-interna/buscar/alumnos?q=' + q)
        .then(res => res.json())
        .then(data => {

            let html = "";

            if (data.length === 0) {
                document.getElementById('resultadoAlumnos').innerHTML =
                    `<tr><td colspan="4" class="text-center">Sin resultados.</td></tr>`;
                return;
            }

            data.forEach(a => {

                html += `
                    <tr>
                        <td>${a.run}</td>
                        <td>${a.nombre_completo}</td>
                        <td>${a.curso}</td>
                        <td>
                            <button class="btn btn-sm btn-success seleccionar-alumno"
                                data-id="${a.id}"
                                data-nombre="${a.nombre_completo}"
                                data-curso="${a.curso}">
                                Seleccionar
                            </button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('resultadoAlumnos').innerHTML = html;
        });
});


// ============================================================
// SELECCIÓN DEL ALUMNO DESDE EL MODAL
// ============================================================
document.addEventListener('click', function(e){

    if (e.target.classList.contains('seleccionar-alumno')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let curso = e.target.dataset.curso;

        // Será implementado en cada vista:
        // - Insert de alumno en create PIE
        // - Insert en tablas especiales si es necesario
        // Este JS solo envía los datos.
        
        if (typeof agregarAlumnoSeleccionado === 'function') {
            agregarAlumnoSeleccionado(id, nombre, curso);
        }

        // Cerrar el modal
        bootstrap.Modal.getInstance(document.getElementById('modalBuscarAlumno')).hide();
    }
});
</script>
