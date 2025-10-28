window.onload = function () {
    let clases = obtenerClases();
    let planSocio = obtenerPlanSocio();
    let beneficios = obtenerBeneficios();

    inicializarRedirecciones();
    inicializarClases(clases, planSocio);
    inicializarBuscador(beneficios);
    inicializarTabla(beneficios);
    inicializarSaludYProgreso(planSocio);
    cargarPersonalTrainer();
    cargarTablaRutinas();
    descargarPDFalimentacion();
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

//FUNCION PARA LOS ICONOS DE LAS CLASES DEL USUARIO
function obtenerIconoClase(nombre) {
    let iconos = {
        Funcional: "fa-dumbbell",
        Crossfit: "fa-fire",
        Yoga: "fa-spa",
        HIIT: "fa-bolt",
        Powerlifting: "fa-weight-hanging",
        Spinning: "fa-bicycle"
    };
    return iconos[nombre] || "fa-running";
}

function inicializarClases(clases, planSocio) {
    let contenedor = document.getElementById("clasesDisponibles");
    if (!contenedor) return;

    clases.forEach(clase => {
        let icono = obtenerIconoClase(clase.nombre);

        let card = document.createElement("div");
        card.className = "col-md-4";

        card.innerHTML = `
  <div class="card card-socio h-100 text-center">
    <div class="card-body">
      <i class="fas ${icono} display-5 text-warning mb-3"></i>
      <h5 class="card-title">${clase.nombre}</h5>
      <p class="card-text">${clase.dia} - ${clase.horario}</p>
      <button class="btn btn-warning w-100">Reservar</button>
    </div>
  </div>
`;

        // Asignar evento al botón
        let btn = card.querySelector("button");
        btn.addEventListener("click", () => {
            mostrarModalClase(clase, planSocio);
        });

        contenedor.appendChild(card);
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

function obtenerIconoBeneficio(tipo) {
    let iconos = {
        Descuento: "fa-tag",
        Producto: "fa-gift",
        Servicio: "fa-concierge-bell",
        Evento: "fa-calendar-check"
    };
    return iconos[tipo] || "fa-star";
}

function inicializarTabla(lista) {
    let tabla = document.getElementById("tablaBeneficios");
    if (!tabla) return;

    tabla.innerHTML = "";

    lista.forEach(b => {
        let icono = obtenerIconoBeneficio(b.tipo);
        let fila = document.createElement("tr");
        fila.innerHTML = `
  <td><i class="fas ${icono} text-warning me-2"></i>${b.nombre}</td>
  <td>${b.tipo}</td>
  <td>${b.descripcion}</td>
  <td>
    <button 
      class="btn btn-sm ${b.canjeado ? 'btn-outline-warning' : 'btn-outline-success'} fw-bold"
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

    asignarRedireccion("btnClases", "clasesSocio.php");
    asignarRedireccion("btnPlan", "planSocio.php");
    asignarRedireccion("btnBeneficios", "beneficiosSocio.php");
    asignarRedireccion("btnRutinas", "rutinaSocio.php");
    asignarRedireccion("btnPersonalTrainer", "personalTrainerSocio.php");
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

function cargarPersonalTrainer() {
    let contenedor = document.getElementById("bloqueTrainer");
    if (!contenedor) return;

    let card = document.createElement("div");
    card.className = "col-md-6 offset-md-3";

    card.innerHTML = `
  <div class="card card-socio h-100 text-center">
    <div class="card-body">
      <i class="fas fa-user-tie display-5 text-warning mb-3"></i>
      <h5 class="card-title">Tu Entrenador</h5>
      <p class="card-text"><strong>Nombre:</strong> Juan Pérez</p>
      <p class="card-text"><strong>Objetivo actual:</strong> Mejorar resistencia y tonificar</p>
      <p class="card-text"><strong>Próxima sesión:</strong> Martes 1/10 - 18:00 hs</p>
      <div class="d-flex justify-content-center gap-3 mt-4">
        <button id="btnHistorialTrainer" class="btn btn-outline-warning">Ver Historial</button>
        <button id="btnSolicitarTrainer" class="btn btn-warning">Solicitar Sesión</button>
      </div>
    </div>
  </div>
`;

    contenedor.appendChild(card);

    // Evento del botón dentro de la misma función
    let btn = document.getElementById("btnHistorialTrainer");
    if (btn) {
        btn.addEventListener("click", () => {
            let modal = new bootstrap.Modal(document.getElementById("modalHistorialTrainer"));
            modal.show();
        });
    }

    let btnSolicitar = document.getElementById("btnSolicitarTrainer");
    if (btnSolicitar) {
        btnSolicitar.addEventListener("click", () => {
            let modalCargando = new bootstrap.Modal(document.getElementById("modalCargandoTurno"));
            modalCargando.show();

            setTimeout(() => {
                modalCargando.hide();
            }, 2000); // se cierra después de 2 segundos
        });
    }
}

function obtenerIconoRutina(nombre) {
    let iconos = {
        "Fuerza tren inferior": "fa-running",
        "Fuerza tren superior": "fa-dumbbell",
        "Cardio HIIT": "fa-bolt",
        "Core + movilidad": "fa-child",
        "Full body": "fa-person-running"
    };
    return iconos[nombre] || "fa-star";
}

function cargarTablaRutinas() {
    let rutinas = [
        { dia: "Lunes", rutina: "Fuerza tren inferior", objetivo: "Tonificar piernas", duracion: "45 min" },
        { dia: "Martes", rutina: "Cardio HIIT", objetivo: "Resistencia y quema grasa", duracion: "30 min" },
        { dia: "Miércoles", rutina: "Core + movilidad", objetivo: "Fortalecer abdomen", duracion: "40 min" },
        { dia: "Jueves", rutina: "Fuerza tren superior", objetivo: "Tonificar brazos y espalda", duracion: "50 min" },
        { dia: "Sábado", rutina: "Full body", objetivo: "Trabajo general", duracion: "60 min" }
    ];

    let tabla = document.getElementById("tablaRutinas");
    if (!tabla) return;

    tabla.innerHTML = ""; // Limpieza previa

    rutinas.forEach(r => {
        let icono = obtenerIconoRutina(r.rutina);
        let fila = document.createElement("tr");
        fila.innerHTML = `
      <td>${r.dia}</td>
      <td><i class="fas ${icono} text-warning me-2"></i>${r.rutina}</td>
      <td>${r.objetivo}</td>
      <td>${r.duracion}</td>
    `;
        tabla.appendChild(fila);
    });
}

function descargarPDFalimentacion() {
    let btnDescargar = document.getElementById("btnAlimentacion");
    let modal = new bootstrap.Modal(document.getElementById("modalDescargandoPDF"));

    btnDescargar.addEventListener("click", () => {
        modal.show();

        // Simula cierre automático después de 2.5 segundos
        setTimeout(() => {
            modal.hide();
        }, 2500);
    });
}
