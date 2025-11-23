<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuarioSesion = $_SESSION['user']['id'] ?? null;

// Validar permisos
if (!Permisos::tienePermiso('Cambiar plan de socio', $idUsuarioSesion)) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["success" => false, "error" => "No tenés permisos para cambiar planes"]);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

// Leer datos enviados por fetch
$data = json_decode(file_get_contents("php://input"), true);
$idSocio = $data['idSocio'] ?? null;
$nuevoPlan = $data['nuevoPlan'] ?? null;

if (empty($idSocio) || empty($nuevoPlan)) {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
    exit;
}

try {
    // Verificar que el socio existe y está activo
    $stmt = $conn->prepare("
        SELECT s.id_socio, u.estado 
        FROM socio s
        JOIN usuario u ON u.id_usuario = s.id_usuario
        WHERE s.id_socio = :idSocio
    ");
    $stmt->bindValue(':idSocio', $idSocio, PDO::PARAM_INT);
    $stmt->execute();
    $socio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$socio) {
        echo json_encode(["success" => false, "error" => "Socio no encontrado"]);
        exit;
    }

    if ($socio['estado'] !== 'activo') {
        echo json_encode(["success" => false, "error" => "No se puede cambiar el plan de un socio inactivo"]);
        exit;
    }

    // Actualizar el plan
    $stmt = $conn->prepare("UPDATE socio SET id_plan = :nuevoPlan WHERE id_socio = :idSocio");
    $stmt->bindValue(':nuevoPlan', $nuevoPlan, PDO::PARAM_INT);
    $stmt->bindValue(':idSocio', $idSocio, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["success" => true]);
} catch (Throwable $e) {
    echo json_encode(["success" => false, "error" => "Error al cambiar plan", "detalle" => $e->getMessage()]);
}
