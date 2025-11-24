<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido");
    }

    $idUsuario = $_SESSION['user']['id'] ?? null;
    if (!$idUsuario || !Permisos::tienePermiso("Eliminar usuario", $idUsuario)) {
        throw new Exception("No tiene permisos para inactivar usuarios");
    }

    $idInactivar = intval($_POST['id_usuario'] ?? 0);
    if ($idInactivar <= 0) {
        throw new Exception("ID inválido");
    }

    $stmt = $conn->prepare("UPDATE usuario SET estado = 'inactivo' WHERE id_usuario = :id");
    $stmt->execute([':id' => $idInactivar]);

    $response['success'] = true;
    $response['message'] = "Usuario inactivado correctamente";
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} catch (PDOException $e) {
    error_log("Error inactivar usuario: " . $e->getMessage());
    $response['message'] = "Error al inactivar usuario";
}

echo json_encode($response);
