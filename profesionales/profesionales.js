window.onload = function() {
    // Rol de ejemplo: "pt" o "nutri"
    let rol = "nutri"; // cambiar dinámicamente según la sesión

    // Elementos del DOM
    let dashboard = document.getElementById("dashboard");
    let dashboardTitle = document.getElementById("dashboardTitle");
    let calendarioTitle = document.getElementById("calendarioTitle");
    let calendarioText = document.getElementById("calendarioText");
    let gestionTitle = document.getElementById("gestionTitle");
    let gestionText = document.getElementById("gestionText");
    let btnGestion = document.getElementById("btnGestion");
    let btnLogout = document.getElementById("btnLogout");

    // Mostrar el dashboard
    if (dashboard) {
        dashboard.style.display = "block";
    }

    // Configuración de textos según rol
    if (rol === "pt") {
        dashboardTitle.textContent = "Panel Personal Trainer";
        calendarioTitle.textContent = "Disponibilidad PT";
        calendarioText.textContent = "Visualiza y gestiona Disponibilidad.";
        gestionTitle.textContent = "Gestión de Rutinas";
        gestionText.textContent = "Sube o elimina archivos de rutinas asociadas a cada socio.";
        btnGestion.textContent = "Gestionar Rutinas";
    } else if (rol === "nutri") {
        dashboardTitle.textContent = "Panel Nutricionista";
        calendarioTitle.textContent = "Disponibilidad Nutricionista";
        calendarioText.textContent = "Visualiza y gestiona Disponibilidad.";
        gestionTitle.textContent = "Gestión de Planes de Alimentación";
        gestionText.textContent = "Sube o elimina planes alimentarios asociados a cada socio.";
        btnGestion.textContent = "Gestionar Planes";
    }

    // Botón cerrar sesión
    if (btnLogout) {
        btnLogout.addEventListener("click", function() {
            window.location.href = ""; //acaba deberia manejar el cierre de sesion
        });
    }
}
