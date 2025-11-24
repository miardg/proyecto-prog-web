<?php
require_once __DIR__ . '/config.php';
require __DIR__ . '/includes/PHPMailer/PHPMailer.php';
require __DIR__ . '/includes/PHPMailer/SMTP.php';
require __DIR__ . '/includes/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$success = '';
//obtiene el mail del usuario y verifica que exista, luego le envia por mail un codigo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE email = :email AND estado='activo' LIMIT 1");
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $error = "No existe un usuario activo con ese email";
    } else {
        $code = rand(100000, 999999); // código de 6 dígitos
        $_SESSION['reset_code'] = $code;
        $_SESSION['reset_email'] = $email;

        // enviar mail
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kynetikgym@gmail.com';
            //contraseña de aplicacion generada desde gmail
            $mail->Password = 'qguu eqjo xdon sjus';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('kynetikgym@gmail.com', 'KynetikGym');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body = "Tu código de recuperación es: <b>$code</b>";

            $mail->send();
            $success = "Se envió un código de recuperación a tu correo.";
        } catch (Exception $e) {
            $error = "Error al enviar el correo: " . $mail->ErrorInfo;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - KynetikGym</title>
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
                            <i class="fas fa-envelope display-4 text-warning mb-3"></i>
                            <h2 class="fw-bold">Recuperar Contraseña</h2>
                            <p class="text-muted">Ingresa tu correo para recibir un código</p>
                        </div>
                        <?php if ($error): ?>
                            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                        <?php elseif ($success): ?>
                            <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
                            <div class="text-center mt-3">
                                <a href="verificar.php" class="btn btn-warning">Ingresar código</a>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="recuperar.php">
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">Ingrese un correo válido.</div>
                            </div>
                            <button type="submit" class="btn btn-warning w-100">Enviar código</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="login.php" class="text-warning">Volver al login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>