<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'] ?? null;

if (!Permisos::tienePermiso('Registrar pago de cuota', $idUsuario)) {
    header("Location: /proyecto-prog-web/views/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de pago de cuota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/proyecto-prog-web/assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/proyecto-prog-web/assets/js/registro_pago.js" defer></script>
</head>
<body>
<div class="container mt-5 px-3">
    <?php include __DIR__ . '/../../includes/navbar_permisos.php'; ?>
    <br>

    <h2 class="fw-bold mb-4">Registro de pago de cuota</h2>
    <p class="text-muted">Buscar socio y registrar pago manual</p>

    <!-- Buscador -->
    <div class="row g-2 mb-4">
        <div class="col-12 col-md-8">
            <input id="searchPagoInput" class="form-control" placeholder="Buscar por nombre o email...">
        </div>
        <div class="col-12 col-md-4 d-grid">
            <button class="btn btn-primary" id="btnBuscarPago">
                <i class="fas fa-search me-2"></i>Buscar
            </button>
        </div>
    </div>

    <!-- Tabla -->
    <div class="table-responsive" style="overflow-x:auto;">
        <table class="table table-hover align-middle" style="min-width:720px;">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th class="d-none d-sm-table-cell">Email</th>
                    <th>Plan</th>
                    <th class="d-none d-md-table-cell">Último pago</th>
                    <th>Estado cuota</th>
                    <th class="d-none d-md-table-cell">Vencimiento</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody id="sociosPagoTbody"><!-- Render JS --></tbody>
        </table>
    </div>
</div>

<!-- MODAL: Registrar pago -->
<div class="modal fade" id="modalRegistrarPago" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <form id="formRegistrarPago">
        <div class="modal-header">
          <h5 class="modal-title">Registrar pago</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="pagoIdSocio">

          <div class="mb-2">
            <span class="small text-muted">Socio:</span>
            <div id="pagoResumenSocio" class="small"></div>
          </div>

          <label class="form-label">Monto</label>
          <input type="number" id="pagoMonto" class="form-control" min="0" step="0.01" required>

          <label class="form-label mt-3">Método de pago</label>
          <select id="pagoMetodo" class="form-select" required>
            <option value="">Seleccione método</option>
            <option value="efectivo">Efectivo</option>
            <option value="transferencia">Transferencia</option>
            <option value="tarjeta">Tarjeta</option>
          </select>

          <label class="form-label mt-3">Fecha de pago</label>
          <input type="date" id="pagoFecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>

          <label class="form-label mt-3">Periodo correspondiente</label>
          <input type="text" id="pagoPeriodo" class="form-control" placeholder="Ej: Noviembre 2025" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Registrar pago</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
