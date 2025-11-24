document.addEventListener("DOMContentLoaded", () => {
    cargarRolesEnSelect("crearUsuarioRol");
});


async function cargarRolesEnSelect(selectId, selectedRol = "") {
    const rolSelect = document.getElementById(selectId);
    if (!rolSelect) return;

    rolSelect.innerHTML = "";

    try {
        const resp = await fetch("../roles/obtener_roles.php");
        const data = await resp.json();

        if (data.success && Array.isArray(data.roles)) {
            data.roles.forEach(r => {
                const opt = document.createElement("option");
                opt.value = r.nombre;
                opt.textContent = r.nombre;
                if (r.nombre === selectedRol) {
                    opt.selected = true;
                }
                rolSelect.appendChild(opt);
            });
        }
    } catch (err) {
        console.error("Error al cargar roles", err);
    }
}

