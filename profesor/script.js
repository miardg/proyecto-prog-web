// clases hardcodeadas
const clases = [
  { id: 1, nombre: "Funcional", fecha: "2025-09-29", hora: "10:00" },
  { id: 2, nombre: "Crossfit", fecha: "2025-09-30", hora: "18:00" },
  { id: 3, nombre: "Crossfit", fecha: "2025-09-29", hora: "19:00" },
  { id: 4, nombre: "Funcional", fecha: "2025-10-01", hora: "08:00" }
];


// alumnos hardcodeados
const inscritos = {
  1: [{ id: 1, nombre: "Ana López" }, { id: 2, nombre: "Carlos Pérez" }],
  2: [{ id: 3, nombre: "Lucía Torres" }, { id: 4, nombre: "Marcos Díaz" }],
  3: [{ id: 5, nombre: "Julieta Romero" }, { id: 6, nombre: "Pablo Fernández" }],
  4: [{ id: 7, nombre: "Sofía Gómez" }, { id: 8, nombre: "Tomás Méndez" }]
};

// socios hardcodeados
const socios = {
  1: [{ id: 1, dni: "30123456" }, { id: 2, dni: "31234567" }],
  2: [{ id: 3, dni: "32345678" }, { id: 4, dni: "33456789" }],
  3: [{ id: 5, dni: "34567890" }, { id: 6, dni: "35678901" }],
  4: [{ id: 7, dni: "36789012" }, { id: 8, dni: "37890123" }]
};

function hoy() {
  const d = new Date();
  return d.toISOString().split("T")[0];
}

function cargarAsistencia() {
  let params = new URLSearchParams(window.location.search);
  let claseId = parseInt(params.get("claseId"));
  let clase = clases.find(c => c.id === claseId);
  let lista = document.getElementById("listaInscritos");
  let formAsistencia = document.getElementById("formAsistencia");
  let valor = "";

  if (!claseId) return;
  if (!clase) return;

  // mostrar info de la clase
  document.getElementById("claseTitulo").textContent = clase.nombre;
  document.getElementById("claseDetalle").textContent = `${clase.fecha} - ${clase.hora}`;

  // mostrar inscritos
  if (!lista) return;
  lista.innerHTML = "";

  (inscritos[claseId] || []).forEach(alumno => {

    let li = document.createElement("li");
    li.className = "list-group-item d-flex justify-content-between align-items-center";
    li.innerHTML = `
      <span>${alumno.nombre}</span>
      <select class="form-select w-auto" name="alumno-${alumno.id}">
        <option value="presente">Presente</option>
        <option value="ausente">Ausente</option>
      </select>
    `;
    lista.appendChild(li);
  });

  // guardar asistencia
  if (formAsistencia) {
    formAsistencia.addEventListener("submit", (e) => {
      e.preventDefault();
      const data = {};
      (inscritos[claseId] || []).forEach(alumno => {
        valor = formAsistencia.querySelector(`[name=alumno-${alumno.id}]`).value;
        data[alumno.nombre] = valor;
      });

      showToast("📝 Asistencia guardada correctamente");

    });
  }


}


function cargarClasesCancelar() {

  let lista = document.getElementById("listaClasesCancelar");

  if (!lista) return;

  lista.innerHTML = "";
  clases.forEach((c, i) => {
    let li = document.createElement("li");
    li.className = "list-group-item d-flex justify-content-between align-items-center";
    li.innerHTML = `
      <span>${c.nombre} - ${c.fecha} ${c.hora}</span>
      <button class="btn btn-sm btn-danger">Cancelar</button>
    `;
    li.querySelector("button").addEventListener("click", function () {

      let cancelarModal = bootstrap.Modal.getInstance(document.getElementById("modalCancelarClase"));
      clases.splice(i, 1);
      cancelarModal.hide();
      mostrarToast(`📢 La clase "${c.nombre}" fue cancelada y se notificó a los inscritos.`);
      cargarClasesCancelar();

    });
    lista.appendChild(li);
  });

}

//cargar las clases del dia para tomar asistencia desde el dashboard
function cargarClasesAsistencia() {
  let lista = document.getElementById("listaClasesAsistencia");
  let clasesHoy = clases.filter(c => c.fecha === hoy());

  if (!lista) return;

  lista.innerHTML = "";

  if (clasesHoy.length === 0) {
    lista.innerHTML = `<li class="list-group-item">No hay clases asignadas para hoy</li>`;
    return;
  }

  clasesHoy.forEach(c => {
    let li = document.createElement("li");
    li.className = "list-group-item d-flex justify-content-between align-items-center";
    li.innerHTML = `
      <span>${c.nombre} - ${c.hora}</span>
      <button class="btn btn-sm btn-success">Tomar lista</button>
    `;
    li.querySelector("button").addEventListener("click", function () {
      //redirige a la pantalla de asistencia con el id de la clase seleccionada
      window.location.href = `asistencia.html?claseId=${c.id}`;
    });
    lista.appendChild(li);
  });
}

