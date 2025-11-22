document.addEventListener("DOMContentLoaded", () => {
  if (document.getElementById("listaPlanes")) {
    cargarPlanesDisponibles();
  }
  if (document.getElementById("miPlan")) {
    cargarMiPlan();
  }
  if (document.getElementById("planesLanding")) {
    cargarPlanesLanding();
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

async function cargarPlanesLanding() {
  try {
    const resp = await fetch('views/planes/obtener_planes.php'); // ajustá la ruta si es necesario
    const planes = await resp.json();
    const contenedor = document.getElementById("planesLanding");

    if (!planes || planes.length === 0) {
      contenedor.innerHTML = `<p class="text-muted text-center">No hay planes disponibles en este momento.</p>`;
      return;
    }

    contenedor.innerHTML = planes.map(plan => {
      const estilo = plan.nombre === 'Premium' ? 'bg-dark text-white'
        : plan.nombre === 'Standard' ? 'bg-warning text-dark'
          : plan.nombre === 'Élite' ? 'bg-secondary text-white'
            : 'bg-light';

      const precioColor = plan.nombre === 'Premium' ? 'text-warning'
        : plan.nombre === 'Standard' ? 'text-dark'
          : 'text-warning';

      return `
        <div class="col">
          <div class="card h-100 shadow">
            <div class="card-header text-center py-4 ${estilo}">
              <h4 class="fw-bold">${plan.nombre}</h4>
              <div class="display-6 fw-bold ${precioColor}">$${plan.precio}</div>
              <small class="text-frecuencia">${plan.frecuencia_servicios}</small>
            </div>
            <div class="card-body p-4">
              <p class="mb-0">${plan.descripcion}</p>
            </div>
          </div>
        </div>
      `;
    }).join('');
  } catch (e) {
    console.error("Error al cargar planes en la landing", e);
    document.getElementById("planesLanding").innerHTML =
      `<p class="text-danger text-center">Error al conectar con el servidor.</p>`;
  }
}