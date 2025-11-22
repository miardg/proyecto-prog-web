<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$idUsuario = $_SESSION['user']['id'] ?? null;

try {
    $stmt = $conn->prepare("
        SELECT p.id_plan, p.nombre, p.descripcion, p.precio, p.frecuencia_servicios
        FROM plan p
        JOIN socio s ON s.id_plan = p.id_plan
        WHERE s.id_usuario = :id
    ");
    $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $plan = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($plan ?: []);
} catch (Exception $e) {
    echo json_encode(["error" => "Error al obtener tu plan", "detalle" => $e->getMessage()]);
}
