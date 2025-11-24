<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'] ?? null;

if (!$idUsuario || !Permisos::tienePermiso("Crear clases", $idUsuario)) {
    header("Location: /proyecto-prog-web/views/dashboard.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Clase - Kynetik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="../../assets/js/profesor.js" defer></script>
    <script src="../../assets/js/clases.js" defer></script>
</head>

<body>
    <div>
        <?php include __DIR__ . '/../../includes/navbar_permisos.php'; ?>
    </div>
    <div class="auth-container d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 p-5 mt-5">
                    <div class="auth-card">
                        <div class="row g-0">
                            <!-- Lado izquierdo -->
                            <div
                                class="col-md-6 d-flex align-items-center justify-content-center bg-dark text-white p-5">
                                <div class="text-center">
                                    <i class="fas fa-calendar-plus display-3 text-warning mb-4"></i>
                                    <h3 class="fw-bold mb-3">Nueva Clase</h3>
                                    <p class="text-muted">Agregá una clase al sistema</p>
                                </div>
                            </div>

                            <!-- Lado derecho: formulario -->
                            <div class="col-md-6 p-5">
                                <div class="text-center mb-4">
                                    <h2 class="fw-bold">Crear Clase</h2>
                                    <p class="text-muted">Completa los datos de la clase</p>
                                </div>

                                <?php if (!empty($feedback_error)): ?>
                                    <div class="alert alert-danger text-center">
                                        <?= htmlspecialchars($feedback_error) ?>
                                    </div>
                                <?php elseif (!empty($feedback_success)): ?>
                                    <div class="alert alert-success text-center">
                                        <?= htmlspecialchars($feedback_success) ?>
                                    </div>
                                <?php endif; ?>

                                <form id="crearClaseForm" method="POST" action="procesar_crear_clase.php" novalidate>
                                    <div class="mb-3">
                                        <label for="nombre_clase" class="form-label">Nombre de la clase</label>
                                        <input type="text" class="form-control" id="nombre_clase" name="nombre_clase"
                                            value="<?= htmlspecialchars($_POST['nombre_clase'] ?? '') ?>" required
                                            pattern="^[a-zA-ZÀ-ÿ\s]{2,50}$">
                                        <div class="invalid-feedback">Ingrese un nombre válido (2–50 letras).</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tipo_actividad" class="form-label">Tipo de actividad</label>
                                        <input type="text" class="form-control" id="tipo_actividad"
                                            name="tipo_actividad"
                                            value="<?= htmlspecialchars($_POST['tipo_actividad'] ?? '') ?>" required
                                            pattern="^[a-zA-ZÀ-ÿ\s]{2,50}$">
                                        <div class="invalid-feedback">Ingrese el tipo de actividad (2–50 letras).</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="lugar" class="form-label">Lugar</label>
                                        <select class="form-select" id="lugar" name="lugar" required>
                                            <option value="">Seleccione una sala</option>
                                            <option value="Sala 1">Sala 1</option>
                                            <option value="Sala 2">Sala 2</option>
                                            <option value="Sala 3">Sala 3</option>
                                        </select>
                                        <div class="invalid-feedback">Seleccione una sala válida.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="dia_semana" class="form-label">Día de la semana</label>
                                        <select class="form-select" id="dia_semana" name="dia_semana" required>
                                            <option value="">Seleccione un día</option>
                                            <?php
                                            $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                            foreach ($dias as $d) {
                                                $selected = ($_POST['dia_semana'] ?? '') === $d ? 'selected' : '';
                                                echo "<option value=\"$d\" $selected>$d</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">Seleccione un día válido.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="duracion_min" class="form-label">Duración (minutos)</label>
                                        <select class="form-select" id="duracion_min" name="duracion_min" required>
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
                                        <label for="hora_inicio" class="form-label">Hora de inicio</label>
                                        <select class="form-select" id="hora_inicio" name="hora_inicio" required>
                                            <option value="">Seleccione día, sala y duración</option>
                                        </select>
                                        <div class="invalid-feedback">Seleccione una hora válida.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="profesor_id" class="form-label">Profesor</label>
                                        <select class="form-select" id="profesor_id" name="profesor_id" required>
                                            <option value="">Seleccione un profesor</option>
                                        </select>
                                        <div class="invalid-feedback">Seleccione un profesor válido.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="cupo_maximo" class="form-label">Cupo máximo</label>
                                        <input type="number" class="form-control" id="cupo_maximo" name="cupo_maximo"
                                            value="<?= htmlspecialchars($_POST['cupo_maximo'] ?? '') ?>" required
                                            min="1" max="100">
                                        <div class="invalid-feedback">Cupo entre 1 y 100.</div>
                                    </div>

                                    <button type="submit" class="btn btn-warning w-100 mb-3" id="btnCrearClase">Crear
                                        Clase</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>