<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once("./include-socio/head.php"); ?>
</head>

<body>

    <!-- Navigation -->
    <?php require_once("./include-socio/navbar.php"); ?>

    <header class="text-center py-5 bg-light border-bottom">
        <h1 class="display-5 fw-bold text-dark">Plan del Socio</h1>
        <p class="lead text-secondary">Checkea tu plan activo</p>
    </header>

    <main class="container py-5">
        <section class="mb-5">
            <div class="card card-socio">
                <div class="card-body">
                    <h5 class="card-title">Plan Actual: <span class="text-warning fw-bold">Premium</span></h5>
                    <p><strong>Inicio:</strong> 01/09/2025</p>
                    <p><strong>Vencimiento:</strong> 01/10/2025</p>
                    <p><strong>Estado:</strong> <span class="text-success fw-semibold">Activo</span></p>

                    <table class="table table-bordered mt-4">
                        <thead class="table-warning text-dark">
                            <tr>
                                <th>Servicio</th>
                                <th>Disponible</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Clases particulares</td>
                                <td>Ilimitadas</td>
                            </tr>
                            <tr>
                                <td>Consultas nutrición</td>
                                <td>2 restantes</td>
                            </tr>
                            <tr>
                                <td>Sesiones personal trainer</td>
                                <td>1 restante</td>
                            </tr>
                            <tr>
                                <td>Plan de alimentación</td>
                                <td>Incluido</td>
                            </tr>
                            <tr>
                                <td>Rutinas</td>
                                <td>Personalizadas</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <!--Footer-->
    <?php require_once("./include-socio/footer.php"); ?>

</body>

</html>