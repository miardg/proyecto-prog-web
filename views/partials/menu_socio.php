<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">
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
    </div>
</nav>

<section id="panel-socio" class="container py-5 bg-dark text-white">
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
                    <p class="card-text">Consultá tu plan nutricional asignado</p>
                    <button id="btnAlimentacion" class="btn btn-outline-warning w-100">Ver Detalles</button>
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

<!-- Modal de Feedback -->
<div class="modal fade" id="modalFeedback" tabindex="-1" aria-labelledby="modalFeedbackLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-0">
                <h5 class="modal-title text-warning" id="modalFeedbackLabel">Confirmación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalFeedbackBody">
                <!-- JS insertará el mensaje acá -->
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Plan Socio -->
<div class="modal fade" id="modalPlanSocio" tabindex="-1" aria-labelledby="modalPlanSocioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-0">
                <h5 class="modal-title text-warning" id="modalPlanSocioLabel">Mi Plan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalPlanSocioBody">
                <!-- Aquí el JS insertará los datos del plan -->
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Clases Disponibles -->
<div class="modal fade" id="modalClasesSocio" tabindex="-1" aria-labelledby="modalClasesSocioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-0">
                <h5 class="modal-title text-warning" id="modalClasesSocioLabel">Clases Disponibles</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalClasesSocioBody">
                <!-- Aquí el JS insertará las tarjetas de clases -->
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Beneficios -->
<div class="modal fade" id="modalBeneficiosSocio" tabindex="-1" aria-labelledby="modalBeneficiosSocioLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-0">
                <h5 class="modal-title text-warning" id="modalBeneficiosSocioLabel">Beneficios Disponibles</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalBeneficiosSocioBody">
                <!-- JS insertará los beneficios acá -->
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>