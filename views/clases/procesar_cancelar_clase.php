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
    if (!$idUsuario || !Permisos::tienePermiso("Modificar clases", $idUsuario)) {
        throw new Exception("No tiene permisos para cancelar clases");
    }

    $idClase = intval($_POST['id_clase'] ?? 0);
    if ($idClase <= 0) {
        throw new Exception("ID de clase inválido");
    }

    $stmt = $conn->prepare("UPDATE clase SET estado = 'cancelada' WHERE id_clase = :id");
    $stmt->bindValue(':id', $idClase, PDO::PARAM_INT);
    $stmt->execute();

    $response['success'] = true;
    $response['message'] = "Clase cancelada correctamente";
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
