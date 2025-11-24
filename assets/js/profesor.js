document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById('profesor_id')) {
        cargarProfesores('profesor_id');
    }

    if (document.getElementById('mod-profesor')) {
        cargarProfesores('mod-profesor');
    }
});

function cargarProfesores(selectorId, actual = '') {
    const select = document.getElementById(selectorId);
    if (!select) return; 

    fetch('../profesor/obtener_profesores.php')
        .then(resp => resp.json())
        .then(profesores => {
            if (Array.isArray(profesores)) {
                select.innerHTML = '<option value="">Seleccione un profesor</option>' +
                    profesores.map(p => {
                        const selected = p.id_usuario == actual ? 'selected' : '';
                        return `<option value="${p.id_usuario}" ${selected}>${p.nombre} ${p.apellido}</option>`;
                    }).join('');
            } else {
                select.innerHTML = '<option value="">No hay profesores disponibles</option>';
            }
        })
        .catch(e => {
            console.error('Error al cargar profesores', e);
            select.innerHTML = '<option value="">Error al cargar profesores</option>';
        });
}
