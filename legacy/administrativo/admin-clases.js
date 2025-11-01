
let classes = []; // Simulación de base de datos
let currentEditId = null;

// Referencias DOM
const classesTbody = document.getElementById("classesTbody");
const classModal = new bootstrap.Modal(document.getElementById("classModal"));
const classForm = document.getElementById("classForm");
const classModalTitle = document.getElementById("classModalTitle");

const searchClassInput = document.getElementById("searchClassInput");

// Crear nueva clase
document.querySelector(".btn-create-class").addEventListener("click", () => {
  currentEditId = null;
  classModalTitle.textContent = "Crear Clase";
  classForm.reset();
  resetValidation();
  classModal.show();
});

// Guardar clase (crear o editar)
classForm.addEventListener("submit", e => {
  e.preventDefault();
  resetValidation();

  const name = document.getElementById("className").value.trim();
  const instructor = document.getElementById("classInstructor").value.trim();
  const date = document.getElementById("classDate").value;
  const time = document.getElementById("classTime").value;
  const capacity = parseInt(document.getElementById("classCapacity").value);
  const room = document.getElementById("classRoom").value.trim();

  // Validación
  let valid = true;
  if (!name) { markInvalid("className"); valid = false; }
  if (!instructor) { markInvalid("classInstructor"); valid = false; }
  if (!date) { markInvalid("classDate"); valid = false; }
  if (!time) { markInvalid("classTime"); valid = false; }
  if (!capacity || capacity <= 0) { markInvalid("classCapacity"); valid = false; }
  if (!room) { markInvalid("classRoom"); valid = false; }

  if (!valid) return;

  if (currentEditId !== null) {
    // Editar clase existente
    const cls = classes.find(c => c.id === currentEditId);
    cls.name = name;
    cls.instructor = instructor;
    cls.date = date;
    cls.time = time;
    cls.capacity = capacity;
    cls.room = room;
    cls.published = cls.published; // mantiene estado actual
    showFeedback("Clase modificada exitosamente.");
  } else {
    // Crear nueva clase
    classes.push({
      id: Date.now(),
      name,
      instructor,
      date,
      time,
      capacity,
      room,
      published: false
    });
    showFeedback("Clase creada exitosamente.");
  }

  classModal.hide();
  renderClasses();
});

// Función para marcar input inválido
function markInvalid(id) {
  const input = document.getElementById(id);
  input.classList.add("is-invalid");
}

// Resetear validaciones
function resetValidation() {
  ["className","classInstructor","classDate","classTime","classCapacity","classRoom"].forEach(id=>{
    document.getElementById(id).classList.remove("is-invalid");
  });
}

// Renderizar tabla de clases
function renderClasses(filter = "") {
  classesTbody.innerHTML = "";
  classes
    .filter(c => c.name.toLowerCase().includes(filter.toLowerCase()) || c.instructor.toLowerCase().includes(filter.toLowerCase()))
    .forEach(cls => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${cls.name}</td>
        <td>${cls.instructor}</td>
        <td>${cls.date} ${cls.time}</td>
        <td>${cls.capacity}</td>
        <td>${cls.room}</td>
        <td>${cls.published ? "Publicado" : "Borrador"}</td>
        <td class="text-end">
          <button class="btn btn-sm btn-warning btn-edit-class me-1"><i class="fas fa-edit"></i></button>
          <button class="btn btn-sm btn-danger btn-delete-class me-1"><i class="fas fa-trash"></i></button>
          <button class="btn btn-sm btn-success btn-publish-class">${cls.published ? "Despublicar" : "Publicar"}</button>
        </td>
      `;
      // Editar
      tr.querySelector(".btn-edit-class").addEventListener("click", () => {
        currentEditId = cls.id;
        classModalTitle.textContent = "Editar Clase";
        document.getElementById("className").value = cls.name;
        document.getElementById("classInstructor").value = cls.instructor;
        document.getElementById("classDate").value = cls.date;
        document.getElementById("classTime").value = cls.time;
        document.getElementById("classCapacity").value = cls.capacity;
        document.getElementById("classRoom").value = cls.room;
        resetValidation();
        classModal.show();
      });
      // Eliminar
      tr.querySelector(".btn-delete-class").addEventListener("click", () => {
        if (confirm("¿Seguro que querés eliminar esta clase?")) {
          classes = classes.filter(c => c.id !== cls.id);
          renderClasses(searchClassInput.value);
          showFeedback("Clase eliminada exitosamente.");
        }
      });
      // Publicar / despublicar
      tr.querySelector(".btn-publish-class").addEventListener("click", () => {
        cls.published = !cls.published;
        renderClasses(searchClassInput.value);
        showFeedback(cls.published ? "Clase publicada." : "Clase despublicada.");
      });

      classesTbody.appendChild(tr);
    });
}

// Buscar clases
searchClassInput.addEventListener("input", () => {
  renderClasses(searchClassInput.value);
});

// Modal Feedback
function showFeedback(msg) {
  const modal = new bootstrap.Modal(document.getElementById("modalFeedback"));
  document.getElementById("modal-title").textContent = "Información";
  document.getElementById("modal-body").textContent = msg;
  modal.show();
}

// Inicial
renderClasses();
