<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../permisos.php';
require_login();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>KynetikGym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/perfil.js"></script>
</head>

<body>
    <div class="container mt-5">
        <?php include __DIR__ . '/../includes/navbar_permisos.php'; ?>
        <br>
        <h2>Mi Perfil</h2>

        <div class="card p-4 mb-3">
            <h5 class="card-title">Datos personales</h5>

            <p>
                <strong>Nombre:</strong> <span id="cardNombre"></span>
                <i class="fa fa-pen ms-2 text-primary edit-icon" data-campo="nombre"></i>
            </p>

            <p>
                <strong>Apellido:</strong> <span id="cardApellido"></span>
                <i class="fa fa-pen ms-2 text-primary edit-icon" data-campo="apellido"></i>
            </p>

            <p>
                <strong>DNI:</strong> <span id="cardDni"></span>
            </p>

            <p>
                <strong>Email:</strong> <span id="cardEmail"></span>
                <i class="fa fa-pen ms-2 text-primary edit-icon" data-campo="email"></i>
            </p>

            <p>
                <strong>Teléfono:</strong> <span id="cardTelefono"></span>
                <i class="fa fa-pen ms-2 text-primary edit-icon" data-campo="telefono"></i>
            </p>

            <p>
                <strong>Contraseña:</strong> ********
                <i class="fa fa-pen ms-2 text-primary edit-icon" data-campo="password"></i>
            </p>
        </div>
    </div>

    <!-- Modal para edición -->
    <div class="modal fade" id="modalEditar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditarCampo" class="p-4">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarTitulo">Editar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="modalEditarBody">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal feedback -->
    <div class="modal fade" id="modalFeedback" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFeedbackTitulo">Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalFeedbackBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>
