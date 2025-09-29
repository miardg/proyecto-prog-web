// ===== Simulación de base de datos =====
let plans = [
  {
    id: 1,
    name: "Básico",
    price: 5000,
    services: "Acceso libre a sala de máquinas; 4 clases particulares/mes",
    details: "No incluye nutricionista ni personal trainer; sin plan de alimentación ni rutinas"
  },
  {
    id: 2,
    name: "Standard",
    price: 8000,
    services: "Acceso libre a sala de máquinas; clases particulares ilimitadas",
    details: "1 consulta trimestral con nutricionista; rutinas genéricas disponibles; sin personal trainer"
  },
  {
    id: 3,
    name: "Premium",
    price: 12000,
    services: "Acceso libre a sala de máquinas; clases particulares ilimitadas",
    details: "1 consulta mensual con nutricionista; 2 sesiones mensuales con personal trainer; plan de alimentación y rutinas personalizadas"
  },
  {
    id: 4,
    name: "Elite",
    price: 18000,
    services: "Acceso libre a sala de máquinas; clases particulares ilimitadas",
    details: "2 consultas mensuales con nutricionista; 4 sesiones mensuales con personal trainer; plan de alimentación y rutinas totalmente personalizadas"
  }
];

let editPlanId = null;

// ===== Referencias DOM =====
const plansTbody = document.getElementById("plansTbody");
const planModal = new bootstrap.Modal(document.getElementById("planModal"));
const planForm = document.getElementById("planForm");
const planModalTitle = document.getElementById("planModalTitle");
const planIdInput = document.getElementById("planId");
const planNameInput = document.getElementById("planName");
const planPriceInput = document.getElementById("planPrice");
const planServicesInput = document.getElementById("planServices");
const planDetailsInput = document.getElementById("planDetails");

// ===== Renderizado =====
function renderPlans() {
  plansTbody.innerHTML = "";
  plans.forEach(plan => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${plan.name}</td>
      <td>$${plan.price.toLocaleString()}</td>
      <td>${plan.services}</td>
      <td>${plan.details}</td>
      <td class="text-end">
        <button class="btn btn-sm btn-warning btn-edit-plan"><i class="fas fa-edit"></i> Editar</button>
      </td>
    `;
    tr.querySelector(".btn-edit-plan").addEventListener("click", () => openEditPlanModal(plan));
    plansTbody.appendChild(tr);
  });
}

// ===== Editar Plan =====
function openEditPlanModal(plan) {
  editPlanId = plan.id;
  planModalTitle.textContent = `Editar Plan: ${plan.name}`;
  planIdInput.value = plan.id;
  planNameInput.value = plan.name;
  planPriceInput.value = plan.price;
  planServicesInput.value = plan.services;
  planDetailsInput.value = plan.details;
  planModal.show();
}

// ===== Validación simple =====
function validatePlanForm() {
  let valid = true;

  if (!planNameInput.value.trim()) {
    planNameInput.classList.add("is-invalid");
    valid = false;
  } else planNameInput.classList.remove("is-invalid");

  if (!planPriceInput.value || parseFloat(planPriceInput.value) <= 0) {
    planPriceInput.classList.add("is-invalid");
    valid = false;
  } else planPriceInput.classList.remove("is-invalid");

  if (!planServicesInput.value.trim()) {
    planServicesInput.classList.add("is-invalid");
    valid = false;
  } else planServicesInput.classList.remove("is-invalid");

  if (!planDetailsInput.value.trim()) {
    planDetailsInput.classList.add("is-invalid");
    valid = false;
  } else planDetailsInput.classList.remove("is-invalid");

  return valid;
}

// ===== Guardar cambios =====
planForm.addEventListener("submit", (e) => {
  e.preventDefault();
  if (!validatePlanForm()) return;

  const index = plans.findIndex(p => p.id === editPlanId);
  if (index !== -1) {
    plans[index].name = planNameInput.value.trim();
    plans[index].price = parseFloat(planPriceInput.value);
    plans[index].services = planServicesInput.value.trim();
    plans[index].details = planDetailsInput.value.trim();
  }

  renderPlans();
  planModal.hide();
});

// ===== Inicial =====
renderPlans();
