<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'] ?? null;

if (
    !Permisos::tienePermiso('Cambiar plan de socio', $idUsuario) &&
    !Permisos::tienePermiso('Dar de baja socio', $idUsuario)
) {
    header("Location: /proyecto-prog-web/views/dashboard.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Socios Activos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/proyecto-prog-web/assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/proyecto-prog-web/assets/js/socios_activos.js" defer></script>
</head>

<body>
    <div class="container mt-5 px-3">

        <?php include __DIR__ . '/../../includes/navbar_permisos.php'; ?>
        <br>

        <h2 class="fw-bold mb-4">Gestión de Socios Activos</h2>
        <p class="text-muted">Buscar, cambiar plan o dar de baja socios activos</p>

        <div class="row g-2 mb-4">
            <div class="col-12 col-md-8">
                <input id="searchSocioInput" class="form-control" placeholder="Buscar por nombre o email...">
            </div>
            <div class="col-12 col-md-4 d-grid">
                <button class="btn btn-primary" id="btnBuscarSocio">
                    <i class="fas fa-search me-2"></i>Buscar
                </button>
            </div>
        </div>

        <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-hover align-middle" style="min-width:640px;">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th class="d-none d-sm-table-cell">Email</th>
                        <th>Plan</th>
                        <th class="d-none d-md-table-cell">Fecha alta</th>
                        <th>Estado cuota</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="sociosActivosTbody">
                </tbody>
            </table>
        </div>


        <!-- MODAL: Cambiar plan -->
        <div class="modal fade" id="modalCambiarPlan" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <form id="formCambiarPlan">
                        <div class="modal-header">
                            <h5 class="modal-title">Cambiar plan de socio</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="cambiarPlanIdSocio">
                            <label class="form-label">Nuevo plan</label>
                            <select id="nuevoPlanSelect" class="form-select" required>
                                <option value="">Seleccione un plan</option>
                                <option value="1">Básico</option>
                                <option value="2">Standard</option>
                                <option value="3">Premium</option>
                                <option value="4">Élite</option>
                            </select>
                            <div class="invalid-feedback">Debe seleccionar un plan válido.</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL: Dar de baja -->
        <div class="modal fade" id="modalBajaSocio" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar baja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Seguro que querés dar de baja este socio?</p>
                        <input type="hidden" id="bajaIdSocio">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="btnConfirmarBaja">Dar de baja</button>
                    </div>
                </div>
            </div>
        </div>

</body>

</html>