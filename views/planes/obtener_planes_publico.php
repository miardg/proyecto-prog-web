<?php
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $conn->prepare("
        SELECT id_plan, nombre, descripcion, precio, frecuencia_servicios
        FROM plan
        ORDER BY precio ASC
    ");
    $stmt->execute();
    $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($planes);
} catch (Throwable $e) {
    error_log("Error obtener_planes_publico: " . $e->getMessage());
    echo json_encode([]);
}
