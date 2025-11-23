<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'] ?? null;

if (!Permisos::tienePermiso('Registrar pago de cuota', $idUsuario)) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["error" => "Sin permisos"]);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$busqueda = $_GET['busqueda'] ?? '';

try {
    //se una .= para ir concatenando la consulta y poder validar si la busqueda llego con datos o vacia
    $sql = "
        SELECT 
            s.id_socio,
            u.nombre,
            u.apellido,
            u.email,
            p.nombre AS plan,
            s.fecha_inscripcion,
            s.fecha_vencimiento,
            (SELECT MAX(hp.fecha_pago) FROM historialpagos hp WHERE hp.id_socio = s.id_socio) AS ultimo_pago,
            CASE 
                WHEN s.fecha_vencimiento IS NULL THEN 'sin vencimiento'
                WHEN s.fecha_vencimiento >= CURDATE() THEN 'al día'
                ELSE 'vencida'
            END AS estado_cuota
        FROM socio s
        JOIN usuario u ON u.id_usuario = s.id_usuario
        JOIN plan p ON p.id_plan = s.id_plan
        WHERE u.estado = 'activo'
    ";

    if (!empty($busqueda)) {
        $sql .= " AND (u.nombre LIKE :q OR u.apellido LIKE :q OR u.email LIKE :q)";
    }

    $sql .= " ORDER BY 
    CASE 
        WHEN s.fecha_vencimiento IS NULL THEN 2   -- sin vencimiento al final
        WHEN s.fecha_vencimiento < CURDATE() THEN 0  -- vencida primero
        ELSE 1  -- al día en el medio
    END,
    u.nombre ASC";

    $stmt = $conn->prepare($sql);
    if (!empty($busqueda)) {
        $stmt->bindValue(':q', "%$busqueda%", PDO::PARAM_STR);
    }
    $stmt->execute();
    $socios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($socios);
} catch (Throwable $e) {
    echo json_encode(["error" => "Error al obtener socios para pago", "detalle" => $e->getMessage()]);
}
