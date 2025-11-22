document.addEventListener("DOMContentLoaded", () => {
    cargarProfesores();
});

function cargarProfesores() {
    fetch('../profesor/obtener_profesores.php')
        .then(resp => resp.json())
        .then(profesores => {
            const select = document.getElementById('profesor_id');
            const actual = "<?= htmlspecialchars($_POST['profesor_id'] ?? '') ?>"; // mantiene selección

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
            document.getElementById('profesor_id').innerHTML =
                '<option value="">Error al cargar profesores</option>';
        });
}
