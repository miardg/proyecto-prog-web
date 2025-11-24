<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false, 'message' => '', 'field' => null];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception(json_encode(['field' => null, 'message' => 'Método no permitido']));
    }

    $idUsuario = $_SESSION['user']['id'] ?? null;
    if (!$idUsuario || !Permisos::tienePermiso("Modificar planes", $idUsuario)) {
        throw new Exception(json_encode(['field' => null, 'message' => 'No tiene permisos para modificar planes']));
    }

    // Sanitización
    $idPlan = intval($_POST['id_plan'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = trim($_POST['precio'] ?? '');
    $frecuencia = trim($_POST['frecuencia_servicios'] ?? '');

    // Validaciones
    if ($idPlan <= 0 || $nombre === '' || $descripcion === '' || $precio === '' || $frecuencia === '') {
        throw new Exception(json_encode(['field' => null, 'message' => 'Todos los campos son obligatorios']));
    }

    if (!preg_match("/^[a-zA-ZÀ-ÿ\s]{2,50}$/", $nombre)) {
        throw new Exception(json_encode(['field' => 'nombre', 'message' => 'Nombre inválido (2–50 letras)']));
    }

    if (strlen($descripcion) < 10 || strlen($descripcion) > 500) {
        throw new Exception(json_encode(['field' => 'descripcion', 'message' => 'Descripción inválida (10–500 caracteres)']));
    }

    if (!filter_var($precio, FILTER_VALIDATE_FLOAT) || $precio <= 0) {
        throw new Exception(json_encode(['field' => 'precio', 'message' => 'Precio inválido (número positivo)']));
    }

    if (!in_array($frecuencia, ['Mensual', 'Trimestral', 'Anual'], true)) {
        throw new Exception(json_encode(['field' => 'frecuencia_servicios', 'message' => 'Frecuencia inválida (Mensual, Trimestral o Anual)']));
    }

    // Actualización
    $stmt = $conn->prepare("
        UPDATE plan
        SET nombre = :nombre,
            descripcion = :descripcion,
            precio = :precio,
            frecuencia_servicios = :frecuencia
        WHERE id_plan = :idPlan
    ");
    $stmt->execute([
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':precio' => (float) $precio,
        ':frecuencia' => $frecuencia,
        ':idPlan' => $idPlan
    ]);

    $response['success'] = true;
    $response['message'] = "Plan modificado correctamente";

} catch (Exception $e) {
    $decoded = json_decode($e->getMessage(), true);
    if (is_array($decoded)) {
        $response['success'] = false;
        $response['field'] = $decoded['field'];
        $response['message'] = $decoded['message'];
    } else {
        $response['success'] = false;
        $response['message'] = $e->getMessage();
    }
} catch (PDOException $e) {
    $response['success'] = false;
    $response['message'] = "Error al modificar el plan";
    error_log("Error modificar plan: " . $e->getMessage());
}

echo json_encode($response);
