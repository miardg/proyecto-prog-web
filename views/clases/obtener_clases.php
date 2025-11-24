<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$idUsuario = $_SESSION['user']['id'] ?? null;

try {
  if (!$idUsuario || !Permisos::tienePermiso("Ver clases", $idUsuario)) {
    echo json_encode(["clases" => [], "esSocio" => false, "puedeModificar" => false]);
    exit;
  }

  // nos fijamos si es socio
  $stmt = $conn->prepare("SELECT id_socio FROM socio WHERE id_usuario = :id");
  $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
  $stmt->execute();
  $socio = $stmt->fetch(PDO::FETCH_ASSOC);
  $esSocio = $socio !== false;

  $puedeModificar = Permisos::tienePermiso("Modificar clases", $idUsuario);

  if ($esSocio) {
    $idSocio = (int) $socio['id_socio'];

    $sql = "
    SELECT c.id_clase, c.nombre_clase, c.tipo_actividad, c.dia_semana, c.hora_inicio,
           c.duracion_min, c.lugar, c.cupo_maximo, c.estado,
           c.profesor_id,
           IFNULL(CONCAT(u.nombre, ' ', u.apellido), 'Sin asignar') AS profesor
    FROM clase c
    LEFT JOIN inscripcionclase i
      ON i.id_clase = c.id_clase AND i.id_socio = :socio
    LEFT JOIN usuario u
      ON u.id_usuario = c.profesor_id
    WHERE c.estado = 'activa'
      AND i.id_clase IS NULL
    ORDER BY FIELD(c.dia_semana,
                   'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'),
             c.hora_inicio
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':socio', $idSocio, PDO::PARAM_INT);
  } else {
    $sql = "
    SELECT c.id_clase, c.nombre_clase, c.tipo_actividad, c.dia_semana, c.hora_inicio,
           c.duracion_min, c.lugar, c.cupo_maximo, c.estado,
           c.profesor_id,
           IFNULL(CONCAT(u.nombre, ' ', u.apellido), 'Sin asignar') AS profesor
    FROM clase c
    LEFT JOIN usuario u
      ON u.id_usuario = c.profesor_id
    WHERE c.estado IN ('activa', 'cancelada')
    ORDER BY FIELD(c.dia_semana,
                   'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'),
             c.hora_inicio
    ";
    $stmt = $conn->prepare($sql);
  }

  $stmt->execute();
  $clases = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode([
    "clases" => $clases,
    "esSocio" => $esSocio,
    "puedeModificar" => $puedeModificar
  ]);
} catch (Throwable $e) {
  error_log("Error obtener_clases: " . $e->getMessage());
  echo json_encode(["clases" => [], "esSocio" => false, "puedeModificar" => false, "error" => "Error al obtener clases"]);
}
