window.onload = function () {
  // Botón de logout
  let btnLogout = document.getElementById("btnLogout");
  if (btnLogout) {
    btnLogout.addEventListener("click", function () {
      window.location.href = "login.html";
    });
  }

  // Lista de socios simulada
  let socios = [
    { id: 1, nombre: "Juan Pérez" },
    { id: 2, nombre: "María Gómez" },
    { id: 3, nombre: "Carlos López" },
    { id: 4, nombre: "Ana Martínez" }
  ];

  // Elementos del DOM
  let buscadorSocio = document.getElementById("buscadorSocio");
  let listaSocios = document.getElementById("listaSocios");
  let formularioPlan = document.getElementById("formularioPlan");
  let tituloFormulario = document.getElementById("tituloFormulario");
  let planForm = document.getElementById("planForm");

  let modal = new bootstrap.Modal(document.getElementById("resultadoModal"));
  let modalTitle = document.getElementById("modalTitle");
  let modalBody = document.getElementById("modalBody");

  let socioSeleccionado = null;

  // Mostrar socios en la lista
  function mostrarSocios(filtro = "") {
    listaSocios.innerHTML = "";
    socios
      .filter(s => s.nombre.toLowerCase().includes(filtro.toLowerCase()))
      .forEach(socio => {
        let item = document.createElement("li");
        item.classList.add("list-group-item", "list-group-item-action");
        item.textContent = socio.nombre;

        item.addEventListener("click", function () {
          socioSeleccionado = socio;
          tituloFormulario.textContent = "Subir Plan para " + socio.nombre;
          formularioPlan.style.display = "block";

          // marcar seleccionado
          document.querySelectorAll("#listaSocios li").forEach(li => li.classList.remove("active"));
          item.classList.add("active");
        });

        listaSocios.appendChild(item);
      });
  }

  // Buscar socios mientras se escribe
  buscadorSocio.addEventListener("input", function () {
    mostrarSocios(this.value);
  });

  // Manejar formulario con validaciones bootstrap
  planForm.addEventListener("submit", function (e) {
    e.preventDefault();
    let valido = true;

    if (!socioSeleccionado) {
      modalTitle.textContent = "Error";
      modalBody.textContent = "Por favor selecciona un socio antes de subir el plan.";
      modal.show();
      return;
    }

    let archivoPlan = document.getElementById("archivoPlan");
    if (!archivoPlan.files[0]) {
      archivoPlan.classList.add("is-invalid");
      valido = false;
    } else {
      archivoPlan.classList.remove("is-invalid");
      archivoPlan.classList.add("is-valid");
    }

    if (valido) {
      let archivo = archivoPlan.files[0];
      modalTitle.textContent = "Éxito";
      modalBody.textContent = `El plan "${archivo.name}" fue subido para ${socioSeleccionado.nombre}.`;
      modal.show();

      // Reiniciar
      planForm.reset();
      archivoPlan.classList.remove("is-valid");
      formularioPlan.style.display = "none";
      socioSeleccionado = null;
      document.querySelectorAll("#listaSocios li").forEach(li => li.classList.remove("active"));
    }
  });

  // Inicializar
  mostrarSocios();
};
