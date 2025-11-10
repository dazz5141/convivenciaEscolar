<script>
document.addEventListener('DOMContentLoaded', () => {

    const regionSelect    = document.getElementById('region_id');
    const provinciaSelect = document.getElementById('provincia_id');
    const comunaSelect    = document.getElementById('comuna_id');

    // Variables recibidas desde las vistas (solo en EDIT)
    const provinciaActual = "{{ $provinciaActual ?? '' }}";
    const comunaActual    = "{{ $comunaActual ?? '' }}";

    /* ===============================
       CARGAR PROVINCIAS
    =============================== */
    function cargarProvincias(regionId, provinciaSel = null) {

        provinciaSelect.innerHTML = '<option value="">Cargando...</option>';
        provinciaSelect.disabled   = true;

        comunaSelect.innerHTML = '<option value="">— Seleccione comuna —</option>';
        comunaSelect.disabled   = true;

        if (!regionId) return;

        fetch(`/api-interna/provincias/${regionId}`)
            .then(res => res.json())
            .then(data => {

                provinciaSelect.innerHTML = '<option value="">— Seleccione provincia —</option>';

                data.forEach(p => {
                    provinciaSelect.innerHTML += `
                        <option value="${p.id}" ${p.id == provinciaSel ? 'selected' : ''}>
                            ${p.nombre}
                        </option>
                    `;
                });

                provinciaSelect.disabled = false;

                // Si estoy en modo EDIT, cargo las comunas de inmediato
                if (provinciaSel) cargarComunas(provinciaSel, comunaActual);
            });
    }

    /* ===============================
       CARGAR COMUNAS
    =============================== */
    function cargarComunas(provinciaId, comunaSel = null) {

        comunaSelect.innerHTML = '<option value="">Cargando...</option>';
        comunaSelect.disabled   = true;

        if (!provinciaId) return;

        fetch(`/api-interna/comunas/${provinciaId}`)
            .then(res => res.json())
            .then(data => {

                comunaSelect.innerHTML = '<option value="">— Seleccione comuna —</option>';

                data.forEach(c => {
                    comunaSelect.innerHTML += `
                        <option value="${c.id}" ${c.id == comunaSel ? 'selected' : ''}>
                            ${c.nombre}
                        </option>
                    `;
                });

                comunaSelect.disabled = false;
            });
    }

    /* ===============================
       EVENTOS
    =============================== */

    regionSelect.addEventListener('change', () => {
        cargarProvincias(regionSelect.value, null);
    });

    provinciaSelect.addEventListener('change', () => {
        cargarComunas(provinciaSelect.value, null);
    });

    /* ===============================
       CARGA INICIAL (solo EDIT)
    =============================== */
    if (regionSelect.value) {
        cargarProvincias(regionSelect.value, provinciaActual);
    }

});
</script>
