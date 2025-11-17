<div class="modal fade" id="modalFuncionarioLK" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Buscar Funcionario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="text" id="buscarFuncionarioLK" class="form-control mb-3"
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

          <tbody id="resultadoFuncionarioLK">
            <tr>
              <td colspan="4" class="text-center">Ingrese un criterio de búsqueda.</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>


<script>
document.getElementById('buscarFuncionarioLK').addEventListener('keyup', () => {
    let q = document.getElementById('buscarFuncionarioLK').value;

    if (q.length < 2) return;

    fetch('/api-interna/buscar/funcionarios?q=' + q)
        .then(res => res.json())
        .then(data => {

            let html = "";

            if (data.length === 0) {
                document.getElementById('resultadoFuncionarioLK').innerHTML =
                    `<tr><td colspan="4" class="text-center">Sin resultados.</td></tr>`;
                return;
            }

            data.forEach(f => {
                html += `
                    <tr>
                        <td>${f.run}</td>
                        <td>${f.nombre_completo}</td>
                        <td>${f.cargo}</td>
                        <td>
                            <button class="btn btn-sm btn-success seleccionar-funcionario-lk"
                                data-id="${f.id}"
                                data-nombre="${f.nombre_completo}"
                                data-cargo="${f.cargo}">
                                Seleccionar
                            </button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('resultadoFuncionarioLK').innerHTML = html;
        });
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('seleccionar-funcionario-lk')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let cargo = e.target.dataset.cargo;

        // función definida en create/edit
        seleccionarFuncionarioLK(id, nombre, cargo);

        bootstrap.Modal.getInstance(
            document.getElementById('modalFuncionarioLK')
        ).hide();
    }
});
</script>
