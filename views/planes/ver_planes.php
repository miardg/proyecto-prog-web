<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'];
$nombre = $_SESSION['user']['name'];

if (!Permisos::tienePermiso("Ver planes", $idUsuario)) {
    echo "<div class='alert alert-danger text-center mt-5'>No tenés permiso para ver los planes disponibles</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Planes disponibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/planes.js" defer></script>
</head>

<body>
    <div>
        <?php include __DIR__ . '/../../includes/navbar_permisos.php'; ?>
    </div>

    <div class="d-flex justify-content-center align-items-start min-vh-100 bg-light pt-5">
        <div class="card shadow-lg p-4" style="max-width: 900px; width: 100%;">
            <h2 class="titulo-panel mb-4 text-center">Planes disponibles</h2>
            <div id="listaPlanes"></div>
        </div>
    </div>

    <!-- MODAL PARA MODIFICAR PLAN -->
    <div class="modal fade" id="modalModificarPlan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formModificarPlan" class="modal-content" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_plan" id="mod-id-plan">

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="mod-nombre" required
                            pattern="^[a-zA-ZÀ-ÿ\s]{2,50}$">
                        <div class="invalid-feedback">Ingrese un nombre válido (2–50 letras).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="mod-descripcion" rows="3" required
                            minlength="10" maxlength="500"></textarea>
                        <div class="invalid-feedback">Ingrese una descripción válida (10–500 caracteres).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Precio</label>
                        <input type="number" class="form-control" name="precio" id="mod-precio" required min="1"
                            step="0.01">
                        <div class="invalid-feedback">Ingrese un precio válido.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Frecuencia</label>
                        <select class="form-select" name="frecuencia_servicios" id="mod-frecuencia" required>
                            <option value="">Seleccione frecuencia</option>
                            <option value="Mensual">Mensual</option>
                            <option value="Trimestral">Trimestral</option>
                            <option value="Anual">Anual</option>
                        </select>
                        <div class="invalid-feedback">Seleccione una frecuencia válida.</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>