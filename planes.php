<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planes - KynetikGym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="assets/js/main.js" defer></script>
</head>

<body>

    <!-- Navigation -->
    <?php
    $currentPage = 'planes';
    include __DIR__ . '/includes/navbar.php';
    ?>

    <!-- Header -->
    <section class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold mb-3">Nuestros Planes</h1>
                    <p class="lead">Encuentra el plan perfecto para tus objetivos y presupuesto</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Plans Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Plan Básico -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-light text-center py-4">
                            <h4 class="fw-bold">Plan Básico</h4>
                            <div class="display-4 fw-bold text-warning">$15.000</div>
                            <small class="text-muted">por mes</small>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-unstyled mb-0">
                                <li><i class="fas fa-check text-success me-2"></i>Acceso completo al gimnasio</li>
                                <li><i class="fas fa-check text-success me-2"></i>Uso de todos los equipos</li>
                                <li><i class="fas fa-check text-success me-2"></i>Vestuarios y duchas</li>
                                <li><i class="fas fa-times text-muted me-2"></i>Clases grupales</li>
                                <li><i class="fas fa-times text-muted me-2"></i>Entrenamiento personal</li>
                                <li><i class="fas fa-times text-muted me-2"></i>Consulta nutricional</li>
                            </ul>
                        </div>
                        <div class="card-footer bg-transparent p-4">
                            <button class="btn btn-outline-warning w-100" data-plan="Básico">Elegir Plan</button>
                        </div>
                    </div>
                </div>

                <!-- Plan Completo -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow plan-featured">
                        <div class="card-header bg-warning text-center py-4">
                            <h4 class="fw-bold">Plan Completo</h4>
                            <div class="display-4 fw-bold text-dark">$25.000</div>
                            <small class="text-dark">por mes</small>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-unstyled mb-0">
                                <li><i class="fas fa-check text-success me-2"></i>Acceso completo al gimnasio</li>
                                <li><i class="fas fa-check text-success me-2"></i>Uso de todos los equipos</li>
                                <li><i class="fas fa-check text-success me-2"></i>Vestuarios y duchas</li>
                                <li><i class="fas fa-check text-success me-2"></i>Clases grupales ilimitadas</li>
                                <li><i class="fas fa-check text-success me-2"></i>2 sesiones de entrenamiento personal
                                </li>
                                <li><i class="fas fa-times text-muted me-2"></i>Consulta nutricional</li>
                            </ul>
                        </div>
                        <div class="card-footer bg-transparent p-4">
                            <button class="btn btn-warning w-100" data-plan="Completo">Elegir Plan</button>
                        </div>
                    </div>
                </div>

                <!-- Plan Premium -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-dark text-white text-center py-4">
                            <h4 class="fw-bold">Plan Premium</h4>
                            <div class="display-4 fw-bold text-warning">$35.000</div>
                            <small class="text-light">por mes</small>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-unstyled mb-0">
                                <li><i class="fas fa-check text-success me-2"></i>Acceso completo al gimnasio</li>
                                <li><i class="fas fa-check text-success me-2"></i>Uso de todos los equipos</li>
                                <li><i class="fas fa-check text-success me-2"></i>Vestuarios y duchas</li>
                                <li><i class="fas fa-check text-success me-2"></i>Clases grupales ilimitadas</li>
                                <li><i class="fas fa-check text-success me-2"></i>4 sesiones de entrenamiento personal
                                </li>
                                <li><i class="fas fa-check text-success me-2"></i>Consulta nutricional mensual</li>
                            </ul>
                        </div>
                        <div class="card-footer bg-transparent p-4">
                            <button class="btn btn-dark w-100" data-plan="Premium">Elegir Plan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-5 fw-bold">Preguntas Frecuentes</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq1">
                                    ¿Puedo cambiar de plan en cualquier momento?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Sí, puedes cambiar tu plan en cualquier momento. Los cambios se aplicarán en tu
                                    próximo ciclo de facturación.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq2">
                                    ¿Hay permanencia mínima?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    No hay permanencia mínima. Puedes cancelar tu membresía en cualquier momento con 30
                                    días de anticipación.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq3">
                                    ¿Qué incluyen las clases grupales?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Nuestras clases incluyen: Yoga, Pilates, Spinning, Crossfit, Zumba, Funcional y más.
                                    Consulta nuestro cronograma semanal.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>

</html>