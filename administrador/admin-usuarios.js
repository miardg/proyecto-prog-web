// ===== Simulación de base de datos =====
let users = [
  { id: 1, name: "Juan Pérez", email: "juan@mail.com", role: "admin", password: "123456" },
  { id: 2, name: "María Gómez", email: "maria@mail.com", role: "member", password: "abcdef" },
  { id: 3, name: "Carlos Ruiz", email: "carlos@mail.com", role: "trainer", password: "qwerty" },
];

let editUserId = null;

// ===== Referencias DOM =====
const usersTbody = document.getElementById("usersTbody");
const searchUserInput = document.getElementById("searchUserInput");
const userModal = new bootstrap.Modal(document.getElementById("userModal"));
const userForm = document.getElementById("userForm");
const userModalTitle = document.getElementById("userModalTitle");
const userIdInput = document.getElementById("userId");
const userNameInput = document.getElementById("userName");
const userEmailInput = document.getElementById("userEmail");
const userRoleInput = document.getElementById("userRole");
const userPasswordInput = document.getElementById("userPassword");
const btnCreateUser = document.querySelector(".btn-create-user");
const confirmDeleteUserModal = new bootstrap.Modal(document.getElementById("confirmDeleteUserModal"));
let userIdToDelete = null;
const btnConfirmDeleteUser = document.querySelector(".btn-confirm-delete-user");
const modalFeedback = new bootstrap.Modal(document.getElementById("modalFeedback"));
const modalTitle = document.getElementById("modal-title");
const modalBody = document.getElementById("modal-body");

// ===== Renderizado =====
function renderUsers(list) {
  usersTbody.innerHTML = "";
  list.forEach(user => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${user.name}</td>
      <td>${user.email}</td>
      <td>${capitalize(user.role)}</td>
      <td class="text-end">
        <button class="btn btn-sm btn-warning btn-edit-user me-2"><i class="fas fa-edit"></i></button>
        <button class="btn btn-sm btn-danger btn-delete-user"><i class="fas fa-trash"></i></button>
      </td>
    `;
    // Editar
    tr.querySelector(".btn-edit-user").addEventListener("click", () => openEditUserModal(user));
    // Eliminar
    tr.querySelector(".btn-delete-user").addEventListener("click", () => openDeleteUserModal(user.id));
    usersTbody.appendChild(tr);
  });
}

function capitalize(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

// ===== Crear / Editar Usuario =====
btnCreateUser.addEventListener("click", () => {
  editUserId = null;
  userForm.reset();
  userModalTitle.textContent = "Crear Usuario";
  userPasswordInput.parentElement.style.display = "block";
  userModal.show();
});

function openEditUserModal(user) {
  editUserId = user.id;
  userModalTitle.textContent = "Editar Usuario";
  userIdInput.value = user.id;
  userNameInput.value = user.name;
  userEmailInput.value = user.email;
  userRoleInput.value = user.role;
  userPasswordInput.value = ""; // No mostrar contraseña
  userPasswordInput.parentElement.style.display = "block";
  userModal.show();
}

// ===== Validación simple =====
function validateUserForm() {
  let valid = true;

  if (userNameInput.value.trim().length < 3) {
    userNameInput.classList.add("is-invalid");
    valid = false;
  } else userNameInput.classList.remove("is-invalid");

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(userEmailInput.value.trim())) {
    userEmailInput.classList.add("is-invalid");
    valid = false;
  } else userEmailInput.classList.remove("is-invalid");

  if (!userRoleInput.value) {
    userRoleInput.classList.add("is-invalid");
    valid = false;
  } else userRoleInput.classList.remove("is-invalid");

  if (!editUserId && userPasswordInput.value.trim().length < 6) {
    userPasswordInput.classList.add("is-invalid");
    valid = false;
  } else userPasswordInput.classList.remove("is-invalid");

  return valid;
}

// ===== Guardar =====
userForm.addEventListener("submit", (e) => {
  e.preventDefault();
  if (!validateUserForm()) return;

  const userData = {
    id: editUserId || Date.now(),
    name: userNameInput.value.trim(),
    email: userEmailInput.value.trim(),
    role: userRoleInput.value,
    password: userPasswordInput.value.trim() || (editUserId ? users.find(u => u.id === editUserId).password : ""),
  };

  if (editUserId) {
    // Editar
    const index = users.findIndex(u => u.id === editUserId);
    users[index] = userData;
    showFeedback("Usuario actualizado correctamente");
  } else {
    // Crear
    users.push(userData);
    showFeedback("Usuario creado correctamente");
  }

  renderUsers(users);
  userModal.hide();
});

// ===== Eliminar =====
function openDeleteUserModal(id) {
  userIdToDelete = id;
  confirmDeleteUserModal.show();
}

btnConfirmDeleteUser.addEventListener("click", () => {
  users = users.filter(u => u.id !== userIdToDelete);
  renderUsers(users);
  confirmDeleteUserModal.hide();
  showFeedback("Usuario eliminado correctamente");
});

// ===== Buscar =====
searchUserInput.addEventListener("input", () => {
  const query = searchUserInput.value.toLowerCase();
  const filtered = users.filter(u => u.name.toLowerCase().includes(query) || u.email.toLowerCase().includes(query));
  renderUsers(filtered);
});

// ===== Feedback =====
function showFeedback(message) {
  modalTitle.textContent = "¡Listo!";
  modalBody.textContent = message;
  modalFeedback.show();
}

// ===== Inicial =====
renderUsers(users);
