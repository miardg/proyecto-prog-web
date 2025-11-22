document.addEventListener("DOMContentLoaded", () => {
  if (document.getElementById("listaClases")) {
    cargarClasesDisponibles();
  }
  if (document.getElementById("misClases")) {
    cargarMisClases();
  }
});

async function cargarClasesDisponibles() {
  try {
    const resp = await fetch('obtener_clases.php');
    const clases = await resp.json();
    const contenedor = document.getElementById("listaClases");

    if (!clases || clases.length === 0) {
      contenedor.innerHTML = `<p class="text-muted">No hay clases disponibles.</p>`;
      return;
    }

    contenedor.innerHTML = clases.map(clase => `
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">${clase.nombre_clase}</h5>
          <p><strong>Actividad:</strong> ${clase.tipo_actividad}</p>
          <p><strong>Día:</strong> ${clase.dia_semana}</p>
          <p><strong>Hora:</strong> ${clase.hora_inicio}</p>
          <p><strong>Duración:</strong> ${clase.duracion_min} min</p>
          <p><strong>Lugar:</strong> ${clase.lugar}</p>
          <p><strong>Cupo máximo:</strong> ${clase.cupo_maximo}</p>
          <button class="btn btn-success me-2" onclick="anotarseClase(event, ${clase.id_clase})">Anotarse</button>
        </div>
      </div>
    `).join('');
  } catch (e) {
    console.error("Error al cargar clases", e);
    document.getElementById("listaClases").innerHTML =
      `<p class="text-danger">Error al conectar con el servidor.</p>`;
  }
}

async function anotarseClase(event, idClase) {
  try {
    const resp = await fetch('anotarse_clase.php', {
      method: 'POST',
      body: new URLSearchParams({ id_clase: idClase })
    });
    const resultado = await resp.json();

    if (resultado.exito) {
      cargarClasesDisponibles();
      if (document.getElementById("misClases")) {
        cargarMisClases();
      }
    } else {
      alert(resultado.mensaje || "No se pudo inscribir.");
    }
  } catch (error) {
    console.error("Error al inscribirse", error);
    alert("Hubo un problema al procesar la inscripción.");
  }
}


async function cargarMisClases() {
  try {
    const resp = await fetch('obtener_mis_clases.php');
    const clases = await resp.json();
    const contenedor = document.getElementById("misClases");

    if (!clases || clases.length === 0) {
      contenedor.innerHTML = `<p class="text-muted">No estás anotado en ninguna clase.</p>`;
      return;
    }

    contenedor.innerHTML = clases.map(clase => `
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">${clase.nombre_clase}</h5>
          <p><strong>Actividad:</strong> ${clase.tipo_actividad}</p>
          <p><strong>Día:</strong> ${clase.dia_semana}</p>
          <p><strong>Hora:</strong> ${clase.hora_inicio}</p>
          <p><strong>Duración:</strong> ${clase.duracion_min} min</p>
          <p><strong>Lugar:</strong> ${clase.lugar}</p>
          <button class="btn btn-danger" onclick="cancelarInscripcion(${clase.id_clase})">
            Cancelar inscripción
          </button>
        </div>
      </div>
    `).join('');
  } catch (e) {
    console.error("Error al cargar tus clases", e);
    document.getElementById("misClases").innerHTML =
      `<p class="text-danger">Error al conectar con el servidor.</p>`;
  }
}

async function cancelarInscripcion(idClase) {
  try {
    const resp = await fetch('cancelar_inscripcion.php', {
      method: 'POST',
      body: new URLSearchParams({ id_clase: idClase })
    });
    const resultado = await resp.json();

    if (resultado.exito) {
      // Recargar listado de mis clases
      cargarMisClases();
    } else {
      alert(resultado.mensaje || "No se pudo cancelar la inscripción.");
    }
  } catch (error) {
    console.error("Error al cancelar inscripción", error);
    alert("Hubo un problema al cancelar la inscripción.");
  }
}
