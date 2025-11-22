document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const newPass = document.getElementById("newPassword");
    const confirmPass = document.getElementById("confirmPassword");

    form.addEventListener("submit", function (event) {
        let valid = true;

        // Validación longitud
        if (newPass.value.length < 6) {
            newPass.classList.add("is-invalid");
            valid = false;
        } else {
            newPass.classList.remove("is-invalid");
            newPass.classList.add("is-valid");
        }

        // Validación coincidencia
        if (confirmPass.value !== newPass.value) {
            confirmPass.classList.add("is-invalid");
            valid = false;
        } else {
            confirmPass.classList.remove("is-invalid");
            confirmPass.classList.add("is-valid");
        }

        // Si hay errores, no se envía al servidor
        if (!valid) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
});
