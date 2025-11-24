<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$dia = $_GET['dia'] ?? '';
$lugar = $_GET['lugar'] ?? '';

try {
    if ($dia === '' || $lugar === '') {
        echo json_encode([]);
        exit;
    }

    $stmt = $conn->prepare("
        SELECT hora_inicio, duracion_min
        FROM clase
        WHERE dia_semana = :dia
          AND lugar = :lugar
          AND estado = 'activa'
    ");
    $stmt->execute([':dia' => $dia, ':lugar' => $lugar]);

    $ocupados = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $inicio = substr($row['hora_inicio'], 0, 5); // 'HH:MM'
        $dur = (int) $row['duracion_min'];
        // calcular fin
        [$h, $m] = array_map('intval', explode(':', $inicio));
        $inicioSeg = $h * 3600 + $m * 60;
        $finSeg = $inicioSeg + $dur * 60;
        $fin = sprintf('%02d:%02d', intdiv($finSeg, 3600), intdiv($finSeg % 3600, 60));

        $ocupados[] = ['inicio' => $inicio, 'fin' => $fin];
    }

    echo json_encode($ocupados);
} catch (Throwable $e) {
    error_log('obtener_horarios_ocupados: ' . $e->getMessage());
    echo json_encode([]);
}
