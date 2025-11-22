<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de clases</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/clases_gestion.js" defer></script>
</head>


<body>
    <?php include __DIR__ . '/../../includes/navbar_permisos.php'; ?>
    <div class="container mt-4">
        <h2 class="mb-4">Todas las clases activas</h2>
        <div id="tablaClasesAdmin" class="table-responsive"></div>
    </div>
</body>

</html>