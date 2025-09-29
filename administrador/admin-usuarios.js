document.addEventListener("DOMContentLoaded", () => {
  // ===== Datos predefinidos =====
  let users = [
    { id: 1, name: "Juan Pérez", email: "juan@mail.com", role: "admin", password: "123456" },
    { id: 2, name: "María Gómez", email: "maria@mail.com", role: "member", password: "abcdef" },
    { id: 3, name: "Carlos Ruiz", email: "carlos@mail.com", role: "trainer", password: "qwerty" }
  ];

  const userExtraData = {
    1: {
      plan: { name: "Plan Premium", end: "30/10/2025" },
      payments: [
        { date: "01/09/2025", amount: "$8.000" },
        { date: "01/08/2025", amount: "$8.000" }
      ],
      cuotas: [
        { month: "Septiembre", status: "Pagada" },
        { month: "Octubre", status: "Pendiente" }
      ]
    },
    2: {
      plan: { name: "Plan Básico", end: "15/11/2025" },
      payments: [{ date: "15/09/2025", amount: "$4.000" }],
      cuotas: [{ month: "Septiembre", status: "Pagada" }]
    },
    3: {
      plan: { name: "Entrenamiento Personal (paquete)", end: "01/12/2025" },
      payments: [{ date: "01/09/2025", amount: "$12.000" }],
      cuotas: [
        { month: "Septiembre", status: "Pagada" },
        { month: "Octubre", status: "Pendiente" }
      ]
    }
  };

  // ===== Referencias DOM =====
  const usersTbody = document.getElementById("usersTbody");
  const searchUserInput = document.getElementById("searchUserInput");
  const btnCreateUser = document.querySelector(".btn-create-user");

  const userModal = new bootstrap.Modal(document.getElementById("userModal"));
  const userForm = document.getElementById("userForm");
  const userModalTitle = document.getElementById("userModalTitle");
  const userIdInput = document.getElementById("userId");
  const userNameInput = document.getElementById("userName");
  const userEmailInput = document.getElementById("userEmail");
  const userRoleInput = document.getElementById("userRole");
  const userPasswordInput = document.getElementById("userPassword");

  const confirmDeleteUserModal = new bootstrap.Modal(document.getElementById("confirmDeleteUserModal"));
  const btnConfirmDeleteUser = document.querySelector(".btn-confirm-delete-user");

  const modalFeedback = new bootstrap.Modal(document.getElementById("modalFeedback"));
  const modalTitle = document.getElementById("modal-title");
  const modalBody = document.getElementById("modal-body");

  const userDetailModal = new bootstrap.Modal(document.getElementById("userDetailModal"));
  const detailName = document.getElementById("detailName");
  const detailEmail = document.getElementById("detailEmail");
  const detailRole = document.getElementById("detailRole");
  const detailPlan = document.getElementById("detailPlan");
  const detailPlanEnd = document.getElementById("detailPlanEnd");
  const detailPayments = document.getElementById("detailPayments");
  const detailCuotas = document.getElementById("detailCuotas");

  let editUserId = null;
  let userIdToDelete = null;

  // ===== Renderizado =====
  function renderUsers(filter = "") {
    const q = filter.trim().toLowerCase();
    usersTbody.innerHTML = "";
    const filtered = users.filter(u => !q || u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q));

    if (!filtered.length) {
      usersTbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-4">No se encontraron usuarios.</td></tr>`;
      return;
    }

    filtered.forEach(u => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${escapeHtml(u.name)}</td>
        <td>${escapeHtml(u.email)}</td>
        <td>${escapeHtml(capitalize(u.role))}</td>
        <td class="text-end">
          <div class="d-inline-flex gap-2">
            <button class="btn btn-sm btn-info btn-detail-user" data-id="${u.id}"><i class="fas fa-eye"></i></button>
            <button class="btn btn-sm btn-warning btn-edit-user" data-id="${u.id}"><i class="fas fa-edit"></i></button>
            <button class="btn btn-sm btn-danger btn-delete-user" data-id="${u.id}"><i class="fas fa-trash"></i></button>
          </div>
        </td>
      `;
      tr.querySelector(".btn-detail-user").addEventListener("click", () => openDetailUserModal(u.id));
      tr.querySelector(".btn-edit-user").addEventListener("click", () => openEditUserModal(u.id));
      tr.querySelector(".btn-delete-user").addEventListener("click", () => openDeleteUserModal(u.id));
      usersTbody.appendChild(tr);
    });
  }

  function escapeHtml(str = "") {
    return String(str).replace(/[&<>"]/g, t => ({ "&":"&amp;", "<":"&lt;", ">":"&gt;", "\"":"&quot;" }[t]));
  }
  function capitalize(str = "") { return str.charAt(0).toUpperCase() + str.slice(1); }

  // ===== Crear / Editar Usuario =====
  btnCreateUser.addEventListener("click", () => {
    editUserId = null;
    userModalTitle.textContent = "Crear Usuario";
    userForm.reset();
    resetValidation();
    userPasswordInput.parentElement.style.display = "block";
    userModal.show();
  });

  function openEditUserModal(id) {
    editUserId = id;
    const user = users.find(u => u.id === id);
    if (!user) return showFeedback("Error", "Usuario no encontrado.");
    userModalTitle.textContent = "Editar Usuario";
    userIdInput.value = user.id;
    userNameInput.value = user.name;
    userEmailInput.value = user.email;
    userRoleInput.value = user.role;
    userPasswordInput.value = "";
    resetValidation();
    userModal.show();
  }

  function resetValidation() {
    [userNameInput, userEmailInput, userRoleInput, userPasswordInput].forEach(i => i.classList.remove("is-invalid","is-valid"));
  }

  function validateUserForm(isEditing = false) {
    let valid = true;

    if (userNameInput.value.trim().length < 3) { userNameInput.classList.add("is-invalid"); valid=false; } 
    else { userNameInput.classList.remove("is-invalid"); userNameInput.classList.add("is-valid"); }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(userEmailInput.value.trim())) { userEmailInput.classList.add("is-invalid"); valid=false; } 
    else { userEmailInput.classList.remove("is-invalid"); userEmailInput.classList.add("is-valid"); }

    if (!userRoleInput.value) { userRoleInput.classList.add("is-invalid"); valid=false; } 
    else { userRoleInput.classList.remove("is-invalid"); userRoleInput.classList.add("is-valid"); }

    if (!isEditing && userPasswordInput.value.trim().length < 6) { userPasswordInput.classList.add("is-invalid"); valid=false; } 
    else { userPasswordInput.classList.remove("is-invalid"); }

    return valid;
  }

  userForm.addEventListener("submit", e => {
    e.preventDefault();
    const isEditing = !!editUserId;
    if (!validateUserForm(isEditing)) return;

    const id = isEditing ? editUserId : Date.now();
    const existing = users.find(u => u.id === id);

    const newData = {
      id,
      name: userNameInput.value.trim(),
      email: userEmailInput.value.trim(),
      role: userRoleInput.value,
      password: userPasswordInput.value.trim() || (existing ? existing.password : "")
    };

    if (existing) users = users.map(u => u.id === id ? newData : u);
    else users.unshift(newData);

    showFeedback("Éxito", existing ? "Usuario actualizado correctamente." : "Usuario creado correctamente.");
    renderUsers(searchUserInput.value);
    userModal.hide();
  });

  // ===== Eliminar =====
  function openDeleteUserModal(id) { userIdToDelete = id; confirmDeleteUserModal.show(); }
  btnConfirmDeleteUser.addEventListener("click", () => {
    users = users.filter(u => u.id !== userIdToDelete);
    renderUsers(searchUserInput.value);
    confirmDeleteUserModal.hide();
    showFeedback("Éxito", "Usuario eliminado correctamente.");
  });

  // ===== Buscar =====
  searchUserInput.addEventListener("input", e => renderUsers(e.target.value));

  // ===== Detalle usuario =====
  function openDetailUserModal(id) {
    const user = users.find(u => u.id === id);
    if (!user) return showFeedback("Error","Usuario no encontrado.");
    detailName.textContent = user.name;
    detailEmail.textContent = user.email;
    detailRole.textContent = capitalize(user.role);

    const extra = userExtraData[id] || { plan:{}, payments:[], cuotas:[] };
    detailPlan.textContent = extra.plan.name || "-";
    detailPlanEnd.textContent = extra.plan.end || "-";

    detailPayments.innerHTML = "";
    extra.payments.forEach(p => {
      const li = document.createElement("li");
      li.className = "list-group-item";
      li.textContent = `${p.date} — ${p.amount}`;
      detailPayments.appendChild(li);
    });

    detailCuotas.innerHTML = "";
    extra.cuotas.forEach(c => {
      const li = document.createElement("li");
      li.className = "list-group-item";
      li.textContent = `${c.month}: ${c.status}`;
      detailCuotas.appendChild(li);
    });

    userDetailModal.show();
  }

  // ===== Feedback =====
  function showFeedback(title,msg){ modalTitle.textContent=title; modalBody.textContent=msg; modalFeedback.show(); }

  // ===== Inicial =====
  renderUsers();
});
