document.addEventListener("DOMContentLoaded", () => {
  const benefitsTbody = document.getElementById("benefitsTbody");
  const searchInput = document.getElementById("searchBenefitInput");

  const benefitModalEl = document.getElementById("benefitModal");
  const benefitModal = new bootstrap.Modal(benefitModalEl);
  const modalTitle = document.getElementById("benefitModalTitle");
  const benefitForm = document.getElementById("benefitForm");

  const feedbackModal = new bootstrap.Modal(document.getElementById("modalFeedback"));
  const feedbackTitle = document.getElementById("modal-title");
  const feedbackBody = document.getElementById("modal-body");

  let benefits = [];
  let editingBenefitId = null;

  // Función para renderizar tabla
  function renderTable(data) {
    benefitsTbody.innerHTML = "";
    data.forEach(benefit => {
      const tr = document.createElement("tr");
      tr.classList.add("table-row", "align-middle", "fade");
      tr.innerHTML = `
        <td>${benefit.name}</td>
        <td>${benefit.description}</td>
        <td class="text-center">
          <input type="checkbox" class="form-check-input toggle-published" data-id="${benefit.id}" ${benefit.published ? "checked" : ""}>
        </td>
        <td class="text-end">
          <button class="btn btn-sm btn-warning btn-edit-benefit me-1" data-id="${benefit.id}">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn btn-sm btn-danger btn-delete-benefit" data-id="${benefit.id}">
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      `;
      benefitsTbody.appendChild(tr);
      setTimeout(() => tr.classList.add("show"), 50);
    });
  }

  // Feedback
  function showFeedback(title, message) {
    feedbackTitle.textContent = title;
    feedbackBody.textContent = message;
    feedbackModal.show();
  }

  // Crear nuevo beneficio
  document.querySelector(".btn-create-benefit").addEventListener("click", () => {
    modalTitle.textContent = "Crear Beneficio";
    benefitForm.reset();
    editingBenefitId = null;
    benefitForm.querySelectorAll(".form-control").forEach(input => input.classList.remove("is-invalid"));
    document.getElementById("benefitPublished").checked = true;
    benefitModal.show();
  });

  // Guardar o editar beneficio
  benefitForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const nameInput = document.getElementById("benefitName");
    const descInput = document.getElementById("benefitDescription");
    const publishedInput = document.getElementById("benefitPublished");

    let valid = true;
    if (nameInput.value.trim() === "") {
      nameInput.classList.add("is-invalid");
      valid = false;
    } else {
      nameInput.classList.remove("is-invalid");
    }

    if (descInput.value.trim() === "") {
      descInput.classList.add("is-invalid");
      valid = false;
    } else {
      descInput.classList.remove("is-invalid");
    }

    if (!valid) return;

    if (editingBenefitId === null) {
      const newBenefit = {
        id: Date.now(),
        name: nameInput.value.trim(),
        description: descInput.value.trim(),
        published: publishedInput.checked
      };
      benefits.push(newBenefit);
      showFeedback("Éxito", "Beneficio creado correctamente.");
    } else {
      const benefit = benefits.find(b => b.id === editingBenefitId);
      if (benefit) {
        benefit.name = nameInput.value.trim();
        benefit.description = descInput.value.trim();
        benefit.published = publishedInput.checked;
        showFeedback("Éxito", "Beneficio modificado correctamente.");
      }
    }

    benefitModal.hide();
    renderTable(benefits);
  });

  // Editar beneficio
  benefitsTbody.addEventListener("click", (e) => {
    if (e.target.closest(".btn-edit-benefit")) {
      const id = parseInt(e.target.closest(".btn-edit-benefit").dataset.id);
      const benefit = benefits.find(b => b.id === id);
      if (benefit) {
        editingBenefitId = id;
        modalTitle.textContent = "Editar Beneficio";
        document.getElementById("benefitName").value = benefit.name;
        document.getElementById("benefitDescription").value = benefit.description;
        document.getElementById("benefitPublished").checked = benefit.published;
        benefitForm.querySelectorAll(".form-control").forEach(input => input.classList.remove("is-invalid"));
        benefitModal.show();
      }
    }
  });

  // Toggle publicado directo en tabla
  benefitsTbody.addEventListener("change", (e) => {
    if (e.target.classList.contains("toggle-published")) {
      const id = parseInt(e.target.dataset.id);
      const benefit = benefits.find(b => b.id === id);
      if (benefit) {
        benefit.published = e.target.checked;
        showFeedback("Éxito", `El beneficio "${benefit.name}" ahora está ${benefit.published ? "publicado" : "no publicado"}.`);
      }
    }
  });

  // Eliminar beneficio
  benefitsTbody.addEventListener("click", (e) => {
    if (e.target.closest(".btn-delete-benefit")) {
      const id = parseInt(e.target.closest(".btn-delete-benefit").dataset.id);
      const tr = e.target.closest("tr");
      tr.classList.remove("show");
      setTimeout(() => {
        benefits = benefits.filter(b => b.id !== id);
        renderTable(benefits);
        showFeedback("Éxito", "Beneficio eliminado correctamente.");
      }, 300);
    }
  });

  // Buscar beneficio
  searchInput.addEventListener("input", () => {
    const query = searchInput.value.toLowerCase();
    renderTable(benefits.filter(b => b.name.toLowerCase().includes(query)));
  });

  // Render inicial
  renderTable(benefits);
});
