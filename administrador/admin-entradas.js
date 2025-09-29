// admin-entradas.js
document.addEventListener("DOMContentLoaded", function () {
  // Elementos
  const entriesTbody = document.getElementById("entriesTbody");
  const searchInput = document.getElementById("searchInput");
  const btnCreate = document.querySelector(".btn-create-entry");

  // Modal & form elements
  const entryModalEl = document.getElementById("entryModal");
  const entryModal = new bootstrap.Modal(entryModalEl);
  const entryForm = document.getElementById("entryForm");
  const entryModalTitle = document.getElementById("entryModalTitle");
  const entryIdInput = document.getElementById("entryId");
  const titleInput = document.getElementById("entryTitle");
  const authorInput = document.getElementById("entryAuthor");
  const dateInput = document.getElementById("entryDate");
  const excerptInput = document.getElementById("entryExcerpt");
  const contentInput = document.getElementById("entryContent");

  const confirmDeleteModalEl = document.getElementById("confirmDeleteModal");
  const confirmDeleteModal = new bootstrap.Modal(confirmDeleteModalEl);
  const btnConfirmDelete = document.querySelector(".btn-confirm-delete");

  // Keys
  const STORAGE_KEY = "kynetik_entries_v1";

  // Datos iniciales (si no hay nada en localStorage)
  function seedIfEmpty() {
    if (!localStorage.getItem(STORAGE_KEY)) {
      const sample = [
        {
          id: Date.now() + 1,
          title: "Nuevas Clases de HIIT",
          author: "Admin",
          date: new Date().toISOString().slice(0,10),
          excerpt: "Arrancamos con clases intensivas de HIIT para todos los niveles.",
          content: "Contenido de ejemplo: descripción de la clase, horarios y beneficios.",
          published: true
        },
        {
          id: Date.now() + 2,
          title: "Renovación de Máquinas",
          author: "Gerencia",
          date: new Date().toISOString().slice(0,10),
          excerpt: "Se renuevan equipos de fuerza esta semana.",
          content: "Detalles sobre mantenimiento y horarios alternativos.",
          published: false
        }
      ];
      localStorage.setItem(STORAGE_KEY, JSON.stringify(sample));
    }
  }

  function getEntries() {
    return JSON.parse(localStorage.getItem(STORAGE_KEY) || "[]");
  }

  function saveEntries(entries) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(entries));
  }

  // RENDER
  function renderEntries(filter = "") {
    const q = filter.trim().toLowerCase();
    const entries = getEntries().filter(e => {
      if (!q) return true;
      return (e.title && e.title.toLowerCase().includes(q)) ||
             (e.author && e.author.toLowerCase().includes(q));
    });

    entriesTbody.innerHTML = "";
    if (entries.length === 0) {
      entriesTbody.innerHTML = `
        <tr>
          <td colspan="5" class="text-center text-muted py-4">No se encontraron entradas.</td>
        </tr>`;
      return;
    }

    entries.forEach(entry => {
      const tr = document.createElement("tr");
      tr.dataset.id = entry.id;
      tr.innerHTML = `
        <td>
          <strong>${escapeHtml(entry.title)}</strong>
          <div class="text-muted small">${escapeHtml(entry.excerpt || "")}</div>
        </td>
        <td>${escapeHtml(entry.author)}</td>
        <td>${escapeHtml(entry.date)}</td>
        <td>
          ${entry.published ? '<span class="badge bg-success">Publicado</span>' : '<span class="badge bg-secondary">Borrador</span>'}
        </td>
        <td class="text-end">
          <div class="d-inline-flex gap-2">
            <button class="btn btn-sm btn-outline-primary btn-edit-entry" data-id="${entry.id}" title="Editar"><i class="fas fa-edit"></i></button>
            <button class="btn btn-sm btn-outline-warning btn-toggle-publish" data-id="${entry.id}" title="${entry.published ? 'Despublicar' : 'Publicar'}">
              <i class="fas ${entry.published ? 'fa-eye-slash' : 'fa-eye'}"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger btn-delete-entry" data-id="${entry.id}" title="Eliminar"><i class="fas fa-trash"></i></button>
          </div>
        </td>
      `;
      entriesTbody.appendChild(tr);
    });
  }

  // Util: escape HTML (previene inyección simple)
  function escapeHtml(str) {
    if (!str) return "";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;");
  }

  // BÚSQUEDA
  searchInput.addEventListener("input", function (e) {
    renderEntries(e.target.value);
  });

  // CREAR: abrir modal vacío
  btnCreate.addEventListener("click", function () {
    entryModalTitle.textContent = "Crear Entrada";
    entryIdInput.value = "";
    titleInput.value = "";
    authorInput.value = "";
    dateInput.value = new Date().toISOString().slice(0,10);
    excerptInput.value = "";
    contentInput.value = "";
    clearValidation(entryForm);
    entryModal.show();
  });

  // GUARDAR (crear / editar)
  entryForm.addEventListener("submit", function (e) {
    e.preventDefault();
    clearValidation(entryForm);

    // validaciones
    let valido = true;
    if (!titleInput.value.trim() || titleInput.value.trim().length < 3) {
      markInvalid(titleInput);
      valido = false;
    } else markValid(titleInput);

    if (!authorInput.value.trim() || authorInput.value.trim().length < 3) {
      markInvalid(authorInput);
      valido = false;
    } else markValid(authorInput);

    if (!dateInput.value) {
      markInvalid(dateInput);
      valido = false;
    } else markValid(dateInput);

    if (!contentInput.value.trim() || contentInput.value.trim().length < 10) {
      markInvalid(contentInput);
      valido = false;
    } else markValid(contentInput);

    if (!valido) {
      // mostrar modal de error usando la función global (de main.js)
      if (typeof mostrarModal === "function") {
        mostrarModal("Error", "Por favor corrija los campos del formulario.");
      }
      return;
    }

    const entries = getEntries();
    const id = entryIdInput.value ? Number(entryIdInput.value) : Date.now();
    const item = {
      id,
      title: titleInput.value.trim(),
      author: authorInput.value.trim(),
      date: dateInput.value,
      excerpt: excerptInput.value.trim(),
      content: contentInput.value.trim(),
      published: entries.find(e => e.id === id)?.published || false
    };

    if (entryIdInput.value) {
      // editar
      const idx = entries.findIndex(e => e.id === id);
      if (idx !== -1) entries[idx] = item;
      saveEntries(entries);
      entryModal.hide();
      if (typeof mostrarModal === "function") mostrarModal("Éxito", "Entrada actualizada correctamente.");
    } else {
      // crear (por defecto no publicada)
      entries.unshift(item);
      saveEntries(entries);
      entryModal.hide();
      if (typeof mostrarModal === "function") mostrarModal("Éxito", "Entrada creada correctamente.");
    }

    renderEntries(searchInput.value);
  });

  // EVENT DELEGATION para EDIT, PUBLISH, DELETE
  entriesTbody.addEventListener("click", function (e) {
    const btn = e.target.closest("button");
    if (!btn) return;
    const id = Number(btn.dataset.id);
    if (btn.classList.contains("btn-edit-entry")) return handleEdit(id);
    if (btn.classList.contains("btn-toggle-publish")) return togglePublish(id);
    if (btn.classList.contains("btn-delete-entry")) return handleDelete(id);
  });

  function handleEdit(id) {
    const entries = getEntries();
    const entry = entries.find(e => e.id === id);
    if (!entry) {
      if (typeof mostrarModal === "function") mostrarModal("Error", "No se encontró la entrada.");
      return;
    }
    entryModalTitle.textContent = "Editar Entrada";
    entryIdInput.value = entry.id;
    titleInput.value = entry.title;
    authorInput.value = entry.author;
    dateInput.value = entry.date;
    excerptInput.value = entry.excerpt || "";
    contentInput.value = entry.content;
    clearValidation(entryForm);
    entryModal.show();
  }

  function togglePublish(id) {
    const entries = getEntries();
    const idx = entries.findIndex(e => e.id === id);
    if (idx === -1) {
      if (typeof mostrarModal === "function") mostrarModal("Error", "No se encontró la entrada.");
      return;
    }
    entries[idx].published = !entries[idx].published;
    saveEntries(entries);
    renderEntries(searchInput.value);
    if (typeof mostrarModal === "function") {
      mostrarModal("Éxito", entries[idx].published ? "Entrada publicada." : "Entrada despublicada.");
    }
  }

  // BORRAR - abrimos modal de confirmación, guardamos id en el botón confirm
  let pendingDeleteId = null;
  function handleDelete(id) {
    pendingDeleteId = id;
    btnConfirmDelete.dataset.id = id;
    confirmDeleteModal.show();
  }

  btnConfirmDelete.addEventListener("click", function () {
    const id = Number(btnConfirmDelete.dataset.id);
    let entries = getEntries();
    entries = entries.filter(e => e.id !== id);
    saveEntries(entries);
    confirmDeleteModal.hide();
    renderEntries(searchInput.value);
    if (typeof mostrarModal === "function") mostrarModal("Éxito", "Entrada eliminada.");
  });

  // VALIDATION helpers
  function markInvalid(el) {
    el.classList.remove("is-valid");
    el.classList.add("is-invalid");
  }
  function markValid(el) {
    el.classList.remove("is-invalid");
    el.classList.add("is-valid");
  }
  function clearValidation(form) {
    form.querySelectorAll(".is-invalid, .is-valid").forEach(el => {
      el.classList.remove("is-invalid");
      el.classList.remove("is-valid");
    });
  }

  // Seed + inicial render
  seedIfEmpty();
  renderEntries();

});
