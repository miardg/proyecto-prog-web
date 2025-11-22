<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $conn->prepare("
        SELECT c.id_clase, c.nombre_clase, c.tipo_actividad, c.dia_semana, c.hora_inicio, c.duracion_min,
               c.lugar, c.cupo_maximo, c.estado, u.nombre AS profesor
        FROM clase c
        LEFT JOIN usuario u ON c.profesor_id = u.id_usuario
        ORDER BY FIELD(c.dia_semana, 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'), c.hora_inicio
        LIMIT 0,25;
    ");
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Throwable $e) {
    error_log("Error en obtener_clases_admin.php: " . $e->getMessage());
    echo json_encode([]);
}
