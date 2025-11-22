document.addEventListener("DOMContentLoaded", () => {
    cargarClasesSegunPermisos();
});

async function cargarClasesSegunPermisos() {
    const contenedor = document.getElementById("tablaClasesAdmin");
    contenedor.innerHTML = "<p>Cargando clases...</p>";

    let permisos = [];
    try {
        const resp = await fetch("obtener_permisos_usuario.php");
        permisos = await resp.json();
    } catch (e) {
        console.error("Error al obtener permisos", e);
    }

    const puedeVerClases = permisos.includes("Ver clases");
    const puedeCancelar = permisos.includes("Cancelar inscripción a clase");

    if (!puedeVerClases) {
        contenedor.innerHTML = "<p>No tenés permiso para ver clases.</p>";
        return;
    }

    try {
        const resp = await fetch("obtener_clases.php");
        const clases = await resp.json();

        if (!clases || clases.length === 0) {
            contenedor.innerHTML = "<p>No hay clases registradas.</p>";
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
          ${puedeCancelar ? "<th>Acciones</th>" : ""}
        </tr>
      </thead>
      <tbody>
        ${clases.map(c => `
          <tr>
            <td>${c.nombre_clase}</td>
            <td>${c.tipo_actividad}</td>
            <td>${c.dia_semana}</td>
            <td>${c.hora_inicio.slice(0, 5)}</td>
            <td>${c.duracion_min} min</td>
            <td>${c.lugar}</td>
            <td>${c.profesor ?? 'Sin asignar'}</td>
            <td>${c.cupo_maximo}</td>
            <td>${c.estado}</td>
            ${puedeCancelar ? `<td><button class="btn btn-danger btn-sm" onclick="cancelarInscripcion(${c.id_clase})">Cancelar</button></td>` : ""}
          </tr>
        `).join("")}
      </tbody>
    `;

        contenedor.innerHTML = "";
        contenedor.appendChild(tabla);
    } catch (err) {
        console.error("Error al cargar clases:", err);
        contenedor.innerHTML = "<p>Error al cargar las clases.</p>";
    }
}

async function cancelarInscripcion(idClase) {
    try {
        const resp = await fetch("cancelar_inscripcion.php", {
            method: "POST",
            body: new URLSearchParams({ id_clase: idClase })
        });
        const resultado = await resp.json();

        if (resultado.exito) {
            cargarClasesSegunPermisos(); // recarga la tabla
        } else {
            alert(resultado.mensaje || "No se pudo cancelar la inscripción.");
        }
    } catch (error) {
        console.error("Error al cancelar inscripción", error);
        alert("Hubo un problema al cancelar la inscripción.");
    }
}
