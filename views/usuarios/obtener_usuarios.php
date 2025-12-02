<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$idUsuario = $_SESSION['user']['id'] ?? null;

try {
    if (!$idUsuario || !Permisos::tienePermiso("Ver usuarios", $idUsuario)) {
        echo json_encode(['success' => false, 'message' => 'No tiene permisos para ver usuarios']);
        exit;
    }

    $stmt = $conn->query("
    SELECT
      u.id_usuario,
      u.nombre,
      u.apellido,
      u.email,
      u.telefono,
      u.dni,
      u.estado,
      r.nombre AS rol
    FROM usuario u
    LEFT JOIN roles_usuarios ru ON u.id_usuario = ru.id_usuario
    LEFT JOIN roles r ON ru.id_rol = r.id
    ORDER BY u.id_usuario ASC
    ");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Flags de permisos
    $permisoModificar = Permisos::tienePermiso("Modificar usuario", $idUsuario);
    $permisoEliminar = Permisos::tienePermiso("Eliminar usuario", $idUsuario);

    foreach ($usuarios as &$u) {
        $u['permiso_modificar'] = $permisoModificar;
        $u['permiso_eliminar'] = $permisoEliminar;
    }

    echo json_encode(['success' => true, 'usuarios' => $usuarios]);
} catch (Exception $e) {
    error_log("Error obtener_usuarios: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al obtener usuarios']);
}
