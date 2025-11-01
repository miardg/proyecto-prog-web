<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';

require_login();

$rol = $_SESSION['user']['rol'];
$nombre = $_SESSION['user']['name'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1>bienvenido <?php echo htmlspecialchars($nombre); ?></h1>
        <p>tu rol es: <?php echo htmlspecialchars($rol); ?></p>

        <?php
        // segun el rol se incluye el partial correspondiente
        switch ($rol) {
            case 'Administrador':
                include __DIR__ . '/partials/menu_admin.php';
                break;
            case 'Recepcionista':
                include __DIR__ . '/partials/menu_recepcionista.php';
                break;
            case 'Profesor':
                include __DIR__ . '/partials/menu_profesor.php';
                break;
            case 'Socio':
                include __DIR__ . '/partials/menu_socio.php';
                break;
            default:
                echo "<p>no hay contenido definido para este rol</p>";
        }
        ?>
    </div>

</body>

</html>