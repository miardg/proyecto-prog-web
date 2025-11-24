document.addEventListener("DOMContentLoaded", () => {
    enviarUsuario();
    
    if (document.getElementById("usuariosTableBody")) {
        cargarUsuarios();
    }

    const formModificar = document.getElementById("formModificarUsuario");
    if (formModificar) {
        formModificar.addEventListener("submit", procesarModificarUsuario);
    }
});

function enviarUsuario() {
    const form = document.getElementById("crearUsuarioForm");
    const feedback = document.getElementById("usuarioFeedback");

    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.classList.add("was-validated");
            return;
        }

        const datos = new FormData(form);

        try {
            const resp = await fetch("procesar_crear_usuario.php", {
                method: "POST",
                body: datos
            });
            const result = await resp.json();

            if (result.success) {
                if (feedback) {
                    feedback.innerHTML = `<div class="alert alert-success text-center">${result.message}</div>`;
                }
                form.reset();
                form.classList.remove("was-validated");
            } else {
                if (feedback) {
                    feedback.innerHTML = `<div class="alert alert-danger text-center">${result.message}</div>`;
                }
            }
        } catch (err) {
            console.error("Error al conectar con el servidor", err);
            if (feedback) {
                feedback.innerHTML = `<div class="alert alert-danger text-center">Error al conectar con el servidor</div>`;
            }
        }
    });
}

async function cargarUsuarios() {
    const tbody = document.getElementById("usuariosTableBody");
    const feedback = document.getElementById("usuariosFeedback");

    // si no estamos en la vista de usuarios, no hacer nada
    if (!tbody || !feedback) return;

    try {
        const resp = await fetch("obtener_usuarios.php");
        const data = await resp.json();

        if (!data.success) {
            feedback.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            return;
        }

        tbody.innerHTML = "";

        data.usuarios.forEach(u => {
            const fila = document.createElement("tr");

            fila.innerHTML = `
        <td>${u.id_usuario}</td>
        <td>${u.nombre}</td>
        <td>${u.apellido}</td>
        <td>${u.email}</td>
        <td>${u.telefono}</td>
        <td>${u.dni}</td>
        <td>${u.rol ?? ''}</td>
        <td>${u.estado}</td>
      `;

            // Columna de acciones
            const tdAcciones = document.createElement("td");

            if (u.permiso_modificar) {
                const btnModificar = document.createElement("button");
                btnModificar.className = "btn btn-warning btn-sm me-2";
                btnModificar.textContent = "Modificar";
                btnModificar.addEventListener("click", () => modificarUsuario(u));
                tdAcciones.appendChild(btnModificar);
            }

            if (u.permiso_eliminar) {
                const btnInactivar = document.createElement("button");
                btnInactivar.className = "btn btn-danger btn-sm";
                btnInactivar.textContent = "Inactivar";
                btnInactivar.addEventListener("click", () => inactivarUsuario(u.id_usuario));
                tdAcciones.appendChild(btnInactivar);
            }

            fila.appendChild(tdAcciones);
            tbody.appendChild(fila);
        });
    } catch (err) {
        console.error("Error al cargar usuarios", err);
        feedback.innerHTML = `<div class="alert alert-danger">Error al conectar con el servidor</div>`;
    }
}


async function inactivarUsuario(idUsuario) {
    try {
        const datos = new FormData();
        datos.append("id_usuario", idUsuario);

        const resp = await fetch("inactivar_usuarios.php", {
            method: "POST",
            body: datos
        });
        const result = await resp.json();

        if (result.success) {
            cargarUsuarios(); // refresca tabla
        } else {
            alert(result.message);
        }
    } catch (err) {
        console.error("Error al inactivar usuario", err);
    }
}

function modificarUsuario(usuario) {
    document.getElementById("modificarIdUsuario").value = usuario.id_usuario;
    document.getElementById("modificarNombre").value = usuario.nombre;
    document.getElementById("modificarApellido").value = usuario.apellido;
    document.getElementById("modificarEmail").value = usuario.email;
    document.getElementById("modificarTelefono").value = usuario.telefono;
    document.getElementById("modificarDni").value = usuario.dni;

    cargarRolesEnSelect("modificarRol", usuario.rol);

    const modal = new bootstrap.Modal(document.getElementById("modalModificarUsuario"));
    modal.show();
}


async function procesarModificarUsuario(e) {
    e.preventDefault();

    const datos = new FormData(e.target);

    try {
        const resp = await fetch("procesar_modificar_usuario.php", {
            method: "POST",
            body: datos
        });
        const result = await resp.json();

        const feedback = document.getElementById("modificarUsuarioFeedback");
        if (result.success) {
            feedback.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
            cargarUsuarios(); // refrescar tabla
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById("modalModificarUsuario")).hide();
            }, 1000);
        } else {
            feedback.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
        }
    } catch (err) {
        console.error("Error al modificar usuario", err);
    }
}




