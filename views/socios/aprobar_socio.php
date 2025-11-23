<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuarioSesion = $_SESSION['user']['id'] ?? null;

if (!Permisos::tienePermiso('Aprobar nuevos socios', $idUsuarioSesion)) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["success" => false, "error" => "No tenés permisos para aprobar socios"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$idUsuario = $data['idUsuario'] ?? null;
$plan = $data['plan'] ?? null;
$fechaAlta = $data['fechaAlta'] ?? null;
$fechaVencimiento = $data['fechaVencimiento'] ?? null;

if (!$idUsuario || !$plan || !$fechaAlta || !$fechaVencimiento) {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
    exit;
}

try {
    // Insertar en socio
    $stmt = $conn->prepare("
        INSERT INTO socio (id_usuario, id_plan, fecha_inscripcion, fecha_vencimiento)
        VALUES (:idUsuario, :plan, :fechaAlta, :fechaVencimiento)
    ");
    $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->bindValue(':plan', $plan, PDO::PARAM_INT);
    $stmt->bindValue(':fechaAlta', $fechaAlta);
    $stmt->bindValue(':fechaVencimiento', $fechaVencimiento);
    $stmt->execute();

    // Activar usuario
    $stmt = $conn->prepare("UPDATE usuario SET estado = 'activo' WHERE id_usuario = :idUsuario");
    $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["success" => true]);
} catch (Throwable $e) {
    echo json_encode(["success" => false, "error" => "Error al aprobar socio", "detalle" => $e->getMessage()]);
}
