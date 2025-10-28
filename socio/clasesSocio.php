<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once("./include-socio/head.php"); ?>
</head>

<body>

    <!-- Navigation -->
    <?php require_once("./include-socio/navbar.php"); ?>

    <header class="text-center py-5 bg-light border-bottom">
        <h1 class="display-5 fw-bold text-dark">Mis Clases</h1>
        <p class="lead text-secondary">Consultá tus clases activas y reservá nuevas</p>
    </header>

    <main class="container py-5">
        <!-- Resumen del plan -->
        <section class="mb-5">
            <h3 class="text-warning mb-3">Resumen de Plan</h3>
            <div class="card card-socio">
                <div class="card-body">
                    <p><strong>Plan:</strong> <span class="text-warning">Premium</span></p>
                    <p><strong>Estado:</strong> <span class="text-success">Activo</span></p>
                    <p><strong>Clases particulares restantes:</strong> Ilimitadas</p>
                </div>
            </div>
        </section>

        <!-- Clases disponibles -->
        <section>
            <h3 class="text-warning mb-3">Clases Disponibles</h3>
            <div class="row g-4" id="clasesDisponibles">
                <!-- JS inserta cards acá -->
            </div>
        </section>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="modalClase" tabindex="-1" aria-labelledby="modalClaseLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-white text-dark">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-warning" id="modalClaseLabel">Confirmar inscripción</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="modalClaseBody">
                    <!-- JS inserta contenido -->
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-warning" id="confirmarClase">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!--Footer-->
    <?php require_once("./include-socio/footer.php"); ?>

</body>

</html>