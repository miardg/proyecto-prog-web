// Horarios del gimnasio
const horariosGimnasio = {
  "Lunes": { inicio: "06:00", fin: "23:00" },
  "Martes": { inicio: "06:00", fin: "23:00" },
  "Miércoles": { inicio: "06:00", fin: "23:00" },
  "Jueves": { inicio: "06:00", fin: "23:00" },
  "Viernes": { inicio: "06:00", fin: "23:00" },
  "Sábado": { inicio: "08:00", fin: "20:00" },
  "Domingo": { inicio: "09:00", fin: "18:00" }
};

document.addEventListener("DOMContentLoaded", () => {
  if (document.getElementById("verMisClases")) {
    cargarMisClases();
  }
  if (document.getElementById("verTodasLasClases")) {
    cargarClasesDisponibles();
  }
  if (document.getElementById("crearClaseForm")) {
    crearClase();

    const form = document.getElementById("crearClaseForm");
    const diaEl = form.querySelector('[name="dia_semana"]');
    const lugarEl = form.querySelector('[name="lugar"]');
    const durEl = form.querySelector('[name="duracion_min"]');

    const refrescar = () => actualizarHorariosDisponibles();

    if (diaEl) diaEl.addEventListener('change', refrescar);
    if (lugarEl) lugarEl.addEventListener('change', refrescar);
    if (durEl) durEl.addEventListener('input', refrescar);

    refrescar();
  }

  if (document.getElementById("formModificarClase")) {
    const form = document.getElementById("formModificarClase");
    form.addEventListener("submit", enviarClaseModificada);

    const refrescar = () => actualizarHorariosDisponibles("formModificarClase", "btnModificarClase");

    const diaEl = form.querySelector('[name="dia_semana"]');
    const lugarEl = form.querySelector('[name="lugar"]');
    const durEl = form.querySelector('[name="duracion_min"]');

    if (diaEl) diaEl.addEventListener('change', refrescar);
    if (lugarEl) lugarEl.addEventListener('change', refrescar);
    if (durEl) durEl.addEventListener('input', refrescar);

    refrescar();
  }
});

// Utilidades de tiempo
function parseTime(hhmm) {
  const [h, m] = hhmm.split(":").map(Number);
  return h * 3600 + m * 60;
}
function formatTime(seconds) {
  const h = Math.floor(seconds / 3600);
  const m = Math.floor((seconds % 3600) / 60);
  return `${String(h).padStart(2, "0")}:${String(m).padStart(2, "0")}`;
}
function solapa(rIni, rFin, oIni, oFin) {
  return rIni < oFin && rFin > oIni;
}

// ==================== EVITAR SOLAPAMIENTO DE CLASES TANTO PARA CREAR COMO PARA MODIFICAR ====================
async function actualizarHorariosDisponibles(formId = "crearClaseForm", botonId = "btnCrearClase") {
  const form = document.getElementById(formId);
  if (!form) return;

  const dia = form.querySelector('[name="dia_semana"]')?.value || "";
  const lugar = form.querySelector('[name="lugar"]')?.value || "";
  const duracion = parseInt(form.querySelector('[name="duracion_min"]')?.value || "60", 10);
  const selectHora = form.querySelector('[name="hora_inicio"]');

  selectHora.innerHTML = "";
  selectHora.disabled = true;

  if (!dia || !lugar || !selectHora || !horariosGimnasio[dia]) {
    const opt = document.createElement("option");
    opt.value = "";
    opt.textContent = "Seleccione día, sala y duración";
    opt.disabled = true;
    opt.selected = true;
    selectHora.appendChild(opt);
    return;
  }

  let ocupados = [];
  try {
    const resp = await fetch(`obtener_horarios_ocupados.php?dia=${encodeURIComponent(dia)}&lugar=${encodeURIComponent(lugar)}`);
    ocupados = await resp.json();
  } catch (e) {
    console.error("No se pudieron cargar horarios ocupados", e);
  }

  const ocupadosSeg = ocupados.map(o => ({
    ini: parseTime(o.inicio),
    fin: parseTime(o.fin)
  }));

  const rango = horariosGimnasio[dia];
  let inicio = parseTime(rango.inicio);
  const finGim = parseTime(rango.fin);

  let opcionesGeneradas = 0;

  while (inicio + duracion * 60 <= finGim) {
    const finSlot = inicio + duracion * 60;
    const haySolape = ocupadosSeg.some(o => solapa(inicio, finSlot, o.ini, o.fin));

    if (!haySolape) {
      const opt = document.createElement("option");
      opt.value = formatTime(inicio);
      opt.textContent = `${formatTime(inicio)} - ${formatTime(finSlot)}`;
      selectHora.appendChild(opt);
      opcionesGeneradas++;
    }

    inicio += 15 * 60;
  }

  if (!opcionesGeneradas) {
    const opt = document.createElement("option");
    opt.value = "";
    opt.textContent = "Sin horarios disponibles";
    opt.disabled = true;
    opt.selected = true;
    selectHora.appendChild(opt);
  } else {
    selectHora.selectedIndex = 0;
  }

  selectHora.disabled = opcionesGeneradas === 0;

  const btn = document.getElementById(botonId);
  if (btn) {
    btn.disabled = opcionesGeneradas === 0;
  }
}

