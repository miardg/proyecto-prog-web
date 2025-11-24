<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'] ?? null;

// Permisos: al menos uno
if (
    !Permisos::tienePermiso('Aprobar nuevos socios', $idUsuario) &&
    !Permisos::tienePermiso('Asignar plan a un socio', $idUsuario)
) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["error" => "No tenés permisos para ver pendientes de aprobación"]);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$busqueda = $_GET['busqueda'] ?? '';

try {
    // Consulta: usuarios inactivos, con rol 'socio', sin registro en tabla socio
    $sql = "
    SELECT 
        u.id_usuario,
        u.nombre,
        u.apellido,
        u.email,
        u.fecha_alta
    FROM usuario u
    INNER JOIN roles_usuarios ru ON ru.id_usuario = u.id_usuario
    INNER JOIN roles r ON r.id = ru.id_rol
    LEFT JOIN socio s ON s.id_usuario = u.id_usuario
    WHERE u.estado = 'inactivo'
      AND r.nombre = 'socio'
      AND s.id_socio IS NULL
";

    if (!empty($busqueda)) {
        $sql .= " AND (u.nombre LIKE :busqueda OR u.apellido LIKE :busqueda OR u.email LIKE :busqueda)";
    }

    $stmt = $conn->prepare($sql);

    if (!empty($busqueda)) {
        $stmt->bindValue(':busqueda', "%$busqueda%", PDO::PARAM_STR);
    }

    $stmt->execute();
    $pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($pendientes);
} catch (Throwable $e) {
    echo json_encode(["error" => "Error al obtener pendientes", "detalle" => $e->getMessage()]);
}
