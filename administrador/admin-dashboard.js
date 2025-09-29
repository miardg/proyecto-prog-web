document.addEventListener("DOMContentLoaded", () => {
  // Datos simulados
  const totalUsers = 120;
  const totalPlans = 8;
  const totalClasses = 15;
  const totalBenefits = 5;

  document.getElementById("totalUsers").textContent = totalUsers;
  document.getElementById("totalPlans").textContent = totalPlans;
  document.getElementById("totalClasses").textContent = totalClasses;
  document.getElementById("totalBenefits").textContent = totalBenefits;

  // Chart ventas
  const salesCtx = document.getElementById('salesChart').getContext('2d');
  new Chart(salesCtx, {
    type: 'bar',
    data: {
      labels: ['Enero','Febrero','Marzo','Abril','Mayo','Junio'],
      datasets: [{
        label: 'Ventas ($)',
        data: [5000, 7000, 8000, 6000, 9000, 11000],
        backgroundColor: 'rgba(54, 162, 235, 0.7)',
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Chart usuarios por tipo
  const usersCtx = document.getElementById('usersChart').getContext('2d');
  new Chart(usersCtx, {
    type: 'doughnut',
    data: {
      labels: ['Administradores','Entrenadores','Socios'],
      datasets: [{
        data: [5, 10, 105],
        backgroundColor: [
          'rgba(255, 99, 132, 0.7)',
          'rgba(255, 206, 86, 0.7)',
          'rgba(75, 192, 192, 0.7)'
        ]
      }]
    },
    options: { responsive: true }
  });
});
