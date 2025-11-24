<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

try {
    // Último usuario registrado
    $stmtUsuario = $conn->prepare("
        SELECT nombre, apellido, email, fecha_alta
        FROM usuario
        ORDER BY fecha_alta DESC
        LIMIT 1
    ");
    $stmtUsuario->execute();
    $ultimoUsuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

    // Última clase creada
    $stmtClase = $conn->prepare("
        SELECT c.nombre_clase, c.dia_semana, u.nombre AS profesor
        FROM clase c
        LEFT JOIN usuario u ON u.id_usuario = c.profesor_id
        ORDER BY c.id_clase DESC
        LIMIT 1
    ");
    $stmtClase->execute();
    $ultimaClase = $stmtClase->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "ultimoUsuario" => $ultimoUsuario,
        "ultimaClase" => $ultimaClase
    ]);
} catch (Exception $e) {
    echo json_encode(["error" => "Error al obtener últimos registros", "detalle" => $e->getMessage()]);
}
