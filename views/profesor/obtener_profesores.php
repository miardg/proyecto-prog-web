<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $conn->prepare("
        SELECT u.id_usuario, u.nombre, u.apellido
        FROM usuario u
        JOIN roles_usuarios ru ON ru.id_usuario = u.id_usuario
        WHERE ru.id_rol = 3 -- ID del rol 'Profesor'
          AND u.estado = 'activo'
        ORDER BY u.nombre, u.apellido
    ");
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener profesores']);
}
