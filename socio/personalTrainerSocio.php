<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once("./include-socio/head.php"); ?>
</head>

<body>

    <!-- Navigation -->
    <?php require_once("./include-socio/navbar.php"); ?>

    <header class="text-center py-5 bg-light border-bottom">
        <h1 class="display-5 fw-bold text-dark">Personal Trainer</h1>
        <p class="lead text-secondary">Consultá tus sesiones y objetivos con tu entrenador</p>
    </header>

    <main class="container py-5">
        <section>
            <div class="row g-4" id="bloqueTrainer">
                <!-- JS inserta contenido -->
            </div>
        </section>
    </main>

    <!-- Modal Historial -->
    <div class="modal fade" id="modalHistorialTrainer" tabindex="-1" aria-labelledby="modalHistorialLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-white text-dark">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-warning" id="modalHistorialLabel">Historial de Sesiones</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">🗓️ 20/09 - Cardio + Core</li>
                        <li class="list-group-item">🗓️ 23/09 - Fuerza tren superior</li>
                        <li class="list-group-item">🗓️ 26/09 - HIIT + movilidad</li>
                    </ul>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de carga -->
    <div class="modal fade" id="modalCargandoTurno" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-white text-dark text-center">
                <div class="modal-body py-4">
                    <h5 class="mb-3">Solicitando turno...</h5>
                    <i class="fas fa-spinner fa-spin fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <!--Footer-->
    <?php require_once("./include-socio/footer.php"); ?>

</body>

</html>