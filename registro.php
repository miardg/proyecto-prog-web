<?php
require_once __DIR__ . '/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirmPassword'] ?? '';

    if ($nombre === '' || $apellido === '' || $dni === '' || $email === '' || $telefono === '' || $password === '') {
        $error = "Todos los campos son obligatorios";
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s]{2,50}$/", $nombre)) {
        $error = "Nombre inválido (solo letras, mínimo 2 caracteres)";
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s]{2,50}$/", $apellido)) {
        $error = "Apellido inválido (solo letras, mínimo 2 caracteres)";
    } elseif (!preg_match("/^\d{7,8}$/", $dni)) {
        $error = "DNI inválido (debe tener 7 u 8 dígitos numéricos)";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email inválido";
    } elseif (!preg_match("/^[0-9\-]{6,15}$/", $telefono)) {
        $error = "Teléfono inválido (solo números y guiones, entre 6 y 15 caracteres)";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } elseif ($password !== $confirm) {
        $error = "Las contraseñas no coinciden";
    } else {
        try {
            // verificar si ya existe el email
            $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE email = :email LIMIT 1");
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            if ($stmt->fetch()) {
                $error = "Ya existe una cuenta con ese email";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                // insertar usuario con estado inactivo
                $stmt = $conn->prepare("INSERT INTO usuario 
                    (nombre, apellido, dni, email, telefono, password_hash, fecha_alta, estado) 
                    VALUES (:nombre, :apellido, :dni, :email, :telefono, :hash, NOW(), 'inactivo')");
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':apellido' => $apellido,
                    ':dni' => $dni,
                    ':email' => $email,
                    ':telefono' => $telefono,
                    ':hash' => $hash
                ]);

                $idUsuario = $conn->lastInsertId();

                // asignar rol socio (id_rol = 4)
                $stmt = $conn->prepare("INSERT INTO roles_usuarios (id_usuario, id_rol) VALUES (:idUsuario, 4)");
                $stmt->execute([':idUsuario' => $idUsuario]);

                $success = "Registro enviado. Un recepcionista confirmará su alta.";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                //error dni o mail duplicado
                if (strpos($e->getMessage(), 'dni') !== false) {
                    $error = "Ya existe un usuario registrado con ese DNI.";
                } elseif (strpos($e->getMessage(), 'email') !== false) {
                    $error = "Ya existe una cuenta con ese correo.";
                } else {
                    $error = "El dato ingresado ya está registrado.";
                }
            } else {
                $error = "Error al registrar: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Kynetik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="assets/js/main.js" defer></script>
</head>

<body>
    <div class="auth-container d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-6">
                    <div class="auth-card">
                        <div class="row g-0">
                            <div
                                class="col-md-6 d-flex align-items-center justify-content-center bg-dark text-white p-5">
                                <div class="text-center">
                                    <i class="fas fa-dumbbell display-3 text-warning mb-4"></i>
                                    <h3 class="fw-bold mb-3">¡Únete a KynetikGym!</h3>
                                    <p class="text-muted">Comienza tu transformación hoy mismo</p>
                                </div>
                            </div>

                            <div class="col-md-6 p-5">
                                <div class="text-center mb-4">
                                    <h2 class="fw-bold">Crear Cuenta</h2>
                                    <p class="text-muted">Completa tus datos para registrarte</p>
                                </div>

                                <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger text-center">
                                        <?= htmlspecialchars($error) ?>
                                    </div>
                                <?php elseif (!empty($success)): ?>
                                    <div class="alert alert-success text-center">
                                        <?= htmlspecialchars($success) ?>
                                    </div>
                                <?php endif; ?>
                                <!-- FORM REGISTRO -->
                                <form id="registroForm" method="POST" action="registro.php">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                                            <div class="invalid-feedback">Por favor ingrese su nombre.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="apellido" class="form-label">Apellido</label>
                                            <input type="text" class="form-control" id="apellido" name="apellido"
                                                required>
                                            <div class="invalid-feedback">Por favor ingrese su apellido.</div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="dni" class="form-label">DNI</label>
                                        <input type="number" class="form-control" id="dni" name="dni" required>
                                        <div class="invalid-feedback">Ingrese un DNI válido (7 a 8 dígitos).</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <div class="invalid-feedback">Ingrese un correo válido.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="tel" class="form-control" id="telefono" name="telefono" required>
                                        <div class="invalid-feedback">Ingrese un teléfono válido.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            required>
                                        <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirmar Contraseña</label>
                                        <input type="password" class="form-control" id="confirmPassword"
                                            name="confirmPassword" required>
                                        <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                                    </div>

                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="terminos" name="terminos"
                                            required>
                                        <label class="form-check-label" for="terminos">
                                            Acepto los <a href="#" class="text-warning">términos y condiciones</a>
                                        </label>
                                        <div class="invalid-feedback">Debe aceptar los términos y condiciones.</div>
                                    </div>

                                    <button type="submit" class="btn btn-warning w-100 mb-3">
                                        <span class="btn-text">Registrarse</span>
                                        <span class="loading d-none"></span>
                                    </button>
                                </form>

                                <div class="text-center">
                                    <p class="mb-0">¿Ya tienes cuenta? <a href="login.php" class="text-warning">Iniciar
                                            Sesión</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE FEEDBACK -->
    <div class="modal fade" id="modalFeedback" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modal-title" class="modal-title">Título</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="modal-body">
                    Mensaje del sistema
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnClose" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>