<?php
require_once __DIR__ . '/../../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idUsuario = $_SESSION['user']['id'] ?? null;
if (!$idUsuario) {
    echo json_encode(["error" => "Usuario no logueado"]);
    exit;
}

$stmt = $conn->prepare("
    SELECT p.nombre, s.estado_membresia, s.fecha_vencimiento
    FROM socio s
    JOIN plan p ON p.id_plan = s.id_plan
    WHERE s.id_usuario = :id
");
$stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
$stmt->execute();

$plan = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($plan ?: []);
