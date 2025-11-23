// /assets/js/socios_pendientes.js
document.addEventListener("DOMContentLoaded", () => {
  cargarSociosPendientes();

  // Buscar
  document.getElementById("btnBuscarPendiente")?.addEventListener("click", () => {
    cargarSociosPendientes(document.getElementById("searchPendienteInput").value);
  });

  // Aprobar socio (form)
  document.getElementById("formAprobarSocio").addEventListener("submit", async (e) => {
    e.preventDefault();

    const idUsuario = document.getElementById("aprobarIdUsuario").value;
    const plan = document.getElementById("aprobarPlanSelect").value;
    const fechaAlta = document.getElementById("aprobarFechaAlta").value;
    const fechaVencimiento = document.getElementById("aprobarFechaVencimiento").value;

    if (!plan || !fechaAlta || !fechaVencimiento) {
      alert("Todos los campos son obligatorios");
      return;
    }

    const resp = await fetch("/proyecto-prog-web/views/socios/aprobar_socio.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ idUsuario, plan, fechaAlta, fechaVencimiento })
    });

    const data = await resp.json();
    if (data.success) {
      alert("Socio aprobado correctamente");
      location.reload();
    } else {
      alert("Error: " + data.error);
    }
  });
});

async function cargarSociosPendientes(busqueda = "") {
  const resp = await fetch('/proyecto-prog-web/views/socios/obtener_socios_pendientes.php?busqueda=' + encodeURIComponent(busqueda));
  const usuarios = await resp.json();

  const tbody = document.getElementById("sociosPendientesTbody");
  tbody.innerHTML = "";

  if (usuarios.error) {
    tbody.innerHTML = `<tr><td colspan="5" class="text-danger">${usuarios.error}</td></tr>`;
    return;
  }

  usuarios.forEach(u => {
    const tr = document.createElement("tr");

    // Celdas
    tr.innerHTML = `
      <td>${u.nombre} ${u.apellido}</td>
      <td class="d-none d-sm-table-cell">${u.email}</td>
      <td class="d-none d-md-table-cell">${u.fecha_alta ?? ''}</td>
      <td class="text-end">
        <div class="btn-group"></div>
      </td>
    `;

    // Botón aprobar
    const btnAprobar = document.createElement("button");
    btnAprobar.className = "btn btn-success btn-sm";
    btnAprobar.textContent = "Aprobar";
    btnAprobar.addEventListener("click", () => abrirModalAprobar(u.id_usuario));

    tr.querySelector(".btn-group").appendChild(btnAprobar);
    tbody.appendChild(tr);
  });
}

function abrirModalAprobar(idUsuario) {
  document.getElementById("aprobarIdUsuario").value = idUsuario;
  new bootstrap.Modal(document.getElementById("modalAprobarSocio")).show();
}
