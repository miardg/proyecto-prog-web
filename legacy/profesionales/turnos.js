window.onload = function () {
  // Botón de logout
  let btnLogout = document.getElementById("btnLogout");
  btnLogout.addEventListener("click", function () {
    // Simulación de cierre de sesión
    alert("Sesión cerrada");
    window.location.href = "login.html";
  });

  // Tabla de turnos
  let tablaTurnos = document.getElementById("tablaTurnos").querySelector("tbody");

  // Datos simulados de turnos (más adelante vendrá de la BD)
  let turnos = [
    { socio: "Juan Pérez", actividad: "Personal Trainer", fecha: "2025-10-01", hora: "10:00", estado: "Confirmado" },
    { socio: "María Gómez", actividad: "Nutricionista", fecha: "2025-10-02", hora: "15:00", estado: "Pendiente" },
    { socio: "Carlos López", actividad: "Entrenamiento", fecha: "2025-10-03", hora: "09:00", estado: "Cancelado" }
  ];

  // Función para cargar los turnos en la tabla
  function cargarTurnos() {
    tablaTurnos.innerHTML = ""; // limpiar antes de cargar
    turnos.forEach(turno => {
      let fila = document.createElement("tr");

      // Crear celdas
      let celdaSocio = document.createElement("td");
      celdaSocio.textContent = turno.socio;

      let celdaActividad = document.createElement("td");
      celdaActividad.textContent = turno.actividad;

      let celdaFecha = document.createElement("td");
      celdaFecha.textContent = turno.fecha;

      let celdaHora = document.createElement("td");
      celdaHora.textContent = turno.hora;

      let celdaEstado = document.createElement("td");
      celdaEstado.textContent = turno.estado;

      // Estilo del estado
      if (turno.estado === "Confirmado") {
        celdaEstado.classList.add("text-success", "fw-bold");
      } else if (turno.estado === "Pendiente") {
        celdaEstado.classList.add("text-warning", "fw-bold");
      } else if (turno.estado === "Cancelado") {
        celdaEstado.classList.add("text-danger", "fw-bold");
      }

      // Agregar celdas a la fila
      fila.appendChild(celdaSocio);
      fila.appendChild(celdaActividad);
      fila.appendChild(celdaFecha);
      fila.appendChild(celdaHora);
      fila.appendChild(celdaEstado);

      // Insertar fila en la tabla
      tablaTurnos.appendChild(fila);
    });
  }

  // Cargar al iniciar
  cargarTurnos();
};
