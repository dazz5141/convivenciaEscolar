<div class="modal fade" id="modalBuscarApoderado" tabindex="-1" aria-labelledby="modalBuscarApoderadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header">
                <h5 class="modal-title" id="modalBuscarApoderadoLabel">
                    <i class="bi bi-people-fill me-2"></i> Buscar Apoderado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">

                {{-- Campo de búsqueda --}}
                <div class="mb-3">
                    <label class="form-label">Buscar por RUN o Nombre</label>
                    <input type="text" id="inputBuscarApoderado" class="form-control" placeholder="Ej: 11.111.111-1, Juan, Pérez...">
                </div>

                {{-- Tabla de resultados --}}
                <div class="table-responsive">
                    <table class="table table-hover" id="tablaApoderados">
                        <thead>
                            <tr>
                                <th>RUN</th>
                                <th>Nombre completo</th>
                                <th>Teléfono</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Se llena dinámicamente con JS --}}
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Script --}}
<script>
document.getElementById('inputBuscarApoderado').addEventListener('keyup', function () {

    let busqueda = this.value.trim();

    if (busqueda.length < 2) {
        document.querySelector('#tablaApoderados tbody').innerHTML = '';
        return;
    }

    fetch(`/api/buscar/apoderados?q=${busqueda}`)
        .then(response => response.json())
        .then(data => {

            let tbody = document.querySelector('#tablaApoderados tbody');
            tbody.innerHTML = '';

            data.forEach(ap => {

                let row = `
                    <tr>
                        <td>${ap.run}</td>
                        <td>${ap.nombre_completo}</td>
                        <td>${ap.telefono}</td>
                        <td>
                            <button class="btn btn-primary btn-sm"
                                onclick="seleccionarApoderado(${ap.id}, '${ap.nombre_completo}')">
                                <i class="bi bi-check2"></i>
                                Seleccionar
                            </button>
                        </td>
                    </tr>
                `;

                tbody.innerHTML += row;
            });
        });
});

// ESTA FUNCIÓN LA DEFINES EN CADA FORMULARIO QUE USE EL MODAL
// function seleccionarApoderado(id, nombre) {
//     document.getElementById('apoderado_id').value = id;
//     document.getElementById('apoderado_nombre').value = nombre;
//     bootstrap.Modal.getInstance(document.getElementById('modalBuscarApoderado')).hide();
// }
</script>
