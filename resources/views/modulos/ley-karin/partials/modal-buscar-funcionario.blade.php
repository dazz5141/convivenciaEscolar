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

<script>
document.getElementById('inputBuscarFuncionario').addEventListener('keyup', () => {
    let q = document.getElementById('inputBuscarFuncionario').value;

    if (q.length < 2) return;

    fetch('/api-interna/buscar/funcionarios?q=' + q)
        .then(res => res.json())
        .then(data => {

            let html = "";

            if (data.length === 0) {
                document.getElementById('resultadoFuncionarios').innerHTML =
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
                            <button class="btn btn-sm btn-success seleccionar-funcionario"
                                data-id="${f.id}"
                                data-nombre="${f.nombre_completo}"
                                data-cargo="${f.cargo}">
                                Seleccionar
                            </button>
                        </td>
                    </tr>`;
            });

            document.getElementById('resultadoFuncionarios').innerHTML = html;
        });
});

document.addEventListener('click', function(e){
    if (e.target.classList.contains('seleccionar-funcionario')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let cargo = e.target.dataset.cargo;

        document.getElementById('inputFuncionarioSeleccionado').value = id;
        document.getElementById('textoFuncionarioSeleccionado').textContent =
            `${nombre} (${cargo})`;

        bootstrap.Modal.getInstance(document.getElementById('modalBuscarFuncionario')).hide();
    }
});
</script>
