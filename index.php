<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KynetikGym - Tu Gimnasio de Confianza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js" defer></script>
</head>

<body>

    <?php
    $currentPage = 'index';
    include __DIR__ . '/includes/navbar.php';
    ?>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-white mb-4">
                        Transforma tu cuerpo,<br>
                        <span class="text-warning">transforma tu vida</span>
                    </h1>
                    <p class="lead text-light mb-4">
                        Únete a KynetikGym y descubre el mejor equipamiento, entrenadores profesionales y un ambiente
                        motivador para alcanzar tus objetivos fitness.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <a href="registro.php" class="btn btn-warning btn-lg px-4">Comenzar Ahora</a>
                        <a href="planes.php" class="btn btn-outline-warning btn-lg px-4">Ver Planes</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-dumbbell display-1 text-warning hero-image"></i>
                </div>
            </div>
        </div>
    </section>

    <section id="servicios" class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-3">¿Por qué elegir KynetikGym?</h2>
            <p class="lead text-muted mb-5">Todo lo que necesitas para tu transformación</p>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <i class="fas fa-users display-4 text-warning mb-3"></i>
                        <h5>Clases Grupales</h5>
                        <p class="text-muted">Variedad de clases dirigidas por instructores certificados. Desde yoga
                            hasta crossfit.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <i class="fas fa-user-tie display-4 text-warning mb-3"></i>
                        <h5>Entrenamiento Personal</h5>
                        <p class="text-muted">Sesiones individuales con personal trainers especializados para maximizar
                            tus resultados.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <i class="fas fa-apple-alt display-4 text-warning mb-3"></i>
                        <h5>Nutrición</h5>
                        <p class="text-muted">Consultas nutricionales personalizadas y planes alimentarios adaptados a
                            tus objetivos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>

</html>