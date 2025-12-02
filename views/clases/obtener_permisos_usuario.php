<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'];

try {
    $permisos = Permisos::getPermisos($idUsuario);
    $lista = array_map(fn($p) => $p['nombre'], $permisos);
    echo json_encode($lista);
} catch (Throwable $e) {
    error_log("Error al obtener permisos: " . $e->getMessage());
    echo json_encode([]);
}