function cargarCalendario() {



  let contenedor = document.getElementById("listaClasesCalendario");
  if (!contenedor) return;



  contenedor.innerHTML = "";
  clases.forEach(clase => {
    contenedor.innerHTML += `
      <div class="col-md-4 mb-4">
        <div class="card plan-card h-100">
          <div class="card-body">
            <h5 class="card-title">${clase.nombre}</h5>
            <p class="card-text"><strong>Fecha:</strong> ${clase.fecha}</p>
            <p class="card-text"><strong>Hora:</strong> ${clase.hora}</p>
            <a href="clase-detalle.html?id=${clase.id}" class="btn btn-warning w-100">Ver Detalle</a>
          </div>
        </div>
      </div>
    `;
  });
}

function cargarDetalleClase() {

  let params = new URLSearchParams(window.location.search);
  let claseId = parseInt(params.get("id"));
  let clase = clases.find(c => c.id === claseId);
  let btnCancelar = document.getElementById("btnCancelar");
  let listaInscritos = document.getElementById("listaInscritos");
  let formAviso = document.getElementById("formAviso");
  let listaAsistencia = document.getElementById("listaAsistencia");
  let formAsistencia = document.getElementById("formAsistencia");
  let formInscribirSocio = document.getElementById("formInscribirSocio");


  if (!claseId) return;
  if (!clase) return;


  document.getElementById("claseNombre").textContent = clase.nombre;
  document.getElementById("claseFecha").textContent = clase.fecha;
  document.getElementById("claseHora").textContent = clase.hora;



  listaInscritos.innerHTML = "";
  (inscritos[claseId] || []).forEach(alumno => {
    let li = document.createElement("li");
    li.className = "list-group-item";
    li.textContent = alumno.nombre;
    listaInscritos.appendChild(li);
  });

  //cancelar la clase
  btnCancelar.addEventListener("click", function () {

    let cancelarModal = bootstrap.Modal.getOrCreateInstance(document.getElementById("cancelarClaseModal"));
    cancelarModal.show();

    let botonConfirmar = document.getElementById("confirmarCancelacionBtn");

    botonConfirmar.onclick = function () {
      cancelarModal.hide();
      mostrarToast(`📢 La clase "${clase.nombre}" fue cancelada y se notificó a los inscritos.`);
    };
  });


  //enviar aviso por correo
  if (formAviso) {
    formAviso.addEventListener("submit", function (e) {
      e.preventDefault();
      let asunto = document.getElementById("avisoAsunto").value;
      let mensaje = document.getElementById("avisoMensaje").value;

      mostrarToast(`📩 Aviso enviado: "${asunto}" a todos los inscritos`);
      bootstrap.Modal.getInstance(document.getElementById("modalAviso")).hide();
      formAviso.reset();
    });
  }

  //tomar asistencia
  if (listaAsistencia) {
    listaAsistencia.innerHTML = "";
    (inscritos[claseId] || []).forEach(alumno => {
      let li = document.createElement("li");
      li.className = "list-group-item d-flex justify-content-between align-items-center";
      li.innerHTML = `
        <span>${alumno.nombre}</span>
        <select class="form-select w-auto" name="alumno-${alumno.id}">
          <option value="presente">Presente</option>
          <option value="ausente">Ausente</option>
        </select>
      `;
      listaAsistencia.appendChild(li);
    });
  }


  if (formAsistencia) {
    formAsistencia.addEventListener("submit", function (e) {

      e.preventDefault();
      showToast("📝 Asistencia guardada correctamente");
      bootstrap.Modal.getInstance(document.getElementById("modalAsistencia")).hide();

    });
  }

  //inscribir socio
  formInscribirSocio.addEventListener("submit", function (e) {
    e.preventDefault();
    let dni = document.getElementById("dniSocio").value;
    let socioEncontrado = null;
    if (!dni) return;

    // Buscar socio por DNI en todos los grupos
    for (const grupo in socios) {
      let socio = socios[grupo].find(s => s.dni === dni);
      if (socio) {
        socioEncontrado = socio;
        break;
      }
    }

    if (!socioEncontrado) {
      bootstrap.Modal.getInstance(document.getElementById("modalInscribir")).hide();
      mostrarToast(`❌ No se encontró ningún socio con DNI ${dni}.`);
      return;
    }

    bootstrap.Modal.getInstance(document.getElementById("modalInscribir")).hide();
    mostrarToast(`✅ Socio con DNI ${dni} fue inscrito en la clase.`);
  });
}

function mostrarToast(msg) {
  let toastElemento = document.getElementById("toastSuccess");
  let toastMsg = document.getElementById("toastMsg");
  toastMsg.textContent = msg;
  const toast = new bootstrap.Toast(toastElemento);
  toast.show();
}


document.addEventListener("DOMContentLoaded", function () {

  cargarClasesCancelar();
  cargarClasesAsistencia();
  cargarAsistencia();
  cargarDetalleClase();

  let calendarioElemento = document.getElementById("calendar");

  let clasesCalendario = clases.map(clase => ({
    id: clase.id,
    title: `${clase.nombre} (${clase.hora})`,
    start: `${clase.fecha}T${clase.hora}`
  }));

  if (!calendarioElemento) return;



  const calendario = new FullCalendar.Calendar(calendarioElemento, {
    initialView: "dayGridMonth",
    locale: "es",
    themeSystem: "bootstrap5",
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay"
    },
    buttonText: {
      today: "Hoy",
      month: "Mes",
      week: "Semana",
      day: "Día"
    },
    events: clasesCalendario,
    eventClick: function (info) {
      window.location.href = `clase-detalle.html?id=${info.event.id}`;
    }
  });

  calendario.render();

});

