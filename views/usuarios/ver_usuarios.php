<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'] ?? null;

if (!$idUsuario || !Permisos::tienePermiso("Ver usuarios", $idUsuario)) {
    header("Location: /proyecto-prog-web/views/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ver usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/usuarios.js" defer></script>
    <script src="../../assets/js/roles.js" defer></script>
</head>

<body>
    <div>
        <?php include __DIR__ . '/../../includes/navbar_permisos.php'; ?>
    </div>
    <div class="py-5 bg-light">
        <div class="container">
            <h2 class="mb-4 mt-4">Usuarios registrados</h2>
            <div id="usuariosFeedback"></div>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>DNI</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="usuariosTableBody">
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Modificar Usuario -->
    <div class="modal fade" id="modalModificarUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formModificarUsuario">
                    <div class="modal-header">
                        <h5 class="modal-title">Modificar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="modificarIdUsuario" name="id_usuario">

                        <div class="mb-3">
                            <label for="modificarNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="modificarNombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="modificarApellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="modificarApellido" name="apellido" required>
                        </div>
                        <div class="mb-3">
                            <label for="modificarEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="modificarEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="modificarTelefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="modificarTelefono" name="telefono">
                        </div>
                        <div class="mb-3">
                            <label for="modificarDni" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="modificarDni" name="dni" required>
                        </div>
                        <div class="mb-3">
                            <label for="modificarRol" class="form-label">Rol</label>
                            <select class="form-select" id="modificarRol" name="rol" required>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div id="modificarUsuarioFeedback" class="me-auto"></div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>