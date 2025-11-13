document.addEventListener("DOMContentLoaded", async () => {
    // Llamadas iniciales: cada una va a pedir datos al backend
    let planSocio = await obtenerPlanSocio();
    let clases = await obtenerClases();
    let beneficios = await obtenerBeneficios();

    inicializarClases(clases, planSocio);
    inicializarSaludYProgreso(planSocio);
    inicializarInscripcionClasesModal();
    inicializarBotonPlan();
    inicializarBotonClases();
    inicializarBotonBeneficios();
    inicializarCanjeBeneficiosModal();
});

// ---------------- OBTENER DATOS (AJAX) ----------------

async function obtenerClases() {
    try {
        let resp = await fetch('./clases/obtener_clases.php');
        return await resp.json();
        // esperado: [{id_clase, nombre_clase, dia_semana, hora_inicio, tipo_actividad}, ...]
    } catch (e) {
        console.error("Error al obtener clases", e);
        return [];
    }
}

async function obtenerPlanSocio() {
    try {
        let resp = await fetch('./planes/obtener_plan_socio.php');
        return await resp.json();
        // esperado: {nombre, estado_membresia, fecha_vencimiento, clasesParticularesRestantes}
    } catch (e) {
        console.error("Error al obtener plan socio", e);
        return {};
    }
}

async function obtenerBeneficios() {
    try {
        let resp = await fetch('./beneficios/obtener_beneficios.php');
        return await resp.json();
        // esperado: [{id_beneficio, nombre, tipo, descripcion, canjeado}, ...]
    } catch (e) {
        console.error("Error al obtener beneficios", e);
        return [];
    }
}

// ---------------- LÓGICA DE INICIALIZACIÓN ----------------

function inicializarSaludYProgreso(plan) {
    let bloque = document.getElementById("saludProgreso");
    if (!bloque) return;
    bloque.classList.toggle("d-none", plan.nombre === "Básico");
}

function inicializarClases(clases, planSocio) {
    let contenedor = document.getElementById("clasesDisponibles");
    if (!contenedor) return;

    contenedor.innerHTML = "";
    clases.forEach(clase => {
        let tarjeta = document.createElement("div");
        tarjeta.className = "col-md-4";
        tarjeta.innerHTML = `
            <div class="card card-socio h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">${clase.nombre_clase}</h5>
                    <p><strong>Día:</strong> ${clase.dia_semana}</p>
                    <p><strong>Hora:</strong> ${clase.hora_inicio}</p>
                    <p><strong>Tipo:</strong> ${clase.tipo_actividad}</p>
                    <button class="btn btn-outline-warning w-100 mt-3 btn-anotarse" data-id="${clase.id_clase}">
                        Anotarse
                    </button>
                </div>
            </div>`;
        contenedor.appendChild(tarjeta);
    });

    contenedor.addEventListener("click", async e => {
        if (e.target.classList.contains("btn-anotarse")) {
            let idClase = e.target.dataset.id;
            let resp = await fetch('../views/clases/inscribir_clase.php', {
                method: 'POST',
                body: new URLSearchParams({ id_clase: idClase })
            });
            let resultado = await resp.json();
            alert(resultado.mensaje);
        }
    });
}

// ---------------- OTROS BLOQUES ----------------
function inicializarBotonPlan() {
    let btnPlan = document.getElementById("btnPlan");
    if (btnPlan) {
        btnPlan.addEventListener("click", async () => {
            try {
                let resp = await fetch("./planes/obtener_plan_socio.php");
                let plan = await resp.json();

                // Insertar datos en el modal
                let modalBody = document.getElementById("modalPlanSocioBody");
                modalBody.innerHTML = `
                    <p><strong>Nombre del plan:</strong> ${plan.nombre}</p>
                    <p><strong>Estado:</strong> 
                        <span class="${plan.estado_membresia === 'activa' ? 'text-success' : 'text-danger'}">
                            ${plan.estado_membresia}
                        </span>
                    </p>
                    <p><strong>Vencimiento:</strong> ${plan.fecha_vencimiento}</p>
                `;

                // Mostrar modal
                let modal = new bootstrap.Modal(document.getElementById("modalPlanSocio"));
                modal.show();
            } catch (e) {
                console.error("Error mostrando plan", e);
            }
        });
    }
}

