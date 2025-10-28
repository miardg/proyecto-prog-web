<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once("./include-socio/head.php"); ?>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-dumbbell text-warning me-2"></i>KynetikGym
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="#miInformacion">Mi Información</a></li>
                    <li class="nav-item"><a class="nav-link" href="#saludYProgreso">Salud y Progreso</a></li>
                </ul>
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-outline-warning" id="btnLogout">Cerrar Sesión</button>
            </div>
        </div>
    </nav>


    <section id="panel-socio" class="container py-5 bg-light text-dark">
        <h2 class="text-center mb-5">Panel del Socio</h2>

        <h3 id="miInformacion" class="text-warning mb-4">Mi Información</h3>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-socio h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-id-card display-5 text-warning mb-3"></i>
                        <h5 class="card-title">Mi Plan</h5>
                        <p class="card-text">Visualiza detalles y estado de tu plan actual</p>
                        <button id="btnPlan" class="btn btn-outline-warning w-100">Ver Plan</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-socio h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-calendar-check display-5 text-warning mb-3"></i>
                        <h5 class="card-title">Mis Clases</h5>
                        <p class="card-text">Consultá tus clases y horarios disponibles.</p>
                        <button id="btnClases" class="btn btn-outline-warning w-100">Ver Clases</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-socio h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-gift display-5 text-warning mb-3"></i>
                        <h5 class="card-title">Beneficios</h5>
                        <p class="card-text">Accedé a descuentos y servicios exclusivos.</p>
                        <button id="btnBeneficios" class="btn btn-outline-warning w-100">Ver Beneficios</button>
                    </div>
                </div>
            </div>
        </div>

        <h3 id="saludYProgreso" class="text-warning mt-5 mb-4">Mi Salud y Progreso</h3>
        <div id="saludProgreso" class="row g-4">
            <div class="col-md-4">
                <div class="card card-socio h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-dumbbell display-5 text-warning mb-3"></i>
                        <h5 class="card-title">Mis Rutinas</h5>
                        <p class="card-text">Checkea tu plan de rutinas personalizadas</p>
                        <button id="btnRutinas" class="btn btn-outline-warning w-100">Ver Detalles</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-socio h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-apple-alt display-5 text-warning mb-3"></i>
                        <h5 class="card-title">Alimentación</h5>
                        <p class="card-text">Descarga tu plan nutricional</p>
                        <button id="btnAlimentacion" class="btn btn-outline-warning w-100">Descargar PDF</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-socio h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-user-tie display-5 text-warning mb-3"></i>
                        <h5 class="card-title">Personal Trainer</h5>
                        <p class="card-text">Verifica tus sesiones con el personal trainer</p>
                        <button id="btnPersonalTrainer" class="btn btn-outline-warning w-100">Ver Detalles</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal: Descargando PDF -->
    <div class="modal fade" id="modalDescargandoPDF" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-white text-dark text-center">
                <div class="modal-body py-4">
                    <h5 class="mb-3">Descargando PDF...</h5>
                    <i class="fas fa-spinner fa-spin fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer id="contacto" class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-dumbbell text-warning me-2"></i>KynetikGym</h5>
                    <p>Tu gimnasio de confianza para alcanzar todos tus objetivos fitness con el mejor equipamiento y
                        profesionales.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Contacto</h5>
                    <p><i class="fas fa-map-marker-alt text-warning me-2"></i>Av. Principal 123, Ciudad</p>
                    <p><i class="fas fa-phone text-warning me-2"></i>+54 11 1234-5678</p>
                    <p><i class="fas fa-envelope text-warning me-2"></i>info@KynetikGym.com</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Horarios</h5>
                    <p>Lunes a Viernes: 6:00 - 23:00</p>
                    <p>Sábados: 8:00 - 20:00</p>
                    <p>Domingos: 9:00 - 18:00</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0">&copy; 2025 KynetikGym. Todos los derechos reservados.</p>
                <div>
                    <a href="#" class="text-warning me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-warning me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-warning"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>