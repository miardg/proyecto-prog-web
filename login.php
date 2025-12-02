<?php
require_once __DIR__ . '/config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = "Debe ingresar email y contraseña";
    } else {
        $stmt = $conn->prepare("SELECT * FROM usuario WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['estado'] !== 'activo') {
            $error = "Usuario inexistente o inactivo";
        } elseif (!password_verify($password, $user['password_hash'])) {
            $error = "Credenciales inválidas";
        } else {
            $_SESSION['user'] = [
                'id' => (int) $user['id_usuario'],
                'name' => $user['nombre'] . ' ' . $user['apellido'],
                'email' => $user['email'],
            ];
            header("Location: views/dashboard.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar Sesión - KynetikGym</title>
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
                <div class="col-lg-6 col-xl-5">
                    <div class="auth-card">
                        <div class="row g-0">
                            <div class="col-12 p-5">
                                <div class="text-center mb-4">
                                    <i class="fas fa-dumbbell display-4 text-warning mb-3"></i>
                                    <h2 class="fw-bold">Bienvenido de vuelta</h2>
                                    <p class="text-muted">Inicia sesión en tu cuenta</p>
                                </div>
                                <?php
                                if (!empty($error)): ?>
                                    <div class="alert alert-danger text-center">
                                        <?= htmlspecialchars($error) ?>
                                    </div>
                                <?php endif; ?>
                                <form id="loginForm" method="POST" action="login.php">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required
                                            aria-describedby="emailHelp">
                                        <div class="invalid-feedback">
                                            Por favor ingrese un correo válido.
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            required>
                                        <div class="invalid-feedback">
                                            Ingrese su contraseña.
                                        </div>
                                    </div>

                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="recordar">
                                            <label class="form-check-label" for="recordar">
                                                Recordarme
                                            </label>
                                        </div>
                                        <a href="recuperar.php" class="text-warning">¿Olvidaste tu contraseña?</a>
                                    </div>

                                    <button type="submit" class="btn btn-warning w-100 mb-3">
                                        <span class="btn-text">Iniciar Sesión</span>
                                        <span class="loading d-none" aria-hidden="true"></span>
                                    </button>
                                </form>

                                <div class="text-center">
                                    <p class="mb-0">¿No tienes cuenta? <a href="registro.php"
                                            class="text-warning">Registrarse</a></p>
                                </div>

                                <div class="text-center mt-4">
                                    <a href="index.php" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Volver al inicio
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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