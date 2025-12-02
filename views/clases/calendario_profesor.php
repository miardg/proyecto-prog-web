<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'] ?? null;

if (
    !$idUsuario || !Permisos::tienePermiso('Cancelar clase', $idUsuario) ||
    !Permisos::tienePermiso('Ver clases asignadas', $idUsuario) ||
    !Permisos::tienePermiso('Ver inscriptos', $idUsuario) ||
    !Permisos::tienePermiso('Confirmar asistencia', $idUsuario)
) {
    header("Location: /proyecto-prog-web/views/dashboard.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Calendario del Profesor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js" defer></script>
    <script src="../../assets/js/calendario.js" defer></script>

</head>

<body>

    <div>
        <?php include __DIR__ . '/../../includes/navbar_permisos.php'; ?>
    </div>
    <br>
    <div class="container mt-5">
        <h1 class="mb-4">Calendario semanal de clases</h1>
        <div id="calendar"></div>
    </div>

    <!-- Modal clase -->
    <div class="modal fade" id="modalClase" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de clase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-3" id="tabsClase" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-info"
                                type="button" role="tab">Información</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-inscriptos" type="button"
                                role="tab">Inscriptos</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-asistencia" type="button"
                                role="tab">Asistencia</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-inscribir" type="button"
                                role="tab">Inscribir alumno</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-correo" type="button"
                                role="tab">Enviar correo</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Información -->
                        <div class="tab-pane fade show active" id="tab-info" role="tabpanel">
                            <div id="infoClase" class="mb-3">
                            </div>
                            <div class="mb-3">
                                <label for="motivoCancelacion" class="form-label">Motivo de cancelación</label>
                                <textarea id="motivoCancelacion" class="form-control" rows="2"
                                    placeholder="Ej: Profesor enfermo"></textarea>
                            </div>
                            <button id="btnCancelarClase" class="btn btn-danger">Cancelar clase y notificar</button>
                        </div>

                        <!-- Inscriptos -->
                        <div class="tab-pane fade" id="tab-inscriptos" role="tabpanel">
                            <ul id="listaInscriptos" class="list-group"></ul>
                        </div>

                        <!-- Asistencia -->
                        <div class="tab-pane fade" id="tab-asistencia" role="tabpanel">
                            <div id="contenedorAsistencia" class="d-none">
                                <ul id="listaAsistencia" class="list-group mb-3"></ul>
                                <button id="btnGuardarAsistencia" class="btn btn-primary">Guardar asistencia</button>
                            </div>
                        </div>

                        <!-- Inscribir -->
                        <div class="tab-pane fade" id="tab-inscribir" role="tabpanel">
                            <div class="input-group mb-3">
                                <input type="text" id="buscarSocio" class="form-control"
                                    placeholder="Buscar socio por nombre o DNI">
                                <button id="btnBuscarSocio" class="btn btn-outline-secondary">Buscar</button>
                            </div>
                            <ul id="resultadosSocios" class="list-group mb-3"></ul>
                        </div>

                        <!-- Enviar correo -->
                        <div class="tab-pane fade" id="tab-correo" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">Asunto</label>
                                <input type="text" id="correoAsunto" class="form-control"
                                    placeholder="Aviso importante sobre la clase">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mensaje</label>
                                <textarea id="correoMensaje" class="form-control" rows="4"
                                    placeholder="Escribí tu mensaje para los inscriptos"></textarea>
                            </div>
                            <button id="btnEnviarCorreo" class="btn btn-warning">Enviar correo a inscriptos</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Toast de notificación -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="toastSuccess" class="toast align-items-center text-bg-success border-0" role="alert">
            <div class="d-flex">
                <div id="toastMsg" class="toast-body">Acción completada</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>



    <?php include __DIR__ . '/../../includes/footer.php'; ?>

</body>

</html>