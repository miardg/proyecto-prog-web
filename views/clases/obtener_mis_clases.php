<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$idUsuario = $_SESSION['user']['id'] ?? null;

try {
    if (!$idUsuario) {
        echo json_encode(["clases" => [], "error" => "No hay usuario en sesión"]);
        exit;
    }

    if (!Permisos::tienePermiso("Ver mis clases", $idUsuario)) {
        echo json_encode(["clases" => [], "error" => "No tenés permiso para ver tus clases"]);
        exit;
    }

    // Buscar socio
    $stmt = $conn->prepare("SELECT id_socio FROM socio WHERE id_usuario = :id");
    $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $socio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$socio) {
        echo json_encode(["clases" => [], "error" => "El usuario no es socio"]);
        exit;
    }

    $idSocio = (int) $socio['id_socio'];

    // Traer clases en las que está inscripto
    $sql = "
        SELECT c.id_clase, c.nombre_clase, c.tipo_actividad, c.dia_semana, c.hora_inicio,
               c.duracion_min, c.lugar, c.cupo_maximo, c.estado,
               IFNULL(CONCAT(u.nombre, ' ', u.apellido), 'Sin asignar') AS profesor
        FROM clase c
        INNER JOIN inscripcionclase i ON i.id_clase = c.id_clase
        LEFT JOIN usuario u ON u.id_usuario = c.profesor_id
        WHERE i.id_socio = :socio
          AND c.estado = 'activa'
        ORDER BY FIELD(c.dia_semana,
                       'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'),
                 c.hora_inicio
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':socio', $idSocio, PDO::PARAM_INT);
    $stmt->execute();
    $clases = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["clases" => $clases]);
} catch (Throwable $e) {
    error_log("Error obtener_mis_clases: " . $e->getMessage());
    echo json_encode(["clases" => [], "error" => "Error al obtener mis clases"]);
}