function inicializarBotonClases() {
    let btnClases = document.getElementById("btnClases");
    if (btnClases) {
        btnClases.addEventListener("click", async () => {
            try {
                let resp = await fetch("./clases/obtener_clases.php");
                let clases = await resp.json();

                let modalBody = document.getElementById("modalClasesSocioBody");
                modalBody.innerHTML = "";

                if (clases.length === 0) {
                    modalBody.innerHTML = `<p class="text-center">No hay clases activas disponibles.</p>`;
                } else {
                    let fila = document.createElement("div");
                    fila.className = "row g-4";

                    clases.forEach(clase => {
                        let tarjeta = document.createElement("div");
                        tarjeta.className = "col-md-4";
                        tarjeta.innerHTML = `
                            <div class="card card-socio h-100 text-center">
                                <div class="card-body">
                                    <h5 class="card-title">${clase.nombre_clase}</h5>
                                    <p><strong>Día:</strong> ${clase.dia_semana}</p>
                                    <p><strong>Hora:</strong> ${clase.hora_inicio}</p>
                                    <p><strong>Tipo:</strong> ${clase.tipo_actividad}</p>
                                    <button class="btn btn-outline-warning w-100 mt-3 btn-anotarse" data-id="${clase.id_clase}">
                                        Anotarse
                                    </button>
                                </div>
                            </div>`;
                        fila.appendChild(tarjeta);
                    });

                    modalBody.appendChild(fila);
                }

                let modal = new bootstrap.Modal(document.getElementById("modalClasesSocio"));
                modal.show();
            } catch (e) {
                console.error("Error mostrando clases", e);
            }
        });
    }
}

function inicializarBotonBeneficios() {
    let btn = document.getElementById("btnBeneficios");
    if (!btn) return;

    btn.addEventListener("click", async () => {
        try {
            let resp = await fetch("./beneficios/obtener_beneficios.php");
            let beneficios = await resp.json();

            let modalBody = document.getElementById("modalBeneficiosSocioBody");
            modalBody.innerHTML = "";

            if (beneficios.length === 0) {
                modalBody.innerHTML = `<p class="text-center">No tenés beneficios disponibles.</p>`;
            } else {
                let fila = document.createElement("div");
                fila.className = "row g-4";

                beneficios.forEach(b => {
                    let tarjeta = document.createElement("div");
                    tarjeta.className = "col-md-6";
                    tarjeta.innerHTML = `
                        <div class="card card-socio h-100 text-center">
                            <div class="card-body">
                                <h5 class="card-title">${b.nombre}</h5>
                                <p>${b.descripcion}</p>
                                <button class="btn ${b.canjeado ? 'btn-outline-warning' : 'btn-outline-success'} w-100 mt-2 btn-canjear"
                                        data-id="${b.id_beneficio}" data-action="${b.canjeado ? 'descanjear' : 'canjear'}">
                                    ${b.canjeado ? 'Deshacer' : 'Canjear'}
                                </button>
                            </div>
                        </div>`;
                    fila.appendChild(tarjeta);
                });

                modalBody.appendChild(fila);
            }

            let modal = new bootstrap.Modal(document.getElementById("modalBeneficiosSocio"));
            modal.show();
        } catch (e) {
            console.error("Error mostrando beneficios", e);
            mostrarFeedback("No se pudieron cargar los beneficios.");
        }
    });
}

// ---------------- MODALES ----------------

// Feedback genérico
function mostrarFeedback(mensaje) {
    // Cerrar todos los modales abiertos
    document.querySelectorAll(".modal.show").forEach(modalEl => {
        let instance = bootstrap.Modal.getInstance(modalEl);
        if (instance) instance.hide();
    });

    // Mostrar el feedback
    let cuerpo = document.getElementById("modalFeedbackBody");
    cuerpo.innerHTML = `<p class="text-center">${mensaje}</p>`;
    let modal = new bootstrap.Modal(document.getElementById("modalFeedback"));
    modal.show();
}

// Inscripción a clases 
function inicializarInscripcionClasesModal() {
    let modalBody = document.getElementById("modalClasesSocioBody");
    if (!modalBody) return;

    modalBody.addEventListener("click", async e => {
        if (e.target.classList.contains("btn-anotarse")) {
            let idClase = e.target.dataset.id;
            if (!idClase) {
                mostrarFeedback("Clase inválida");
                return;
            }

            try {
                let resp = await fetch("./clases/inscribir_clase.php", {
                    method: "POST",
                    body: new URLSearchParams({ id_clase: idClase })
                });
                let resultado = await resp.json();
                mostrarFeedback(resultado.mensaje);
            } catch (error) {
                console.error("Error al inscribirse", error);
                mostrarFeedback("Hubo un problema al inscribirse.");
            }
        }
    });
}

// Canje de beneficios 
function inicializarCanjeBeneficiosModal() {
    let modalBody = document.getElementById("modalBeneficiosSocioBody");
    if (!modalBody) return;

    modalBody.addEventListener("click", async e => {
        if (e.target.classList.contains("btn-canjear")) {
            let id = e.target.dataset.id;
            let accion = e.target.dataset.action;

            try {
                let resp = await fetch("./beneficios/canjear_beneficio.php", {
                    method: "POST",
                    body: new URLSearchParams({ id_beneficio: id, accion })
                });
                let resultado = await resp.json();
                mostrarFeedback(resultado.mensaje);

            } catch (error) {
                console.error("Error al canjear beneficio", error);
                mostrarFeedback("Hubo un problema al procesar el beneficio.");
            }
        }
    });
}
