<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'];
$nombre = $_SESSION['user']['name'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Planes disponibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/planes.js" defer></script>
</head>

<body>
    <div>
        <?php include __DIR__ . '/../../includes/navbar_permisos.php'; ?>
    </div>

    <div class="d-flex justify-content-center align-items-start min-vh-100 bg-light pt-5">
        <div class="card shadow-lg p-4" style="max-width: 900px; width: 100%;">
            <h2 class="titulo-panel mb-4 text-center">Planes disponibles</h2>
            <div id="listaPlanes"></div>
        </div>
    </div>
</body>

</html>