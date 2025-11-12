<div class="modal fade" id="modalBuscarAlumnoPIE" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Buscar Estudiante PIE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="text"
                       id="inputBuscarEstudiantePie"
                       class="form-control mb-3"
                       placeholder="Buscar por RUN, nombre o apellidos...">

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th>Curso</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="resultadosBusquedaPie">
                        <tr><td colspan="3" class="text-muted">Escriba para buscar...</td></tr>
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('inputBuscarEstudiantePie').addEventListener('keyup', function() {
    let q = this.value;

    if (q.length < 2) {
        document.getElementById('resultadosBusquedaPie').innerHTML =
            `<tr><td colspan="3" class="text-muted">Escriba para buscar...</td></tr>`;
        return;
    }

    fetch(`/api-interna/buscar/estudiantes-pie?q=${q}`)
        .then(res => res.json())
        .then(lista => {

            let html = "";

            if (lista.length === 0) {
                html = `<tr><td colspan="3" class="text-muted">No se encontraron resultados</td></tr>`;
            } else {
                lista.forEach(e => {
                    html += `
                        <tr>
                            <td>${e.nombre_completo}</td>
                            <td>${e.curso}</td>
                            <td>
                                <button class="btn btn-primary btn-sm seleccionar-estudiante-pie"
                                        data-id="${e.id}"
                                        data-nombre="${e.nombre_completo}"
                                        data-curso="${e.curso}">
                                    Seleccionar
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            document.getElementById('resultadosBusquedaPie').innerHTML = html;

        });
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('seleccionar-estudiante-pie')) {

        let id = e.target.dataset.id;
        let nombre = e.target.dataset.nombre;
        let curso = e.target.dataset.curso;

        document.getElementById('estudiante_pie_id').value = id;

        document.getElementById('textoAlumnoSeleccionado').innerHTML =
            `${nombre} (${curso})`;

        bootstrap.Modal.getInstance(
            document.getElementById('modalBuscarAlumnoPIE')
        ).hide();
    }
});
</script>
