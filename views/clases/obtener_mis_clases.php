<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$idUsuario = $_SESSION['user']['id'];

try {
    $stmt = $conn->prepare("
        SELECT c.id_clase, c.nombre_clase, c.tipo_actividad, c.dia_semana, c.hora_inicio,
               c.duracion_min, c.lugar
        FROM clase c
        INNER JOIN inscripcionclase i ON i.id_clase = c.id_clase
        INNER JOIN socio s ON s.id_socio = i.id_socio
        WHERE s.id_usuario = :id
          AND c.estado = 'activa'
        ORDER BY FIELD(c.dia_semana,
                       'Lunes','Martes','Miércoles','Jueves',
                       'Viernes','Sábado','Domingo'),
                 c.hora_inicio
    ");
    $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $clases = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($clases);
} catch (Exception $e) {
    echo json_encode(["error" => "Error al obtener tus clases", "detalle" => $e->getMessage()]);
}
