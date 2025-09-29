
document.addEventListener("DOMContentLoaded", () => {
  const plansTbody = document.getElementById("plansTbody");
  const searchPlanInput = document.getElementById("searchPlanInput");
  const planModal = new bootstrap.Modal(document.getElementById("planModal"));
  const planForm = document.getElementById("planForm");
  const planModalTitle = document.getElementById("planModalTitle");
  const planIdInput = document.getElementById("planId");
  const planNameInput = document.getElementById("planName");
  const planPriceInput = document.getElementById("planPrice");
  const planDurationInput = document.getElementById("planDuration");
  const confirmDeletePlanModal = new bootstrap.Modal(document.getElementById("confirmDeletePlanModal"));
  const btnConfirmDeletePlan = document.querySelector(".btn-confirm-delete-plan");

  let plans = [
    { id: 1, name: "Plan Básico", price: 50, duration: 1 },
    { id: 2, name: "Plan Premium", price: 120, duration: 3 },
  ];

  let planToDeleteId = null;

  // Función para renderizar la tabla
  function renderPlans(filter = "") {
    plansTbody.innerHTML = "";
    const filteredPlans = plans.filter(plan =>
      plan.name.toLowerCase().includes(filter.toLowerCase())
    );

    filteredPlans.forEach(plan => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${plan.name}</td>
        <td>$${plan.price}</td>
        <td>${plan.duration}</td>
        <td class="text-end">
          <button class="btn btn-sm btn-warning btn-edit-plan me-2" data-id="${plan.id}">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn btn-sm btn-danger btn-delete-plan" data-id="${plan.id}">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      `;
      plansTbody.appendChild(tr);
    });

    // Agregar eventos a los botones después de renderizar
    document.querySelectorAll(".btn-edit-plan").forEach(btn => {
      btn.addEventListener("click", () => openEditPlanModal(btn.dataset.id));
    });

    document.querySelectorAll(".btn-delete-plan").forEach(btn => {
      btn.addEventListener("click", () => openDeletePlanModal(btn.dataset.id));
    });
  }

  // Abrir modal para crear plan
  document.querySelector(".btn-create-plan").addEventListener("click", () => {
    planModalTitle.textContent = "Crear Plan";
    planIdInput.value = "";
    planNameInput.value = "";
    planPriceInput.value = "";
    planDurationInput.value = "";
    planModal.show();
  });

  // Abrir modal para editar plan
  function openEditPlanModal(id) {
    const plan = plans.find(p => p.id == id);
    if (!plan) return;

    planModalTitle.textContent = "Editar Plan";
    planIdInput.value = plan.id;
    planNameInput.value = plan.name;
    planPriceInput.value = plan.price;
    planDurationInput.value = plan.duration;
    planModal.show();
  }

  // Guardar plan (crear o editar)
  planForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const id = planIdInput.value;
  const name = planNameInput.value.trim();
  const price = parseFloat(planPriceInput.value);
  const duration = parseInt(planDurationInput.value);

  // Resetear clases de validación
  planNameInput.classList.remove("is-invalid");
  planPriceInput.classList.remove("is-invalid");
  planDurationInput.classList.remove("is-invalid");

  let valid = true;

  if (name.length < 3) {
    planNameInput.classList.add("is-invalid");
    valid = false;
  }
  if (isNaN(price) || price <= 0) {
    planPriceInput.classList.add("is-invalid");
    valid = false;
  }
  if (isNaN(duration) || duration <= 0) {
    planDurationInput.classList.add("is-invalid");
    valid = false;
  }

  if (!valid) return; // si hay errores, no continuar

  if (id) {
    // Editar plan
    const plan = plans.find(p => p.id == id);
    plan.name = name;
    plan.price = price;
    plan.duration = duration;
    showModalFeedback("Éxito", "Plan modificado correctamente.");
  } else {
    // Crear nuevo plan
    const newPlan = {
      id: plans.length ? plans[plans.length - 1].id + 1 : 1,
      name,
      price,
      duration
    };
    plans.push(newPlan);
    showModalFeedback("Éxito", "Plan creado correctamente.");
  }

  planModal.hide();
  renderPlans(searchPlanInput.value);
});


  // Abrir modal de confirmación de eliminación
  function openDeletePlanModal(id) {
    planToDeleteId = id;
    confirmDeletePlanModal.show();
  }

  // Confirmar eliminación
  btnConfirmDeletePlan.addEventListener("click", () => {
    plans = plans.filter(p => p.id != planToDeleteId);
    renderPlans(searchPlanInput.value);
    confirmDeletePlanModal.hide();
    showModalFeedback("Éxito", "Plan eliminado correctamente.");
  });

  // Buscar planes
  searchPlanInput.addEventListener("input", () => {
    renderPlans(searchPlanInput.value);
  });

  // Función para mostrar feedback
  function showModalFeedback(title, message) {
    const modal = new bootstrap.Modal(document.getElementById("modalFeedback"));
    document.getElementById("modal-title").textContent = title;
    document.getElementById("modal-body").textContent = message;
    modal.show();
  }

  // Render inicial
  renderPlans();
});
