<div class="modal fade" id="modalBuscarProfesionalPIE" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Buscar Profesional PIE</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="text" id="inputBuscarProfesionalPIE" class="form-control mb-3"
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
          <tbody id="resultadoProfesionalesPIE">
            <tr><td colspan="4" class="text-center">Ingrese un criterio de búsqueda.</td></tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<script>
document.getElementById('inputBuscarProfesionalPIE').addEventListener('keyup', () => {
    let q = document.getElementById('inputBuscarProfesionalPIE').value;

    if (q.length < 2) return;

    fetch('/api-interna/buscar/profesionales-pie?q=' + q)
        .then(res => res.json())
        .then(data => {

            let html = "";

            if (data.length === 0) {
                document.getElementById('resultadoProfesionalesPIE').innerHTML =
                    `<tr><td colspan="4" class="text-center">Sin resultados.</td></tr>`;
                return;
            }

            data.forEach(p => {
                html += `
                    <tr>
                        <td>${p.run}</td>
                        <td>${p.nombre_completo}</td>
                        <td>${p.cargo}</td>
                        <td>
                            <button class="btn btn-sm btn-success seleccionar-profesional-pie"
                                data-id="${p.id}"
                                data-nombre="${p.nombre_completo}"
                                data-cargo="${p.cargo}">
                                Seleccionar
                            </button>
                        </td>
                    </tr>`;
            });

            document.getElementById('resultadoProfesionalesPIE').innerHTML = html;
        });
});

document.addEventListener('click', function(e){
    if (e.target.classList.contains('seleccionar-profesional-pie')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let cargo = e.target.dataset.cargo;

        document.getElementById('profesional_id').value = id;
        document.getElementById('textoFuncionarioSeleccionado').textContent =
            `${nombre} (${cargo})`;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarProfesionalPIE')
        ).hide();
    }
});
</script>
