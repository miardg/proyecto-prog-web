<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'] ?? null;

// Validar permisos
if (
    !Permisos::tienePermiso('Cambiar plan de socio', $idUsuario) &&
    !Permisos::tienePermiso('Dar de baja socio', $idUsuario)
) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["error" => "No tenés permisos para ver socios activos"]);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$busqueda = $_GET['busqueda'] ?? '';

try {
    $sql = "
        SELECT 
            s.id_socio,
            u.nombre,
            u.apellido,
            u.email,
            p.nombre AS plan,
            s.fecha_inscripcion,
            CASE 
                WHEN s.fecha_vencimiento < CURDATE() THEN 'Cuota vencida'
                ELSE 'Al día'
            END AS estado_cuota
        FROM socio s
        JOIN usuario u ON u.id_usuario = s.id_usuario
        JOIN plan p ON p.id_plan = s.id_plan
        WHERE u.estado = 'activo'
    ";

    // Si hay búsqueda, agregamos filtro
    if (!empty($busqueda)) {
        $sql .= " AND (u.nombre LIKE :busqueda OR u.apellido LIKE :busqueda OR u.email LIKE :busqueda OR p.nombre LIKE :busqueda)";
    }

    $stmt = $conn->prepare($sql);

    if (!empty($busqueda)) {
        $stmt->bindValue(':busqueda', "%$busqueda%", PDO::PARAM_STR);
    }

    $stmt->execute();
    $socios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($socios);
} catch (Throwable $e) {
    echo json_encode(["error" => "Error al obtener socios activos", "detalle" => $e->getMessage()]);
}
