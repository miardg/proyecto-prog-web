<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once("./template-socio/head.php"); ?>
</head>

<body>

    <!-- Navigation -->
    <?php require_once("./template-socio/navbar.php"); ?>

    <header class="text-center py-5 bg-light border-bottom mt-5">
        <h1 class="display-5 fw-bold text-dark">Beneficios del Socio</h1>
        <p class="lead text-secondary">Consultá, buscá y canjeá tus beneficios disponibles</p>
    </header>

    <main class="container py-5">
        <input type="text" id="buscadorBeneficio" class="form-control mb-4" placeholder="Buscar beneficio...">

        <div class="card card-socio">
            <div class="card-body p-0">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-warning text-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaBeneficios">
                        <!-- JS inserta filas -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>


    <!-- Modal -->
    <div class="modal fade" id="modalBeneficio" tabindex="-1" aria-labelledby="modalBeneficioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-white text-dark">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-warning" id="modalBeneficioLabel">Canje de beneficio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="modalBeneficioBody">
                    <!-- JS inserta contenido -->
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!--Footer-->
    <?php require_once("./include-socio/footer.php"); ?>

</body>

</html>