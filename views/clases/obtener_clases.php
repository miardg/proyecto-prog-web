<?php
require_once __DIR__ . '/../../config.php';

$stmt = $conn->query("
    SELECT id_clase, nombre_clase, dia_semana, hora_inicio, tipo_actividad
    FROM clase
    WHERE estado = 'activa'
");
$clases = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($clases);
