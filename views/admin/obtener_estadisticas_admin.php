<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$idUsuario = $_SESSION['user']['id'] ?? null;

if (!$idUsuario || !Permisos::esRol('Administrador', $idUsuario)) {
    echo json_encode(["error" => "No sos administrador"]);
    exit;
}

try {
    // Datos del administrador
    $stmtAdmin = $conn->prepare("
        SELECT id_usuario, nombre, apellido, email, telefono, dni, fecha_alta, estado
        FROM usuario
        WHERE id_usuario = :id
    ");
    $stmtAdmin->bindValue(':id', $idUsuario, PDO::PARAM_INT);
    $stmtAdmin->execute();
    $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

    // Totales
    $totalUsuarios = $conn->query("SELECT COUNT(*) FROM usuario")->fetchColumn();
    $totalClases = $conn->query("SELECT COUNT(*) FROM clase")->fetchColumn();
    $totalPlanes = $conn->query("SELECT COUNT(*) FROM plan")->fetchColumn();

    // Último usuario
    $stmtUsuario = $conn->prepare("
        SELECT nombre, apellido, email, fecha_alta
        FROM usuario
        ORDER BY fecha_alta DESC
        LIMIT 1
    ");
    $stmtUsuario->execute();
    $ultimoUsuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

    // Última clase
    $stmtClase = $conn->prepare("
        SELECT c.nombre_clase, c.dia_semana, u.nombre AS profesor
        FROM clase c
        LEFT JOIN usuario u ON u.id_usuario = c.profesor_id
        ORDER BY c.id_clase DESC
        LIMIT 1
    ");
    $stmtClase->execute();
    $ultimaClase = $stmtClase->fetch(PDO::FETCH_ASSOC);

    // Último plan
    $stmtPlan = $conn->prepare("
        SELECT nombre, precio, frecuencia_servicios
        FROM plan
        ORDER BY id_plan DESC
        LIMIT 1
    ");
    $stmtPlan->execute();
    $ultimoPlan = $stmtPlan->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "admin" => $admin,
        "estadisticas" => [
            "usuarios" => $totalUsuarios,
            "clases" => $totalClases,
            "planes" => $totalPlanes
        ],
        "ultimoUsuario" => $ultimoUsuario,
        "ultimaClase" => $ultimaClase,
        "ultimoPlan" => $ultimoPlan
    ]);
} catch (Exception $e) {
    echo json_encode(["error" => "Error al obtener estadísticas", "detalle" => $e->getMessage()]);
}
