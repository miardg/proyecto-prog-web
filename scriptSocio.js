window.onload = function () {
    let clases = obtenerClases();
    let planSocio = obtenerPlanSocio();

    inicializarRedirecciones();
    inicializarClases(clases, planSocio);
    inicializarModalPlan();
};

function obtenerClases() {
    return [
        { id: 1, nombre: "Funcional", dia: "Lunes", horario: "18:00", tipo: "Grupal" },
        { id: 2, nombre: "Crossfit", dia: "Martes", horario: "19:00", tipo: "Particular" },
        { id: 3, nombre: "Yoga", dia: "Miércoles", horario: "17:00", tipo: "Grupal" },
        { id: 4, nombre: "HIIT", dia: "Jueves", horario: "20:00", tipo: "Particular" },
        { id: 5, nombre: "Powerlifting", dia: "Viernes", horario: "21:00", tipo: "Particular" },
        { id: 6, nombre: "Spinning", dia: "Sabado", horario: "10:00", tipo: "Grupal" }
    ];
}

function obtenerPlanSocio() {
    return {
        nombre: "Premium",
        estado: "Activo",
        clasesParticularesRestantes: Infinity
    };
}

function inicializarClases(clases, planSocio) {
    let contenedor = document.getElementById("clasesDisponibles");
    if (!contenedor) return;

    clases.forEach(clase => {
        let tarjeta = document.createElement("div");
        tarjeta.className = "col-md-4";
        tarjeta.innerHTML = `
      <div class="card h-100 text-center">
        <div class="card-body">
          <h5 class="card-title">${clase.nombre}</h5>
          <p class="card-text">${clase.dia} - ${clase.horario}</p>
          <p class="card-text">Tipo: ${clase.tipo}</p>
          <button class="btn btn-outline-primary" data-id="${clase.id}">Anotarse</button>
        </div>
      </div>
    `;
        contenedor.appendChild(tarjeta);
    });

    contenedor.addEventListener("click", (e) => {
        if (e.target.tagName === "BUTTON") {
            let id = parseInt(e.target.getAttribute("data-id"));
            let claseSeleccionada = clases.find(c => c.id === id);
            mostrarModalClase(claseSeleccionada, planSocio);
        }
    });
}

function mostrarModalClase(clase, planSocio) {
    let modalHeader = document.getElementById("modalClaseLabel");
    let modalBody = document.getElementById("modalClaseBody");
    let botonModal = document.getElementById("confirmarClase");
    let modal = new bootstrap.Modal(document.getElementById("modalClase"));

    if (!clase || !modalHeader || !modalBody || !botonModal) return;

    // Limpiar clases previas
    botonModal.classList.remove("btn-success", "btn-danger", "is-valid", "is-invalid");
    document.getElementById("mensajeConfirmacion")?.remove();

    modalHeader.innerHTML = "Confirmar inscripción";

    if (planSocio.estado !== "Activo") {
        modalBody.innerHTML = `<p class="text-danger">Tu plan no está activo. No podés anotarte a clases.</p>`;
        botonModal.style.display = "none";
    } else if (clase.tipo === "Particular" && planSocio.clasesParticularesRestantes <= 0) {
        modalBody.innerHTML = `<p class="text-danger">No tenés clases particulares disponibles para esta actividad.</p>`;
        botonModal.style.display = "none";
    } else {
        modalBody.innerHTML = `
      <p>¿Confirmás tu inscripción a <strong>${clase.nombre}</strong> el <strong>${clase.dia}</strong> a las <strong>${clase.horario}</strong>?</p>
      <div id="mensajeConfirmacion" class="mt-3 text-success fw-bold" style="display: none;"></div>
    `;
        botonModal.textContent = "Confirmar";
        botonModal.classList.add("btn-primary");
        botonModal.style.display = "inline-block";

        botonModal.onclick = () => {
            if (clase.tipo === "Particular" && planSocio.clasesParticularesRestantes !== Infinity) {
                planSocio.clasesParticularesRestantes--;
            }

            // Mostrar mensaje de éxito
            let mensaje = document.getElementById("mensajeConfirmacion");
            mensaje.textContent = `¡Te anotaste a ${clase.nombre} el ${clase.dia} a las ${clase.horario}!`;
            mensaje.style.display = "block";

            // Feedback visual
            botonModal.classList.remove("btn-primary");
            botonModal.classList.add("btn-success", "is-valid");

            // Opcional: cerrar modal después de unos segundos
            setTimeout(() => {
                modal.hide();
            }, 2000);
        };
    }

    modal.show();
}

function inicializarRedirecciones() {
    let asignarRedireccion = (id, url) => {
        let btn = document.getElementById(id);
        if (btn) {
            btn.addEventListener("click", () => {
                window.location.href = url;
            });
        }
    };

    asignarRedireccion("btnClases", "clasesSocio.html");
    asignarRedireccion("btnPlan", "planSocio.html");
    asignarRedireccion("btnBeneficios", "beneficiosSocio.html");
    asignarRedireccion("btnRutinas", "rutinaSocio.html");
    asignarRedireccion("btnAlimentacion", "alimentacionSocio.html");
    asignarRedireccion("btnPersonalTrainer", "personalTrainerSocio.html");
}

function inicializarModalPlan() {
    let btn = document.getElementById("btnModificarPlan");
    if (!btn) return;

    btn.addEventListener("click", () => {
        let modalHeader = document.getElementById("modalHeader");
        let modalBody = document.getElementById("modalBody");
        let botonModal = document.getElementById("botonModal");
        let modal = new bootstrap.Modal(document.getElementById("myModal"));

        modalHeader.innerHTML = "Modificación de Plan";
        modalBody.innerHTML = "¿Estás seguro que querés modificar tu plan actual?";
        botonModal.classList.remove("btn-secondary");
        botonModal.classList.add("btn-success");
        botonModal.textContent = "Confirmar";
        modal.show();
    });
}
