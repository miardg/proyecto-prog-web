<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';

require_login();

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

        <?php
        require_once __DIR__ . '/../config.php';
        require_once __DIR__ . '/../auth.php';
        require_once __DIR__ . '/../permisos.php';

        require_login();

        $idUsuario = $_SESSION['user']['id'];
        $nombre = $_SESSION['user']['name'];
        ?>
        <h1>bienvenido <?php echo htmlspecialchars($nombre); ?></h1>

        <?php if (Permisos::esRol('Administrador', $idUsuario)): ?>
            <?php include __DIR__ . '/partials/menu_admin.php'; ?>
        <?php elseif (Permisos::esRol('Profesor', $idUsuario)): ?>
            <?php include __DIR__ . '/partials/menu_profesor.php'; ?>
        <?php elseif (Permisos::esRol('Recepcionista', $idUsuario)): ?>
            <?php include __DIR__ . '/partials/menu_recepcionista.php'; ?>
        <?php elseif (Permisos::esRol('Socio', $idUsuario)): ?>
            <?php include __DIR__ . '/partials/menu_socio.php'; ?>
        <?php else: ?>
            <p>no hay contenido definido para este rol</p>
        <?php endif; ?>
    </div>

</body>

</html>