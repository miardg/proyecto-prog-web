document.addEventListener("DOMContentLoaded", () => {
 
	// Chart altas y bajas


	const ctx3 = document.getElementById('subsChart').getContext('2d');

	new Chart(ctx3, {
	  type: 'bar',
	  data: {
	    labels: ['Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre'],
	    datasets: [
	      {
	        label: 'Altas',
	        data: [15, 22, 18, 25, 20, 28],
	        backgroundColor: 'rgba(54, 162, 235, 0.7)',
	        borderColor: 'rgba(54, 162, 235, 1)',
	        borderWidth: 1
	      },
	      {
	        label: 'Bajas',
	        data: [5, 10, 8, 12, 9, 14],
	        backgroundColor: 'rgba(255, 99, 132, 0.7)',
	        borderColor: 'rgba(255, 99, 132, 1)',
	        borderWidth: 1
	      }
	    ]
	  },
	  options: {
	    responsive: true,
	    plugins: {
	      title: {
	        display: true,
	        text: 'Altas y Bajas de Suscripción (6 meses)',
	        font: { size: 18 }
	      }
	    },
	    scales: {
	      y: {
	        beginAtZero: true,
	        title: {
	          display: true,
	          text: 'Cantidad de socios'
	        }
	      },
	      x: {
	        title: {
	          display: true,
	          text: 'Meses'
	        }
	      }
	    }
	  }
	});

	  
  
// Chart saldos a cobrar

const ctx2 = document.getElementById('saldosChart').getContext('2d');
 new Chart(ctx2, {
   type: 'pie',
   data: {
     labels: ['Pagos realizados', 'Deudas pendientes'],
     datasets: [{
       data: [120, 15],
       backgroundColor: ['rgba(75, 192, 75, 0.7)', '#FF6384']
     }]
   }
 });
 

})