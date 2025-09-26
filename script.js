window.onload = function () {
    let btnClase = document.getElementById("btnClases");
    let btnPlan = document.getElementById("btnPlan");
    let btnBeneficios = document.getElementById("btnBeneficios");
    let btnRutinas = document.getElementById("btnRutinas");
    let btnAlimentacion = document.getElementById("btnAlimentacion");
    let btnPersonalTrainer = document.getElementById("btnPersonalTrainer");

    btnBeneficios.addEventListener('click', () => {
        window.location.href = 'beneficiosSocio.html';
    });
    btnClase.addEventListener('click', () => {
        window.location.href = 'clasesSocio.html';
    });
    btnPlan.addEventListener('click', () => {
        window.location.href = 'planSocio.html';
    });
    btnRutinas.addEventListener('click', () => {
        window.location.href = 'rutinaSocio.html';
    });
    btnAlimentacion.addEventListener('click', () => {
        window.location.href = 'alimentacionSocio.html';
    });
    btnPersonalTrainer.addEventListener('click', () => {
        window.location.href = 'personalTrainerSocio.html';
    });
}
