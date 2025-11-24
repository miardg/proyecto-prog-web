<?php
require_once __DIR__ . '/config.php';

$error = '';
$success = '';

if (!isset($_SESSION['reset_email'])) {
    header('Location: recuperar.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPass = $_POST['newPassword'] ?? '';
    $confirm = $_POST['confirmPassword'] ?? '';

    if ($newPass === '' || $confirm === '') {
        $error = "Debes completar ambos campos.";
    } elseif ($newPass !== $confirm) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($newPass) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        try {
            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuario SET password_hash = :hash WHERE email = :email AND estado = 'activo'");
            $stmt->execute([
                ':hash' => $hash,
                ':email' => $_SESSION['reset_email']
            ]);

            unset($_SESSION['reset_code'], $_SESSION['reset_email']);

            $success = "Contraseña actualizada correctamente. Ya puedes iniciar sesión.";
        } catch (Throwable $e) {
            $error = "Ocurrió un error al actualizar la contraseña.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resetear Contraseña - KynetikGym</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="assets/js/recuperar_contrasena.js" defer></script>
</head>

<body>
    <div class="auth-container d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-xl-5">
                    <div class="auth-card p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-lock display-4 text-warning mb-3"></i>
                            <h2 class="fw-bold">Nueva Contraseña</h2>
                            <p class="text-muted">Ingresa tu nueva contraseña</p>
                        </div>

                        <?php if ($error): ?>
                            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                        <?php elseif ($success): ?>
                            <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
                            <div class="text-center mt-3">
                                <a href="login.php" class="btn btn-warning">Ir al login</a>
                            </div>
                        <?php endif; ?>

                        <?php if (!$success): ?>
                            <form method="POST" action="reset.php" novalidate>
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">Nueva contraseña</label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" required
                                        minlength="6">
                                    <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Confirmar contraseña</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                        required>
                                    <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                                </div>

                                <button type="submit" class="btn btn-warning w-100">Cambiar contraseña</button>
                            </form>
                            <div class="text-center mt-3">
                                <a href="verificar.php" class="text-warning">Volver</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>