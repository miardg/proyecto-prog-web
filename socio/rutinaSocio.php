<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once("./include-socio/head.php"); ?>
</head>

<body>

    <!-- Navigation -->
    <?php require_once("./include-socio/navbar.php"); ?>

    <header class="text-center py-5 bg-light border-bottom mt-5">
        <h1 class="display-5 fw-bold text-dark">Mis Rutinas</h1>
        <p class="lead text-secondary">Consultá tus rutinas activas y progresos semanales</p>
    </header>

    <main class="container py-5">
        <label for=""></label>

        <div class="card card-socio">
            <div class="card-body p-0">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-warning">
                        <tr>
                            <th>Día</th>
                            <th>Rutina</th>
                            <th>Objetivo</th>
                            <th>Duración</th>
                        </tr>
                    </thead>
                    <tbody id="tablaRutinas">
                        <!-- JS inserta filas -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!--Footer-->
    <?php require_once("./include-socio/footer.php"); ?>

</body>

</html>