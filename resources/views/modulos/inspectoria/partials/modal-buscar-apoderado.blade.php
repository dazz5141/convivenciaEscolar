<div class="modal fade" id="modalBuscarApoderado" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Buscar Apoderado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <input type="text"
               id="inputBuscarApoderado"
               class="form-control mb-3"
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
            <tr>
              <td colspan="4" class="text-center">
                Ingrese un criterio de búsqueda.
              </td>
            </tr>
          </tbody>
        </table>

      </div>

    </div>
  </div>
</div>

<script>
// ============================================================
// BÚSQUEDA AJAX DE APODERADOS (API INTERNA)
// ============================================================
document.getElementById('inputBuscarApoderado').addEventListener('keyup', () => {

    let q = document.getElementById('inputBuscarApoderado').value;

    if (q.length < 2) return;

    fetch('/api-interna/buscar/apoderados?q=' + q)
        .then(res => res.json())
        .then(data => {

            let html = "";

            if (data.length === 0) {
                document.getElementById('resultadoApoderados').innerHTML =
                    `<tr><td colspan="4" class="text-center">Sin resultados.</td></tr>`;
                return;
            }

            data.forEach(ap => {

                html += `
                    <tr>
                        <td>${ap.run}</td>
                        <td>${ap.nombre_completo}</td>
                        <td>${ap.telefono ?? '-'}</td>
                        <td>
                            <button class="btn btn-sm btn-success seleccionar-apoderado"
                                data-id="${ap.id}"
                                data-nombre="${ap.nombre_completo}"
                                data-run="${ap.run}"
                                data-telefono="${ap.telefono ?? ''}">
                                Seleccionar
                            </button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('resultadoApoderados').innerHTML = html;
        });
});


// ============================================================
// SELECCIÓN DEL APODERADO DESDE EL MODAL
// ============================================================
document.addEventListener('click', function(e){

    if (e.target.classList.contains('seleccionar-apoderado')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let run = e.target.dataset.run;
        let telefono = e.target.dataset.telefono;

        // Función que deberás definir en la vista create/edit
        if (typeof agregarApoderadoSeleccionado === 'function') {
            agregarApoderadoSeleccionado(id, nombre, run, telefono);
        }

        // Cierra el modal
        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarApoderado')
        ).hide();
    }
});
</script>
