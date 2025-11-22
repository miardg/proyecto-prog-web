<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../permisos.php';
require_login();

$nombre = $_SESSION['user']['name'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>KYNETIK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">

        <?php

        $idUsuario = $_SESSION['user']['id'];
        $nombre = $_SESSION['user']['name'];
        ?>
        <!-- Este es el include de la navbar que determina que mostrar segun los permisos -->
        <?php include __DIR__ . '/../includes/navbar_permisos.php'; ?>

        <!-- quiza habria que quitar esto y dejar un simple "saludo" al usuario manejandonos unicamente por la nav bar -->
        <?php if (Permisos::esRol('Administrador', $idUsuario)): ?>
            <?php include __DIR__ . '/partials/menu_admin.html'; ?>
            <script src="../assets/js/admin.js" defer></script>
        <?php elseif (Permisos::esRol('Profesor', $idUsuario)): ?>
            <?php include __DIR__ . '/partials/menu_profesor.php'; ?>
        <?php elseif (Permisos::esRol('Recepcionista', $idUsuario)): ?>
            <?php include __DIR__ . '/partials/menu_recepcionista.php'; ?>
        <?php elseif (Permisos::esRol('Socio', $idUsuario)): ?>
            <?php include __DIR__ . '/partials/menu_socio.html'; ?>
            <script src="../assets/js/socio.js" defer></script>
        <?php else: ?>
            <p>no hay contenido definido para este rol</p>
        <?php endif; ?>
    </div>

</body>

</html>