window.onload = function () {
    let formFranja = document.getElementById("formFranja");
    let tablaDisponibilidad = document.querySelector("#tablaDisponibilidad tbody");

    // Modal de feedback
    let modalTitle = document.getElementById("modal-title");
    let modalBody = document.getElementById("modal-body");
    let modalFeedback = new bootstrap.Modal(document.getElementById("modalFeedback"));

    // Función para mostrar modal
    function mostrarModal(titulo, mensaje) {
        modalTitle.textContent = titulo;
        modalBody.textContent = mensaje;
        modalFeedback.show();
    }
    // Botón de logout
    let btnLogout = document.getElementById("btnLogout");
    btnLogout.addEventListener("click", function () {
        window.location.href = ""; //aca se tiene que manejar el cierre de sesion
    });

    // Función para agregar fila a la tabla
    function agregarFila(dia, horaInicio, horaFin, cupos) {
        let fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${dia}</td>
            <td>${horaInicio}</td>
            <td>${horaFin}</td>
            <td>${cupos}</td>
            <td>
                <button class="btn btn-sm btn-danger btn-eliminar">Eliminar</button>
            </td>
        `;
        // Botón eliminar
        fila.querySelector(".btn-eliminar").addEventListener("click", () => fila.remove());
        tablaDisponibilidad.appendChild(fila);
    }

    // Manejo del submit del formulario con validaciones
    formFranja.addEventListener("submit", (e) => {
        e.preventDefault();

        // Obtener valores
        let dia = document.getElementById("dia");
        let horaInicio = document.getElementById("horaInicio");
        let horaFin = document.getElementById("horaFin");
        let cupos = document.getElementById("cupos");

        let valido = true;

        // Validar día
        if (!dia.value) {
            dia.classList.add("is-invalid");
            valido = false;
        } else {
            dia.classList.remove("is-invalid");
            dia.classList.add("is-valid");
        }

        // Validar hora inicio
        if (!horaInicio.value) {
            horaInicio.classList.add("is-invalid");
            valido = false;
        } else {
            horaInicio.classList.remove("is-invalid");
            horaInicio.classList.add("is-valid");
        }

        // Validar hora fin
        if (!horaFin.value) {
            horaFin.classList.add("is-invalid");
            valido = false;
        } else {
            horaFin.classList.remove("is-invalid");
            horaFin.classList.add("is-valid");
        }

        // Validar que hora fin sea mayor a hora inicio
        if (horaInicio.value && horaFin.value && horaFin.value <= horaInicio.value) {
            horaInicio.classList.add("is-invalid");
            horaFin.classList.add("is-invalid");
            mostrarModal("Error", "La hora de fin debe ser mayor a la hora de inicio.");
            return;
        }

        // Validar cupos
        if (!cupos.value || parseInt(cupos.value) < 1) {
            cupos.classList.add("is-invalid");
            valido = false;
        } else {
            cupos.classList.remove("is-invalid");
            cupos.classList.add("is-valid");
        }

        if (!valido) {
            mostrarModal("Error", "Por favor completa todos los campos correctamente.");
            return;
        }

        // Si todo es válido, agregar fila
        agregarFila(dia.value, horaInicio.value, horaFin.value, cupos.value);

        // Resetear formulario y clases de validación
        formFranja.reset();
        [dia, horaInicio, horaFin, cupos].forEach(el => el.classList.remove("is-valid"));
        mostrarModal("Éxito", "Franja agregada correctamente.");
    });
}
