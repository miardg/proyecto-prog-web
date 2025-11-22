<?php
require_once __DIR__ . '/config.php';
$error = '';
//se verifica el codigo para que el usuario cambie la contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    if ($code == $_SESSION['reset_code']) {
        header("Location: reset.php");
        exit;
    } else {
        $error = "Código incorrecto";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Código - KynetikGym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body>
<div class="auth-container d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-xl-5">
                <div class="auth-card p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-key display-4 text-warning mb-3"></i>
                        <h2 class="fw-bold">Verificar Código</h2>
                        <p class="text-muted">Ingresa el código que recibiste por correo</p>
                    </div>
                    <?php if ($error): ?>
                        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="POST" action="verificar.php">
                        <div class="mb-3">
                            <label for="code" class="form-label">Código</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Verificar</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="recuperar.php" class="text-warning">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
