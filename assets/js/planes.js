document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("listaPlanes")) {
        cargarPlanesDisponibles();
    }
    if (document.getElementById("miPlan")) {
        cargarMiPlan();
    }
});

async function cargarPlanesDisponibles() {
    try {
        const resp = await fetch('obtener_planes.php');
        const planes = await resp.json();
        const contenedor = document.getElementById("listaPlanes");

        if (!planes || planes.length === 0) {
            contenedor.innerHTML = `<p class="text-muted">No hay planes disponibles.</p>`;
            return;
        }

        contenedor.innerHTML = planes.map(plan => `
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">${plan.nombre}</h5>
          <p>${plan.descripcion}</p>
          <p><strong>Precio:</strong> $${plan.precio}</p>
          <p><strong>Frecuencia:</strong> ${plan.frecuencia_servicios}</p>
        </div>
      </div>
    `).join('');
    } catch (e) {
        console.error("Error al cargar planes", e);
        document.getElementById("listaPlanes").innerHTML =
            `<p class="text-danger">Error al conectar con el servidor.</p>`;
    }
}

async function cargarMiPlan() {
    try {
        const resp = await fetch('obtener_plan_socio.php');
        const plan = await resp.json();
        const contenedor = document.getElementById("miPlan");

        if (!plan || plan.error || Object.keys(plan).length === 0) {
            contenedor.innerHTML = `<p class="text-muted">No tenés un plan asignado actualmente.</p>`;
            return;
        }

        contenedor.innerHTML = `
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">${plan.nombre}</h5>
          <p>${plan.descripcion}</p>
          <p><strong>Precio:</strong> $${plan.precio}</p>
          <p><strong>Frecuencia:</strong> ${plan.frecuencia_servicios}</p>
        </div>
      </div>
    `;
    } catch (e) {
        console.error("Error al cargar tu plan", e);
        document.getElementById("miPlan").innerHTML =
            `<p class="text-danger">Error al conectar con el servidor.</p>`;
    }
}
