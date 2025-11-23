<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'] ?? null;

// Validar permisos: al menos uno
if (
    !Permisos::tienePermiso('Aprobar nuevos socios', $idUsuario) &&
    !Permisos::tienePermiso('Asignar plan a un socio', $idUsuario)
) {
    header("Location: /proyecto-prog-web/views/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Socios pendientes de aprobación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/proyecto-prog-web/assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/proyecto-prog-web/assets/js/socios_pendientes.js" defer></script>
</head>

<body>
    <div class="container mt-5 px-3">
        <?php include __DIR__ . '/../../includes/navbar_permisos.php'; ?>
        <br>

        <h2 class="fw-bold mb-4">Socios pendientes de aprobación</h2>
        <p class="text-muted">Aprobá registros web y asignales plan + primer pago</p>

        <!-- Buscador -->
        <div class="row g-2 mb-4">
            <div class="col-12 col-md-8">
                <input id="searchPendienteInput" class="form-control" placeholder="Buscar por nombre o email...">
            </div>
            <div class="col-12 col-md-4 d-grid">
                <button class="btn btn-primary" id="btnBuscarPendiente">
                    <i class="fas fa-search me-2"></i>Buscar
                </button>
            </div>
        </div>

        <!-- Tabla -->
        <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-hover align-middle" style="min-width:640px;">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th class="d-none d-sm-table-cell">Email</th>
                        <th class="d-none d-md-table-cell">Fecha registro</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="sociosPendientesTbody">
                    <!-- Renderizado por JS -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL: Aprobar socio -->
    <div class="modal fade" id="modalAprobarSocio" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form id="formAprobarSocio">
                    <div class="modal-header">
                        <h5 class="modal-title">Aprobar socio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="aprobarIdUsuario">

                        <!-- Plan -->
                        <label class="form-label">Plan</label>
                        <select id="aprobarPlanSelect" class="form-select" required>
                            <option value="">Seleccione un plan</option>
                            <option value="1">Básico</option>
                            <option value="2">Standard</option>
                            <option value="3">Premium</option>
                            <option value="4">Élite</option>
                        </select>
                        <div class="invalid-feedback">Debe seleccionar un plan válido.</div>

                        <!-- Fechas -->
                        <label class="form-label mt-3">Fecha de alta</label>
                        <input type="date" id="aprobarFechaAlta" class="form-control"
                            value="<?php echo date('Y-m-d'); ?>" required>

                        <label class="form-label mt-3">Fecha de vencimiento</label>
                        <input type="date" id="aprobarFechaVencimiento" class="form-control" required>

                        <!-- Primer pago -->
                        <hr class="my-3">
                        <h6 class="fw-bold">Primer pago</h6>

                        <label class="form-label">Monto</label>
                        <input type="number" id="aprobarMonto" class="form-control" required>

                        <label class="form-label mt-3">Método de pago</label>
                        <select id="aprobarMetodo" class="form-select" required>
                            <option value="">Seleccione método</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                        </select>

                        <label class="form-label mt-3">Periodo correspondiente</label>
                        <input type="text" id="aprobarPeriodo" class="form-control" placeholder="Ej: Noviembre 2025" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Aprobar socio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
