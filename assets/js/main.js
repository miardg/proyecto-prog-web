window.onload = function () {
    // Usuarios de prueba para la navegacion del front
    const usuariosPrueba = [
        { email: "profesional@gmail.com", password: "123456", rol: "profesionales" },
        { email: "profesor@gmail.com", password: "123456", rol: "profesor" },
        { email: "socio@gmail.com", password: "123456", rol: "socio" },
        { email: "administrador@gmail.com", password: "123456", rol: "administrador" },
        { email: "administrativo@gmail.com", password: "123456", rol: "administrativo" }
    ];

    // ---------------------- NAVBAR ----------------------
    let navbar = document.querySelector(".navbar");
    let navbarCollapse = document.querySelector(".navbar-collapse");
    let navLinks = document.querySelectorAll(".nav-link");

    // Cambia el estilo al hacer scroll
    window.addEventListener("scroll", function () {
        if (window.scrollY > 50) {
            navbar.classList.add("navbar-scrolled");
        } else {
            navbar.classList.remove("navbar-scrolled");
        }
    });

    // Cierra el menú al hacer clic en un enlace
    navLinks.forEach(link => {
        link.addEventListener("click", function () {
            if (navbarCollapse.classList.contains("show")) {
                let bsCollapse = new bootstrap.Collapse(navbarCollapse);
                bsCollapse.hide();
            }
        });
    });

    // ---------------------- MODAL (para feedback) ----------------------
    let modalTitle = document.getElementById("modal-title");
    let modalBody = document.getElementById("modal-body");
    let btnClose = document.getElementById("btnClose");

    function mostrarModal(titulo, mensaje) {
        if (modalTitle) modalTitle.textContent = titulo;
        if (modalBody) modalBody.textContent = mensaje;
        // Se espera que tengas un modal de Bootstrap configurado con id="modalFeedback"
        let modal = new bootstrap.Modal(document.getElementById("modalFeedback"));
        modal.show();
    }

    // ---------------------- LOGIN ----------------------
    let formLogin = document.getElementById("loginForm");
    if (formLogin) {
        let loginEmail = document.getElementById("email");
        let loginPassword = document.getElementById("password");

        formLogin.addEventListener("submit", function (e) {
            let valido = true;
            let formatoMail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Validar email
            if (!formatoMail.test(loginEmail.value)) {
                loginEmail.classList.add("is-invalid");
                valido = false;
            } else {
                loginEmail.classList.remove("is-invalid");
                loginEmail.classList.add("is-valid");
            }

            // Validar password
            if (loginPassword.value.trim() === "") {
                loginPassword.classList.add("is-invalid");
                valido = false;
            } else {
                loginPassword.classList.remove("is-invalid");
                loginPassword.classList.add("is-valid");
            }

            // Si no es valido, bloqueamos el envio y mostramos modal
            if (!valido) {
                e.preventDefault();
                mostrarModal("Error", "Revise los campos de inicio de sesión.");
            }
            // Si es valido, NO hacemos preventDefault osea el form se envia al backend
        });
    }


    // ---------------------- REGISTRO ----------------------
    let formRegistro = document.getElementById("registroForm");
    if (formRegistro) {
        let nombre = document.getElementById("nombre");
        let apellido = document.getElementById("apellido");
        let dni = document.getElementById("dni");
        let emailRegistro = document.getElementById("email");
        let telefono = document.getElementById("telefono");
        let pass1 = document.getElementById("password");
        let pass2 = document.getElementById("confirmPassword");
        let terminos = document.getElementById("terminos");

        formRegistro.addEventListener("submit", function (e) {
            let valido = true;
            let formatoMail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            let formatoTelefono = /^[0-9\-]+$/;

            // Validar nombre
            if (nombre.value.trim().length < 2) {
                nombre.classList.add("is-invalid");
                valido = false;
            } else {
                nombre.classList.remove("is-invalid");
                nombre.classList.add("is-valid");
            }

            // Validar apellido
            if (apellido.value.trim().length < 2) {
                apellido.classList.add("is-invalid");
                valido = false;
            } else {
                apellido.classList.remove("is-invalid");
                apellido.classList.add("is-valid");
            }

            // Validar DNI (7-8 dígitos numéricos)
            if (dni.value.length < 7 || dni.value.length > 8) {
                dni.classList.add("is-invalid");
                valido = false;
            } else {
                dni.classList.remove("is-invalid");
                dni.classList.add("is-valid");
            }

            // Validar email
            if (!formatoMail.test(emailRegistro.value)) {
                emailRegistro.classList.add("is-invalid");
                valido = false;
            } else {
                emailRegistro.classList.remove("is-invalid");
                emailRegistro.classList.add("is-valid");
            }

            // Validar teléfono
            if (!formatoTelefono.test(telefono.value)) {
                telefono.classList.add("is-invalid");
                valido = false;
            } else {
                telefono.classList.remove("is-invalid");
                telefono.classList.add("is-valid");
            }

            // Validar contraseñas
            if (pass1.value.length < 6 || pass1.value !== pass2.value) {
                pass1.classList.add("is-invalid");
                pass2.classList.add("is-invalid");
                valido = false;
            } else {
                pass1.classList.remove("is-invalid");
                pass2.classList.remove("is-invalid");
                pass1.classList.add("is-valid");
                pass2.classList.add("is-valid");
            }

            // Validar checkbox términos
            if (!terminos.checked) {
                terminos.classList.add("is-invalid");
                valido = false;
            } else {
                terminos.classList.remove("is-invalid");
                terminos.classList.add("is-valid");
            }

            if (!valido){
                e.preventDefault();
                mostrarModal("Error", "Complete correctamente el formulario de registro.");
            }
        });
    }


    // ---------------------- ANIMACIONES SIMPLES ----------------------
    let elementos = document.querySelectorAll(".stat-item, .feature-icon");
    elementos.forEach(function (el) {
        el.style.opacity = "0";
        el.style.transform = "translateY(20px)";
        el.style.transition = "opacity 0.6s ease, transform 0.6s ease";
    });

    window.addEventListener("scroll", function () {
        elementos.forEach(function (el) {
            let rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight - 100) {
                el.style.opacity = "1";
                el.style.transform = "translateY(0)";
            }
        });
    });

    // ---------------------- FOOTER (texto en blanco) ----------------------
    let footer = document.querySelector("footer") || document.getElementById("contacto");
    if (footer) {
        footer.querySelectorAll(".text-muted").forEach(function (el) {
            el.classList.remove("text-muted");
            el.classList.add("text-white");
        });
    }
};