// ==================== CREAR CLASE ====================

function crearClase() {
  const form = document.getElementById("crearClaseForm");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    if (!form.checkValidity()) {
      form.classList.add("was-validated");
      return;
    }

    form.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
    const feedback = document.getElementById("claseFeedback");
    feedback.innerHTML = "";

    const formData = new FormData(form);

    try {
      const resp = await fetch("procesar_crear_clase.php", {
        method: "POST",
        body: formData
      });
      const data = await resp.json();

      if (data.success) {
        const alertBox = document.createElement("div");
        alertBox.className = "alert alert-success text-center mt-3";
        alertBox.textContent = data.message;
        feedback.appendChild(alertBox);
        form.reset();
        form.classList.remove("was-validated");
        form.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
        actualizarHorariosDisponibles();
      } else {
        if (data.field) {
          const input = form.querySelector(`[name="${data.field}"]`);
          if (input) {
            input.classList.add("is-invalid");
            const invalidFeedback = input.nextElementSibling;
            if (invalidFeedback && invalidFeedback.classList.contains("invalid-feedback")) {
              invalidFeedback.textContent = data.message;
            }
          }
        } else {
          const alertBox = document.createElement("div");
          alertBox.className = "alert alert-danger text-center mt-3";
          alertBox.textContent = data.message;
          feedback.appendChild(alertBox);
        }
      }
    } catch (err) {
      console.error("Error AJAX:", err);
      const alertBox = document.createElement("div");
      alertBox.className = "alert alert-danger text-center mt-3";
      alertBox.textContent = "Error de conexión con el servidor.";
      feedback.appendChild(alertBox);
    }
  });
}

// ==================== VER CLASES (VISTA SOCIO / ADMIN) ====================
async function cargarClasesDisponibles() {
  const contenedor = document.getElementById("verTodasLasClases");
  contenedor.innerHTML = "<p>Cargando clases...</p>";

  try {
    const resp = await fetch("obtener_clases.php");
    const data = await resp.json();

    const clases = data.clases || [];
    const esSocio = !!data.esSocio;
    const puedeModificar = !!data.puedeModificar;

    if (!clases.length) {
      contenedor.innerHTML = "<p>No hay clases disponibles.</p>";
      return;
    }

    const tabla = document.createElement("table");
    tabla.className = "table table-bordered table-hover";

    const thead = document.createElement("thead");
    thead.className = "table-dark";
    thead.innerHTML = `
      <tr>
        <th>Nombre</th>
        <th>Actividad</th>
        <th>Día</th>
        <th>Hora</th>
        <th>Duración</th>
        <th>Lugar</th>
        <th>Profesor</th>
        <th>Cupo</th>
        <th>Estado</th>
        ${(esSocio || puedeModificar) ? "<th>Acciones</th>" : ""}
      </tr>
    `;
    tabla.appendChild(thead);

    const tbody = document.createElement("tbody");

    clases.forEach(c => {
      const fila = document.createElement("tr");

      fila.innerHTML = `
        <td>${c.nombre_clase}</td>
        <td>${c.tipo_actividad}</td>
        <td>${c.dia_semana}</td>
        <td>${(c.hora_inicio || '').slice(0, 5)}</td>
        <td>${c.duracion_min} min</td>
        <td>${c.lugar}</td>
        <td>${c.profesor ?? 'Sin asignar'}</td>
        <td>${c.cupo_maximo}</td>
        <td>${c.estado}</td>
      `;

      // Columna de acciones (SI NO TENES PERMISOS PARA MODIFICAR O CANCELAR, MOSTRAMOS SOLAMENTE LA TABLA)
      if (esSocio || puedeModificar) {
        const tdAcciones = document.createElement("td");

        if (esSocio) {
          const btnAnotarse = document.createElement("button");
          btnAnotarse.className = "btn btn-success btn-sm";
          btnAnotarse.textContent = "Anotarse";
          btnAnotarse.addEventListener("click", () => anotarseClase(c.id_clase));
          tdAcciones.appendChild(btnAnotarse);
        } else if (puedeModificar) {
          const btnModificar = document.createElement("button");
          btnModificar.className = "btn btn-warning btn-sm me-2";
          btnModificar.textContent = "Modificar";
          btnModificar.addEventListener("click", () => modificarClase(c));
          tdAcciones.appendChild(btnModificar);

          const btnCancelar = document.createElement("button");
          btnCancelar.className = "btn btn-danger btn-sm";
          btnCancelar.textContent = "Inactivar";
          btnCancelar.addEventListener("click", () => cancelarClase(c.id_clase));
          tdAcciones.appendChild(btnCancelar);
        }

        fila.appendChild(tdAcciones);
      }

      tbody.appendChild(fila);
    });

    tabla.appendChild(tbody);

    contenedor.innerHTML = "";
    contenedor.appendChild(tabla);
  } catch (e) {
    console.error("Error al cargar clases", e);
    contenedor.innerHTML = `<p class="text-danger">Error al conectar con el servidor.</p>`;
  }
}


