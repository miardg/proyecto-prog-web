document.addEventListener("DOMContentLoaded", () => {
  cargarSociosPago();

  document.getElementById("btnBuscarPago").addEventListener("click", () => {
    cargarSociosPago(document.getElementById("searchPagoInput").value);
  });

  document.getElementById("formRegistrarPago").addEventListener("submit", registrarPagoSubmit);
});

async function cargarSociosPago(busqueda = "") {
  const resp = await fetch('/proyecto-prog-web/views/pagos/obtener_socios_para_pago.php?busqueda=' + encodeURIComponent(busqueda));
  const socios = await resp.json();

  const tbody = document.getElementById("sociosPagoTbody");
  tbody.innerHTML = "";

  if (socios.error) {
    tbody.innerHTML = `<tr><td colspan="6" class="text-danger">${socios.error}</td></tr>`;
    return;
  }

  socios.forEach(s => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
  <td>${s.nombre} ${s.apellido}</td>
  <td class="d-none d-sm-table-cell">${s.email}</td>
  <td>${s.plan}</td>
  <td class="d-none d-md-table-cell">${s.ultimo_pago ?? ''}</td>
  <td>${s.estado_cuota}</td>
  <td class="d-none d-md-table-cell">${s.fecha_vencimiento ?? ''}</td>
  <td class="text-end"><div class="btn-group"></div></td>
`;

    const btnRegistrar = document.createElement("button");
    btnRegistrar.className = "btn btn-success btn-sm";
    btnRegistrar.textContent = "Registrar pago";
    btnRegistrar.addEventListener("click", () => abrirModalPago(s));

    tr.querySelector(".btn-group").appendChild(btnRegistrar);
    tbody.appendChild(tr);
  });
}

function abrirModalPago(socio) {
  document.getElementById("pagoIdSocio").value = socio.id_socio;
  document.getElementById("pagoMonto").value = "";
  document.getElementById("pagoMetodo").value = "";
  document.getElementById("pagoFecha").value = new Date().toISOString().slice(0, 10);
  document.getElementById("pagoPeriodo").value = "";

  document.getElementById("pagoResumenSocio").textContent =
    `${socio.nombre} ${socio.apellido} • Plan: ${socio.plan} • Vencimiento: ${socio.fecha_vencimiento ?? '-'}`;

  new bootstrap.Modal(document.getElementById("modalRegistrarPago")).show();
}

async function registrarPagoSubmit(e) {
  e.preventDefault();
  const idSocio = document.getElementById("pagoIdSocio").value;
  const monto = document.getElementById("pagoMonto").value;
  const metodo = document.getElementById("pagoMetodo").value;
  const fechaPago = document.getElementById("pagoFecha").value;
  const periodo = document.getElementById("pagoPeriodo").value;

  if (!idSocio || !monto || !metodo || !fechaPago || !periodo) {
    alert("Todos los campos son obligatorios");
    return;
  }

  const resp = await fetch('/proyecto-prog-web/views/pagos/registrar_pago.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ idSocio, monto, metodo, fechaPago, periodo })
  });
  const resultado = await resp.json();

  if (resultado.success) {
    bootstrap.Modal.getInstance(document.getElementById("modalRegistrarPago")).hide();
    cargarSociosPago();
  } else {
    alert("Error: " + (resultado.error || "No se pudo registrar el pago"));
  }
}
