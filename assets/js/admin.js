document.addEventListener("DOMContentLoaded", () => {
    cargarPanelAdmin();
});

async function cargarPanelAdmin() {
    try {
        const resp = await fetch('./admin/obtener_estadisticas_admin.php');
        const resultado = await resp.json();

        if (!resultado || resultado.error) {
            document.getElementById("cardDatosAdmin").innerHTML =
                `<p class="text-danger">No se pudieron cargar tus datos.</p>`;
            return;
        }

        const admin = resultado.admin;
        const stats = resultado.estadisticas;
        const u = resultado.ultimoUsuario;
        const c = resultado.ultimaClase;
        const p = resultado.ultimoPlan;

        // Datos personales
        document.getElementById("cardDatosAdmin").innerHTML = `
      <h4 class="mb-3">${admin.nombre} ${admin.apellido}</h4>
      <div class="row">
        <div class="col-md-6">
          <p><strong>Email:</strong> ${admin.email}</p>
          <p><strong>Teléfono:</strong> ${admin.telefono ?? 'No registrado'}</p>
          <p><strong>DNI:</strong> ${admin.dni}</p>
        </div>
        <div class="col-md-6">
          <p><strong>Fecha de alta:</strong> ${admin.fecha_alta}</p>
          <p><strong>Estado:</strong> 
            <span class="${admin.estado === 'activo' ? 'text-success' : 'text-danger'}">
              ${admin.estado}
            </span>
          </p>
        </div>
      </div>
    `;

        // Estadísticas
        document.getElementById("totalUsuarios").textContent = stats.usuarios;
        document.getElementById("totalClases").textContent = stats.clases;
        document.getElementById("totalPlanes").textContent = stats.planes;

        // Último usuario
        document.getElementById("ultimoUsuarioNombre").textContent = `${u.nombre} ${u.apellido}`;
        document.getElementById("ultimoUsuarioEmail").textContent = u.email;
        document.getElementById("ultimoUsuarioFecha").textContent = u.fecha_alta;

        // Última clase
        document.getElementById("ultimaClaseNombre").textContent = c.nombre_clase;
        document.getElementById("ultimaClaseDia").textContent = c.dia_semana;
        document.getElementById("ultimaClaseProfesor").textContent = c.profesor;

        // Último plan
        document.getElementById("ultimoPlanNombre").textContent = p.nombre;
        document.getElementById("ultimoPlanPrecio").textContent = `$${p.precio}`;
        document.getElementById("ultimoPlanFrecuencia").textContent = p.frecuencia_servicios;

    } catch (e) {
        console.error("Error cargando panel del administrador", e);
        document.getElementById("cardDatosAdmin").innerHTML =
            `<p class="text-danger">Error al conectar con el servidor.</p>`;
    }
}
