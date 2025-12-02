function mostrarFeedback(titulo, mensaje) {
    document.getElementById("modalFeedbackTitulo").textContent = titulo;
    document.getElementById("modalFeedbackBody").textContent = mensaje;
    new bootstrap.Modal(document.getElementById("modalFeedback")).show();
}

document.addEventListener("DOMContentLoaded", () => {
    //cargar datos del perfil
    fetch("../views/usuarios/obtener_perfil.php")
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById("cardNombre").textContent = data.usuario.nombre;
                document.getElementById("cardApellido").textContent = data.usuario.apellido;
                document.getElementById("cardDni").textContent = data.usuario.dni;
                document.getElementById("cardEmail").textContent = data.usuario.email;
                document.getElementById("cardTelefono").textContent = data.usuario.telefono;
            } else {
                mostrarFeedback("Error", "No se pudieron cargar los datos del usuario");
            }
        })
        .catch(err => {
            console.error(err);
            mostrarFeedback("Error", "Error de conexión al cargar perfil");
        });

    //setear el evento a los iconos de edicion
    document.querySelectorAll(".edit-icon").forEach(icon => {
        icon.addEventListener("click", () => {
            const campo = icon.dataset.campo;
            abrirModalEdicion(campo);
        });
    });

    document.getElementById("formEditarCampo").addEventListener("submit", function(e) {
        e.preventDefault();

        const campo = this.dataset.campo;
        let valido = true;
        let data = { campo: campo };

        //validaciones
        if (campo === "password") {
            let passActual = document.getElementById("password_actual");
            let passNueva = document.getElementById("password_nueva");

            if (passActual.value.trim().length < 1) {
                passActual.classList.add("is-invalid");
                valido = false;
            } else {
                passActual.classList.remove("is-invalid");
                passActual.classList.add("is-valid");
            }

            if (passNueva.value.trim().length < 6) {
                passNueva.classList.add("is-invalid");
                valido = false;
            } else {
                passNueva.classList.remove("is-invalid");
                passNueva.classList.add("is-valid");
            }

            data.password_actual = passActual.value.trim();
            data.password_nueva = passNueva.value.trim();
        } else {
            let input = document.getElementById("nuevoValor");
            let valor = input.value.trim();

            switch (campo) {
                case "nombre":
                case "apellido":
                    if (valor.length < 2) {
                        input.classList.add("is-invalid");
                        valido = false;
                    } else {
                        input.classList.remove("is-invalid");
                        input.classList.add("is-valid");
                    }
                    break;
                case "email":
                    let formatoMail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!formatoMail.test(valor)) {
                        input.classList.add("is-invalid");
                        valido = false;
                    } else {
                        input.classList.remove("is-invalid");
                        input.classList.add("is-valid");
                    }
                    break;
                case "telefono":
                    let formatoTelefono = /^[0-9+\-\s]{6,20}$/;
                    if (valor !== "" && !formatoTelefono.test(valor)) {
                        input.classList.add("is-invalid");
                        valido = false;
                    } else {
                        input.classList.remove("is-invalid");
                        input.classList.add("is-valid");
                    }
                    break;
            }

            data.valor = valor;
        }

        if (!valido) {
            mostrarFeedback("Error", "Complete correctamente el campo antes de guardar.");
            return;
        }

        //modificar el perfil
        fetch("../views/usuarios/modificar_perfil.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(respuesta => {
            if (respuesta.success) {
                mostrarFeedback("Éxito", "Campo actualizado correctamente");

                if (campo !== "password") {
                    const spanId = "card" + campo.charAt(0).toUpperCase() + campo.slice(1);
                    document.getElementById(spanId).textContent = data.valor;
                }

                bootstrap.Modal.getInstance(document.getElementById("modalEditar")).hide();
            } else {
                mostrarFeedback("Error", respuesta.errores.join(", "));
            }
        })
        .catch(err => {
            console.error(err);
            mostrarFeedback("Error", "No se pudo actualizar el campo");
        });
    });
});

//funcion que abre el modal segun el campo que se eligio para modificar
function abrirModalEdicion(campo) {
    const titulo = "Editar " + campo.charAt(0).toUpperCase() + campo.slice(1);
    document.getElementById("modalEditarTitulo").textContent = titulo;

    const body = document.getElementById("modalEditarBody");
    body.innerHTML = "";

    if (campo === "password") {
        body.innerHTML = `
            <div class="mb-3">
                <label for="password_actual" class="form-label">Contraseña actual</label>
                <input type="password" id="password_actual" class="form-control" required>
                <div class="invalid-feedback">Debe ingresar su contraseña actual.</div>
            </div>
            <div class="mb-3">
                <label for="password_nueva" class="form-label">Nueva contraseña</label>
                <input type="password" id="password_nueva" class="form-control" required>
                <div class="invalid-feedback">La nueva contraseña debe tener al menos 6 caracteres.</div>
            </div>
        `;
    } else {
        const spanId = "card" + campo.charAt(0).toUpperCase() + campo.slice(1);
        const valorActual = document.getElementById(spanId).textContent;
        let feedbackMsg = "";

        switch (campo) {
            case "nombre":
            case "apellido":
                feedbackMsg = "Debe tener al menos 2 caracteres.";
                break;
            case "email":
                feedbackMsg = "Ingrese un correo válido.";
                break;
            case "telefono":
                feedbackMsg = "Ingrese un teléfono válido (6-20 dígitos).";
                break;
        }

        body.innerHTML = `
            <div class="mb-3">
                <label for="nuevoValor" class="form-label">${titulo}</label>
                <input type="text" id="nuevoValor" class="form-control" value="${valorActual}" required>
                <div class="invalid-feedback">${feedbackMsg}</div>
            </div>
        `;
    }

    document.getElementById("formEditarCampo").dataset.campo = campo;
    new bootstrap.Modal(document.getElementById("modalEditar")).show();
}
