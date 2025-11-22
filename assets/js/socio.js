document.addEventListener("DOMContentLoaded", () => {
    cargarDatosSocio();
});

async function cargarDatosSocio() {
    try {
        const resp = await fetch('./socios/obtener_datos_socio.php');
        const datos = await resp.json();
        const contenedor = document.getElementById("cardDatosSocio");

        if (!datos || datos.error) {
            contenedor.innerHTML = `<p class="text-danger">No se pudieron cargar tus datos.</p>`;
            return;
        }

        contenedor.innerHTML = `
  <h4 class="mb-3">${datos.nombre} ${datos.apellido}</h4>
  <div class="row">
    <div class="col-md-6">
      <p><strong>Email:</strong> ${datos.email}</p>
      <p><strong>Teléfono:</strong> ${datos.telefono ?? 'No registrado'}</p>
      <p><strong>DNI:</strong> ${datos.dni}</p>
    </div>
    <div class="col-md-6">
      <p><strong>Plan:</strong> ${datos.plan ?? 'Sin plan asignado'}</p>
      <p><strong>Inscripción:</strong> ${datos.fecha_inscripcion}</p>
      <p><strong>Vencimiento:</strong> ${datos.fecha_vencimiento ?? 'Sin fecha'}</p>
      <p><strong>Estado:</strong> 
        <span class="${datos.estado_membresia === 'activa' ? 'text-success' : 'text-danger'}">
          ${datos.estado_membresia}
        </span>
      </p>
    </div>
  </div>
`;
    } catch (e) {
        console.error("Error cargando datos del socio", e);
        document.getElementById("cardDatosSocio").innerHTML = `<p class="text-danger">Error al conectar con el servidor.</p>`;
    }
}