// ==================== ACCIONES ====================
async function anotarseClase(idClase) {
  try {
    const resp = await fetch("anotarse_clase.php", {
      method: "POST",
      body: new URLSearchParams({ id_clase: idClase })
    });
    const resultado = await resp.json();

    if (resultado.exito) {
      if (document.getElementById("verTodasLasClases")) cargarClasesDisponibles();
    } else {
      alert(resultado.mensaje || "No se pudo inscribir.");
    }
  } catch (error) {
    console.error("Error al inscribirse", error);
    alert("Hubo un problema al procesar la inscripción.");
  }
}

// ==================== MODIFICAR CLASE ====================

function modificarClase(c) {
  document.getElementById("mod-id-clase").value = c.id_clase;
  document.getElementById("mod-nombre").value = c.nombre_clase;
  document.getElementById("mod-actividad").value = c.tipo_actividad;
  document.getElementById("mod-dia").value = c.dia_semana;
  document.getElementById("mod-duracion").value = c.duracion_min;
  document.getElementById("mod-lugar").value = c.lugar;
  document.getElementById("mod-cupo").value = c.cupo_maximo;

  cargarProfesores("mod-profesor", c.profesor_id);

  actualizarHorariosDisponibles("formModificarClase", "btnModificarClase");

  const modal = new bootstrap.Modal(document.getElementById("modalModificarClase"));
  modal.show();
}

async function enviarClaseModificada(e) {
  e.preventDefault();

  const form = e.target;
  const feedback = form.querySelector("#claseFeedbackMod") || document.createElement("div");
  feedback.id = "claseFeedbackMod";
  feedback.className = "mt-2";
  if (!form.contains(feedback)) {
    form.appendChild(feedback);
  }
  feedback.innerHTML = "";

  if (!form.checkValidity()) {
    form.classList.add("was-validated");
    return;
  }

  const formData = new FormData(form);

  try {
    const resp = await fetch("procesar_modificar_clase.php", {
      method: "POST",
      body: formData
    });
    const data = await resp.json();

    if (data.success) {
      const alertBox = document.createElement("div");
      alertBox.className = "alert alert-success text-center mt-3";
      alertBox.textContent = data.message || "Clase modificada correctamente";
      feedback.appendChild(alertBox);

      setTimeout(() => {
        bootstrap.Modal.getInstance(form.closest(".modal")).hide();
        cargarClasesDisponibles();
      }, 1000);

      form.classList.remove("was-validated");
      form.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
    } else {
      if (data.field) {
        const input = form.querySelector(`[name="${data.field}"]`);
        if (input) {
          input.classList.add("is-invalid");
          const invalidFeedback = input.nextElementSibling;
          if (invalidFeedback && invalidFeedback.classList.contains("invalid-feedback")) {
            invalidFeedback.textContent = data.message;
          }
        }
      } else {
        const alertBox = document.createElement("div");
        alertBox.className = "alert alert-danger text-center mt-3";
        alertBox.textContent = data.message || "No se pudo modificar la clase.";
        feedback.appendChild(alertBox);
      }
    }
  } catch (err) {
    console.error("Error al modificar clase", err);
    const alertBox = document.createElement("div");
    alertBox.className = "alert alert-danger text-center mt-3";
    alertBox.textContent = "Error de conexión con el servidor.";
    feedback.appendChild(alertBox);
  }
}

