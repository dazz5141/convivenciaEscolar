<div class="modal fade" id="modalApoderadoLK" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Buscar Apoderado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <input type="text"
               id="buscarApoderadoLK"
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

          <tbody id="resultadoApoderadoLK">
            <tr>
              <td colspan="4" class="text-center">Ingrese un criterio de búsqueda</td>
            </tr>
          </tbody>
        </table>

      </div>

    </div>
  </div>
</div>


<script>
document.getElementById('buscarApoderadoLK').addEventListener('keyup', () => {

    let q = document.getElementById('buscarApoderadoLK').value;

    if (q.length < 2) return;

    fetch('/api-interna/buscar/apoderados?q=' + q)
        .then(res => res.json())
        .then(data => {

            let html = "";

            if (data.length === 0) {
                document.getElementById('resultadoApoderadoLK').innerHTML =
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
                            <button class="btn btn-sm btn-success seleccionar-apoderado-lk"
                                data-id="${ap.id}"
                                data-nombre="${ap.nombre_completo}"
                                data-run="${ap.run}">
                                Seleccionar
                            </button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('resultadoApoderadoLK').innerHTML = html;
        });
});


document.addEventListener('click', function(e) {

    if (e.target.classList.contains('seleccionar-apoderado-lk')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let run = e.target.dataset.run;

        // función definida en create/edit
        seleccionarApoderadoLK(id, nombre, run);

        bootstrap.Modal.getInstance(
            document.getElementById('modalApoderadoLK')
        ).hide();
    }
});
</script>
