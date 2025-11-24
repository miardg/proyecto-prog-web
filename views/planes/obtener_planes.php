<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$idUsuario = $_SESSION['user']['id'] ?? null;

try {
    error_log("ID de sesión: " . $idUsuario);

    if (!$idUsuario || !Permisos::tienePermiso("Ver planes", $idUsuario)) {
        echo json_encode(["planes" => [], "puedeModificar" => false]);
        exit;
    }

    $puedeModificar = Permisos::tienePermiso("Modificar planes", $idUsuario);

    $stmt = $conn->prepare("
        SELECT id_plan, nombre, descripcion, precio, frecuencia_servicios
        FROM plan
        ORDER BY precio ASC
    ");
    $stmt->execute();
    $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "planes" => $planes,
        "puedeModificar" => $puedeModificar
    ]);
} catch (Throwable $e) {
    error_log("Error obtener_planes: " . $e->getMessage());
    echo json_encode([
        "planes" => [],
        "puedeModificar" => false,
        "error" => "Error al obtener planes"
    ]);
}
