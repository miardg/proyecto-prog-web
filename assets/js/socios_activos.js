document.addEventListener("DOMContentLoaded", () => {
  cargarSociosActivos();

  // Buscar
  document.getElementById("btnBuscarSocio").addEventListener("click", () => {
    cargarSociosActivos(document.getElementById("searchSocioInput").value);
  });

  // Cambiar plan
  document.getElementById("formCambiarPlan").addEventListener("submit", async (e) => {
    e.preventDefault();
    const idSocio = document.getElementById("cambiarPlanIdSocio").value;
    const nuevoPlan = document.getElementById("nuevoPlanSelect").value;
    if (!nuevoPlan) return;

    const resp = await fetch('/proyecto-prog-web/views/socios/cambiar_plan.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ idSocio, nuevoPlan })
    });
    const resultado = await resp.json();
    if (resultado.success) {
      bootstrap.Modal.getInstance(document.getElementById("modalCambiarPlan")).hide();
      cargarSociosActivos();
    } else {
      alert("Error: " + resultado.error);
    }
  });

  // Dar de baja
  document.getElementById("btnConfirmarBaja").addEventListener("click", async () => {
    const idSocio = document.getElementById("bajaIdSocio").value;
    const resp = await fetch('/proyecto-prog-web/views/socios/baja_socio.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ idSocio })
    });
    const resultado = await resp.json();
    if (resultado.success) {
      bootstrap.Modal.getInstance(document.getElementById("modalBajaSocio")).hide();
      cargarSociosActivos();
    } else {
      alert("Error: " + resultado.error);
    }
  });
});

async function cargarSociosActivos(busqueda = "") {
  const resp = await fetch('/proyecto-prog-web/views/socios/obtener_socios_activos.php?busqueda=' + encodeURIComponent(busqueda));
  const socios = await resp.json();

  const tbody = document.getElementById("sociosActivosTbody");
  tbody.innerHTML = "";

  if (socios.error) {
    tbody.innerHTML = `<tr><td colspan="6" class="text-danger">${socios.error}</td></tr>`;
    return;
  }

  socios.forEach(s => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${s.nombre} ${s.apellido}</td>
      <td>${s.email}</td>
      <td>${s.plan}</td>
      <td>${s.fecha_inscripcion}</td>
      <td>${s.estado_cuota}</td>
      <td class="text-end">
        <div class="btn-group"></div>
      </td>
    `;

    // Botón Cambiar plan
    const btnCambiar = document.createElement("button");
    btnCambiar.className = "btn btn-sm btn-warning me-2";
    btnCambiar.textContent = "Cambiar plan";
    btnCambiar.addEventListener("click", () => abrirModalCambiarPlan(s.id_socio));

    // Botón Dar de baja
    const btnBaja = document.createElement("button");
    btnBaja.className = "btn btn-sm btn-danger";
    btnBaja.textContent = "Dar de baja";
    btnBaja.addEventListener("click", () => abrirModalBaja(s.id_socio));

    tr.querySelector(".btn-group").appendChild(btnCambiar);
    tr.querySelector(".btn-group").appendChild(btnBaja);

    tbody.appendChild(tr);
  });
}

function abrirModalCambiarPlan(idSocio) {
  document.getElementById("cambiarPlanIdSocio").value = idSocio;
  new bootstrap.Modal(document.getElementById("modalCambiarPlan")).show();
}

function abrirModalBaja(idSocio) {
  document.getElementById("bajaIdSocio").value = idSocio;
  new bootstrap.Modal(document.getElementById("modalBajaSocio")).show();
}
