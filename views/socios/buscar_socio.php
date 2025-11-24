<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_login();

$q = $_GET['q'] ?? '';

$stmt = $conn->prepare("
    SELECT s.id_socio, CONCAT(u.nombre, ' ', u.apellido) AS nombre, u.dni
    FROM socio s
    INNER JOIN usuario u ON u.id_usuario = s.id_usuario
    WHERE u.estado = 'activo'
      AND s.estado_membresia = 'activa'
      AND (u.nombre LIKE :q OR u.apellido LIKE :q OR u.dni LIKE :q)
    LIMIT 10
");

$likeQ = "%$q%";
$stmt->bindParam(':q', $likeQ, PDO::PARAM_STR);
$stmt->execute();

header('Content-Type: application/json');
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>