<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'] ?? null;

if (!$idUsuario || !Permisos::tienePermiso("Ver clases", $idUsuario)) {
    header("Location: ../dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Clases disponibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/clases.js" defer></script>
    <script src="../../assets/js/profesor.js" defer></script>
</head>

<body>
    <?php include __DIR__ . '/../../includes/navbar_permisos.php'; ?>
    <div class="container mt-4">
        <h2 class="mb-4">Todas las clases activas</h2>
        <div id="verTodasLasClases" class="table-responsive"></div>
    </div>

    <!-- MODAL PARA MODIFICAR CLASE -->
    <div class="modal fade" id="modalModificarClase" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formModificarClase" class="modal-content" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Clase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_clase" id="mod-id-clase">

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre_clase" id="mod-nombre" required
                            pattern="^[a-zA-ZÀ-ÿ\s]{2,50}$">
                        <div class="invalid-feedback">Ingrese un nombre válido (2–50 letras).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Actividad</label>
                        <input type="text" class="form-control" name="tipo_actividad" id="mod-actividad" required
                            pattern="^[a-zA-ZÀ-ÿ\s]{2,50}$">
                        <div class="invalid-feedback">Ingrese el tipo de actividad (2–50 letras).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lugar</label>
                        <select class="form-select" name="lugar" id="mod-lugar" required>
                            <option value="">Seleccione una sala</option>
                            <option value="Sala 1">Sala 1</option>
                            <option value="Sala 2">Sala 2</option>
                            <option value="Sala 3">Sala 3</option>
                        </select>
                        <div class="invalid-feedback">Seleccione una sala válida.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Día</label>
                        <select class="form-select" name="dia_semana" id="mod-dia" required>
                            <option value="">Seleccione un día</option>
                            <option>Lunes</option>
                            <option>Martes</option>
                            <option>Miércoles</option>
                            <option>Jueves</option>
                            <option>Viernes</option>
                            <option>Sábado</option>
                            <option>Domingo</option>
                        </select>
                        <div class="invalid-feedback">Seleccione un día válido.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Duración</label>
                        <select class="form-select" name="duracion_min" id="mod-duracion" required>
                            <option value="">Seleccione duración</option>
                            <option value="30">30 minutos</option>
                            <option value="45">45 minutos</option>
                            <option value="60">60 minutos</option>
                            <option value="75">75 minutos</option>
                            <option value="90">90 minutos</option>
                        </select>
                        <div class="invalid-feedback">Seleccione una duración válida.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hora de inicio</label>
                        <select class="form-select" name="hora_inicio" id="mod-hora" required>
                            <option value="">Seleccione día, sala y duración</option>
                        </select>
                        <div class="invalid-feedback">Seleccione una hora válida.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profesor</label>
                        <select class="form-select" name="profesor_id" id="mod-profesor" required>
                            <option value="">Seleccione un profesor</option>
                        </select>
                        <div class="invalid-feedback">Seleccione un profesor válido.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cupo máximo</label>
                        <input type="number" class="form-control" name="cupo_maximo" id="mod-cupo" required min="1"
                            max="100">
                        <div class="invalid-feedback">Cupo entre 1 y 100.</div>
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