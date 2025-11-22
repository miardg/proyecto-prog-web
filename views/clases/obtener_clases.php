<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$idUsuario = $_SESSION['user']['id'] ?? null;

try {
    // Obtener el id_socio del usuario
    $stmt = $conn->prepare("SELECT id_socio FROM socio WHERE id_usuario = :id");
    $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $socio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$socio) {
        echo json_encode(["error" => "Socio no encontrado"]);
        exit;
    }

    $idSocio = $socio['id_socio'];

    // Obtener clases activas en las que NO esté inscripto
    $stmt = $conn->prepare("
        SELECT c.id_clase, c.nombre_clase, c.tipo_actividad, c.dia_semana, c.hora_inicio,
               c.duracion_min, c.lugar, c.cupo_maximo
        FROM clase c
        WHERE c.estado = 'activa'
          AND c.id_clase NOT IN (
              SELECT id_clase FROM inscripcionclase WHERE id_socio = :socio
          )
        ORDER BY FIELD(c.dia_semana,
                       'Lunes','Martes','Miércoles','Jueves',
                       'Viernes','Sábado','Domingo'),
                 c.hora_inicio
    ");
    $stmt->bindValue(':socio', $idSocio, PDO::PARAM_INT);
    $stmt->execute();
    $clases = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($clases);
} catch (Exception $e) {
    echo json_encode(["error" => "Error al obtener clases", "detalle" => $e->getMessage()]);
}
