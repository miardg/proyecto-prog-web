window.onload = function () {
    let clases = obtenerClases();
    let planSocio = obtenerPlanSocio();
    let beneficios = obtenerBeneficios();

    inicializarRedirecciones();
    inicializarClases(clases, planSocio);
    inicializarModalPlan();
    inicializarBuscador(beneficios);
    inicializarTabla(beneficios);
    inicializarSaludYProgreso(planSocio);
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

function obtenerBeneficios() {
    return [
        { id: 1, nombre: "10% en suplementos", tipo: "Descuento", descripcion: "Válido en tienda asociada", canjeado: false },
        { id: 2, nombre: "Sesión de masajes", tipo: "Servicio", descripcion: "1 sesión mensual incluida", canjeado: false },
        { id: 3, nombre: "Acceso a spa", tipo: "Convenio", descripcion: "Disponible para planes Premium y Élite", canjeado: false }
    ];
}

function inicializarSaludYProgreso(plan) {
    let bloque = document.getElementById("saludProgreso");
    if (!bloque) return;

    if (plan.nombre !== "Básico") {
        bloque.classList.remove("d-none");
    } else {
        bloque.classList.add("d-none");
    }
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

function inicializarTabla(lista) {
    let tabla = document.getElementById("tablaBeneficios");
    if (!tabla) return;

    tabla.innerHTML = "";

    lista.forEach(b => {
        let fila = document.createElement("tr");
        fila.innerHTML = `
    <td>${b.nombre}</td>
    <td>${b.tipo}</td>
    <td>${b.descripcion}</td>
    <td>
      <button 
        class="btn btn-sm ${b.canjeado ? 'btn-warning' : 'btn-success'}"
        data-id="${b.id}"
        data-action="${b.canjeado ? 'descanjear' : 'canjear'}"
      >
        ${b.canjeado ? 'Deshacer' : 'Canjear'}
      </button>
    </td>
  `;
        tabla.appendChild(fila);
    });

    tabla.addEventListener("click", (e) => {
        let id = parseInt(e.target.dataset.id);
        let accion = e.target.dataset.action;
        let beneficio = lista.find(b => b.id === id);
        if (!beneficio) return;

        if (accion === "canjear" && !beneficio.canjeado) {
            canjearBeneficio(beneficio, lista);
        } else if (accion === "descanjear" && beneficio.canjeado) {
            descanjearBeneficio(beneficio, lista);
        }
    });
}

function inicializarBuscador(lista) {
    let buscador = document.getElementById("buscadorBeneficio");
    if (!buscador) return;

    buscador.addEventListener("input", () => {
        let texto = buscador.value.toLowerCase();
        let filtrados = lista.filter(b =>
            b.nombre.toLowerCase().includes(texto) ||
            b.tipo.toLowerCase().includes(texto) ||
            b.descripcion.toLowerCase().includes(texto)
        );
        inicializarTabla(filtrados);
    });
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

function canjearBeneficio(beneficio, lista) {
    beneficio.canjeado = true;

    let modal = new bootstrap.Modal(document.getElementById("modalBeneficio"));
    let modalBody = document.getElementById("modalBeneficioBody");
    let modalLabel = document.getElementById("modalBeneficioLabel");

    modalLabel.textContent = "Canje exitoso";
    modalBody.innerHTML = `
      <p>Has canjeado el beneficio <strong>${beneficio.nombre}</strong>.</p>
      <p>Tipo: ${beneficio.tipo}</p>
      <p>${beneficio.descripcion}</p>
    `;
    modal.show();

    inicializarTabla(lista);
}

function descanjearBeneficio(beneficio, lista) {
    beneficio.canjeado = false;

    let modal = new bootstrap.Modal(document.getElementById("modalBeneficio"));
    let modalBody = document.getElementById("modalBeneficioBody");
    let modalLabel = document.getElementById("modalBeneficioLabel");

    modalLabel.textContent = "Canje revertido";
    modalBody.innerHTML = `
      <p>Has revertido el canje del beneficio <strong>${beneficio.nombre}</strong>.</p>
      <p>Ahora está disponible nuevamente.</p>
    `;
    modal.show();

    inicializarTabla(lista);
}
