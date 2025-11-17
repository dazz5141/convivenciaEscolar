<div class="modal fade" id="modalConflictos" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Buscar Conflicto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <input type="text"
               id="buscarConflictoLK"
               class="form-control mb-3"
               placeholder="Buscar denunciante, denunciado, lugar o descripción...">

        <table class="table table-hover">
          <thead>
            <tr>
              <th>Tipo</th>
              <th>Denunciante</th>
              <th>Denunciado</th>
              <th></th>
            </tr>
          </thead>

          <tbody id="resultadoConflictoLK">
            <tr>
              <td colspan="5" class="text-center">Ingrese un criterio de búsqueda</td>
            </tr>
          </tbody>
        </table>

      </div>

    </div>
  </div>
</div>

<script>
document.getElementById('buscarConflictoLK').addEventListener('keyup', () => {

    let q = document.getElementById('buscarConflictoLK').value;

    if (q.length < 2) return;

    fetch('/api-interna/buscar/conflictos?q=' + q)
        .then(res => res.json())
        .then(data => {

            let html = "";

            if (data.length === 0) {
                document.getElementById('resultadoConflictoLK').innerHTML =
                    `<tr><td colspan="5" class="text-center">Sin resultados.</td></tr>`;
                return;
            }

            data.forEach(c => {

                let tipo = c.type === "funcionario"
                    ? "Conflicto entre Funcionarios"
                    : "Conflicto Apoderado - Funcionario";

                html += `
                  <tr>
                      <td>${c.tipo}</td>
                      <td>${c.denunciante}</td>
                      <td>${c.denunciado}</td>
                      <td>
                          <button class="btn btn-sm btn-success seleccionar-conflicto-lk"
                              data-type="${c.type}"
                              data-id="${c.id}"
                              data-text="${c.tipo}">
                              Seleccionar
                          </button>
                      </td>
                  </tr>
              `;
            });

            document.getElementById('resultadoConflictoLK').innerHTML = html;
        });
});


document.addEventListener('click', function(e) {

    if (e.target.classList.contains('seleccionar-conflicto-lk')) {

        let type = e.target.dataset.type;
        let id = e.target.dataset.id;
        let texto = e.target.dataset.text;

        // función definida en create
        seleccionarConflictoLK(type, id, texto);

        bootstrap.Modal.getInstance(
            document.getElementById('modalConflictos')
        ).hide();
    }
});
</script>
