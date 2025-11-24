<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $conn->query("SELECT id, nombre FROM roles ORDER BY nombre ASC");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'roles' => $roles]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener roles']);
}
