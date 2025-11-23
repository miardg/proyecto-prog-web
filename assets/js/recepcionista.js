document.addEventListener("DOMContentLoaded", () => {
    cargarPanelRecepcionista();
});

async function cargarPanelRecepcionista() {
    try {
        const resp = await fetch('./recepcionista/obtener_estadisticas_recepcionista.php');
        const resultado = await resp.json();

        if (!resultado || resultado.error) {
            document.getElementById("cardDatosRecepcionista").innerHTML =
                `<p class="text-danger">No se pudieron cargar tus datos.</p>`;
            return;
        }

        const recep = resultado.recepcionista;
        const stats = resultado.estadisticas;
        const ultimoSocio = resultado.ultimoSocio;
        const ultimoPago = resultado.ultimoPago;

        // Datos personales
        document.getElementById("cardDatosRecepcionista").innerHTML = `
          <h4 class="mb-3">${recep.nombre} ${recep.apellido}</h4>
          <div class="row">
            <div class="col-md-6">
              <p><strong>Email:</strong> ${recep.email}</p>
              <p><strong>Teléfono:</strong> ${recep.telefono ?? 'No registrado'}</p>
            </div>
            <div class="col-md-6">
              <p><strong>Fecha de alta:</strong> ${recep.fecha_alta}</p>
              <p><strong>Estado:</strong> 
                <span class="${recep.estado === 'activo' ? 'text-success' : 'text-danger'}">
                  ${recep.estado}
                </span>
              </p>
            </div>
          </div>
        `;

        // Estadísticas
        document.getElementById("totalSociosActivos").textContent = stats.socios_activos;
        document.getElementById("totalSociosPendientes").textContent = stats.socios_pendientes;
        document.getElementById("totalPagosMes").textContent = `$${stats.pagos_mes}`;
        document.getElementById("totalDeudas").textContent = `$${stats.deudas}`;

        // Último socio aprobado
        document.getElementById("ultimoSocioNombre").textContent = `${ultimoSocio.nombre} ${ultimoSocio.apellido}`;
        document.getElementById("ultimoSocioEmail").textContent = ultimoSocio.email;
        document.getElementById("ultimoSocioFecha").textContent = ultimoSocio.fecha_alta;

        // Último pago registrado
        document.getElementById("ultimoPagoSocio").textContent = `${ultimoPago.nombre} ${ultimoPago.apellido}`;
        document.getElementById("ultimoPagoMonto").textContent = `$${ultimoPago.monto}`;
        document.getElementById("ultimoPagoFecha").textContent = ultimoPago.fecha_pago;

    } catch (e) {
        console.error("Error cargando panel del recepcionista", e);
        document.getElementById("cardDatosRecepcionista").innerHTML =
            `<p class="text-danger">Error al conectar con el servidor.</p>`;
    }
}