async function cancelarClase(idClase) {
  try {
    const datos = new FormData();
    datos.append("id_clase", idClase);

    const resp = await fetch("procesar_cancelar_clase.php", {
      method: "POST",
      body: datos
    });

    const result = await resp.json();
    if (result.success) {
      cargarClasesDisponibles();
    } else {
      console.error("Error al cancelar clase:", result.message);
    }
  } catch (e) {
    console.error("Error al conectar con el servidor", e);
  }
}

// ==================== VER CLASES ANOTADAS ====================

async function cargarMisClases() {
  const contenedor = document.getElementById("verMisClases");
  contenedor.innerHTML = "<p>Cargando mis clases...</p>";

  try {
    const resp = await fetch("obtener_mis_clases.php");
    const data = await resp.json();

    const clases = data.clases || [];

    if (!clases.length) {
      contenedor.innerHTML = "<p>No estás inscripto en ninguna clase.</p>";
      return;
    }

    const tabla = document.createElement("table");
    tabla.className = "table table-bordered table-hover";

    // Cabecera
    const thead = document.createElement("thead");
    thead.className = "table-dark";
    thead.innerHTML = `
      <tr>
        <th>Nombre</th>
        <th>Actividad</th>
        <th>Día</th>
        <th>Hora</th>
        <th>Duración</th>
        <th>Lugar</th>
        <th>Profesor</th>
        <th>Cupo</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    `;
    tabla.appendChild(thead);

    // Cuerpo
    const tbody = document.createElement("tbody");

    clases.forEach(c => {
      const fila = document.createElement("tr");

      fila.innerHTML = `
        <td>${c.nombre_clase}</td>
        <td>${c.tipo_actividad}</td>
        <td>${c.dia_semana}</td>
        <td>${(c.hora_inicio || '').slice(0, 5)}</td>
        <td>${c.duracion_min} min</td>
        <td>${c.lugar}</td>
        <td>${c.profesor ?? 'Sin asignar'}</td>
        <td>${c.cupo_maximo}</td>
        <td>${c.estado}</td>
      `;

      const tdAcciones = document.createElement("td");
      const btnCancelar = document.createElement("button");
      btnCancelar.className = "btn btn-danger btn-sm";
      btnCancelar.textContent = "Cancelar";
      btnCancelar.addEventListener("click", () => cancelarInscripcion(c.id_clase));

      tdAcciones.appendChild(btnCancelar);
      fila.appendChild(tdAcciones);
      tbody.appendChild(fila);
    });

    tabla.appendChild(tbody);

    contenedor.innerHTML = "";
    contenedor.appendChild(tabla);
  } catch (err) {
    console.error("Error al cargar mis clases:", err);
    contenedor.innerHTML = "<p>Error al cargar tus clases.</p>";
  }
}

// ==================== CANCELAR INSCRIPCION ====================

async function cancelarInscripcion(idClase) {
  try {
    const resp = await fetch("cancelar_inscripcion.php", {
      method: "POST",
      body: new URLSearchParams({ id_clase: idClase })
    });
    const resultado = await resp.json();

    const feedback = document.getElementById("clasesFeedback");
    if (resultado.exito) {
      cargarMisClases(); // recarga la tabla
    } else if (feedback) {
      const alertBox = document.createElement("div");
      alertBox.className = "alert alert-danger text-center mt-3";
      alertBox.textContent = resultado.mensaje || "No se pudo cancelar la inscripción.";
      feedback.innerHTML = "";
      feedback.appendChild(alertBox);
    }
  } catch (error) {
    console.error("Error al cancelar inscripción", error);
    const feedback = document.getElementById("clasesFeedback");
    if (feedback) {
      const alertBox = document.createElement("div");
      alertBox.className = "alert alert-danger text-center mt-3";
      alertBox.textContent = "Hubo un problema al cancelar la inscripción.";
      feedback.innerHTML = "";
      feedback.appendChild(alertBox);
    }
  }
}



