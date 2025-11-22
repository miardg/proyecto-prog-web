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
  if (document.getElementById("listaClases")) {
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
});

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

// Llena el select [name="hora_inicio"] con intervalos de 15 min
async function actualizarHorariosDisponibles() {
  const form = document.getElementById("crearClaseForm");
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

  // Obtener ocupados del backend
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

  const btnCrear = document.getElementById("btnCrearClase");
  if (btnCrear) {
    btnCrear.disabled = opcionesGeneradas === 0;
  }
}

function crearClase() {
  const form = document.getElementById("crearClaseForm");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault(); // evita que el navegador vaya a procesar_crear_clase.php

    // limpiar estados previos
    form.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
    form.querySelectorAll(".alert").forEach(el => el.remove());

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
        form.prepend(alertBox);
        form.reset();
        actualizarHorariosDisponibles();
      } else {
        if (data.field) {
          const input = form.querySelector(`[name="${data.field}"]`);
          if (input) {
            input.classList.add("is-invalid");
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains("invalid-feedback")) {
              feedback.textContent = data.message;
            }
          }
        } else {
          const alertBox = document.createElement("div");
          alertBox.className = "alert alert-danger text-center mt-3";
          alertBox.textContent = data.message;
          form.prepend(alertBox);
        }
      }
    } catch (err) {
      console.error("Error AJAX:", err);
      const alertBox = document.createElement("div");
      alertBox.className = "alert alert-danger text-center mt-3";
      alertBox.textContent = "Error de conexión con el servidor.";
      form.prepend(alertBox);
    }
  });
}

async function cargarClasesDisponibles() {
  const contenedor = document.getElementById("listaClases");
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

    tabla.innerHTML = `
      <thead class="table-dark">
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
      </thead>
      <tbody>
        ${clases.map(c => `
          <tr>
            <td>${c.nombre_clase}</td>
            <td>${c.tipo_actividad}</td>
            <td>${c.dia_semana}</td>
            <td>${(c.hora_inicio || '').slice(0, 5)}</td>
            <td>${c.duracion_min} min</td>
            <td>${c.lugar}</td>
            <td>${c.profesor ?? 'Sin asignar'}</td>
            <td>${c.cupo_maximo}</td>
            <td>${c.estado}</td>
            ${(esSocio)
        ? `<td><button class="btn btn-success btn-sm" onclick="anotarseClase(${c.id_clase})">Anotarse</button></td>`
        : (puedeModificar ? `<td><button class="btn btn-warning btn-sm" onclick="modificarClase(${c.id_clase})">Modificar</button></td>` : "")
      }
          </tr>
        `).join("")}
      </tbody>
    `;

    contenedor.innerHTML = "";
    contenedor.appendChild(tabla);
  } catch (e) {
    console.error("Error al cargar clases", e);
    contenedor.innerHTML = `<p class="text-danger">Error al conectar con el servidor.</p>`;
  }
}

async function anotarseClase(idClase) {
  try {
    const resp = await fetch("anotarse_clase.php", {
      method: "POST",
      body: new URLSearchParams({ id_clase: idClase })
    });
    const resultado = await resp.json();

    if (resultado.exito) {
      cargarClasesDisponibles();
    } else {
      alert(resultado.mensaje || "No se pudo inscribir.");
    }
  } catch (error) {
    console.error("Error al inscribirse", error);
    alert("Hubo un problema al procesar la inscripción.");
  }
}

function modificarClase(idClase) {
  alert("Modificar clase (pendiente) ID: " + idClase);
}
