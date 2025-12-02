<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuarioSesion = $_SESSION['user']['id'] ?? null;

// Validar permisos
if (!Permisos::tienePermiso('Dar de baja socio', $idUsuarioSesion)) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["success" => false, "error" => "No tenés permisos para dar de baja socios"]);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

// Leer datos enviados por fetch
$data = json_decode(file_get_contents("php://input"), true);
$idSocio = $data['idSocio'] ?? null;

if (empty($idSocio)) {
    echo json_encode(["success" => false, "error" => "ID de socio inválido"]);
    exit;
}

try {
    // Buscar el usuario asociado al socio
    $stmt = $conn->prepare("SELECT id_usuario FROM socio WHERE id_socio = :idSocio");
    $stmt->bindValue(':idSocio', $idSocio, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo json_encode(["success" => false, "error" => "Socio no encontrado"]);
        exit;
    }

    $idUsuario = $usuario['id_usuario'];

    // Dar de baja al usuario (estado = inactivo)
    $stmt = $conn->prepare("UPDATE usuario SET estado = 'inactivo' WHERE id_usuario = :idUsuario");
    $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["success" => true]);
} catch (Throwable $e) {
    echo json_encode(["success" => false, "error" => "Error al dar de baja socio", "detalle" => $e->getMessage()]);
}
