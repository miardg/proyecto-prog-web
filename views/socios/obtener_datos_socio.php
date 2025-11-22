<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../permisos.php';

header('Content-Type: application/json; charset=utf-8');

$idUsuario = $_SESSION['user']['id'] ?? null;

error_log("ID usuario: " . $idUsuario);

if (!$idUsuario || !Permisos::esRol('Socio', $idUsuario)) {
    error_log("El usuario no tiene rol Socio o el ID es inválido");
    echo json_encode(["error" => "No sos socio"]);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT s.id_socio, s.fecha_inscripcion, s.fecha_vencimiento, s.estado_membresia,
               u.nombre, u.apellido, u.email, u.telefono, u.dni,
               p.nombre AS plan
        FROM socio s
        JOIN usuario u ON u.id_usuario = s.id_usuario
        LEFT JOIN plan p ON p.id_plan = s.id_plan
        WHERE s.id_usuario = :id
    ");
    $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $socio = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($socio ?: []);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al obtener datos del socio", "detalle" => $e->getMessage()]);
}
