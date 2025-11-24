<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_login();

$idProfesor = $_SESSION['user']['id'];

$diaMap = [
    'Lunes' => 1,
    'Martes' => 2,
    'Miércoles' => 3,
    'Jueves' => 4,
    'Viernes' => 5,
    'Sábado' => 6,
    'Domingo' => 7
];


//query que devuelve los inscriptos de una clase en particular para mostrar en la lista
if (isset($_GET['json']) && isset($_GET['id_clase']) && isset($_GET['fecha'])) {
    $idClase = intval($_GET['id_clase']);
    $fecha = $_GET['fecha'];


    $stmt = $conn->prepare("
        SELECT 
            s.id_socio, 
            CONCAT(u.nombre, ' ', u.apellido) AS nombre, 
            u.dni
        FROM inscripcionclase i
        INNER JOIN socio s ON s.id_socio = i.id_socio
        INNER JOIN usuario u ON u.id_usuario = s.id_usuario
        INNER JOIN clase c ON c.id_clase = i.id_clase
        WHERE i.id_clase = :id_clase
          AND u.estado = 'activo'
          AND s.estado_membresia = 'activa'
          AND c.estado = 'activa'
          AND DATE(i.fecha_inscripcion) <= :fecha
    ");
    $stmt->bindParam(':id_clase', $idClase, PDO::PARAM_INT);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->execute();
    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($alumnos);
    exit;
}

//crear las instancias de las clases a partir de la plantilla de la DB para mostrar en el calendario
$diaFiltro = $_GET['dia'] ?? null;
$soloHoy = ($diaFiltro === 'actual');
$semanasFuturas = $soloHoy ? 1 : 8;

$stmt = $conn->prepare("SELECT * FROM clase WHERE profesor_id = :id AND estado = 'activa'");
$stmt->bindParam(':id', $idProfesor, PDO::PARAM_INT);
$stmt->execute();
$plantillas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$eventos = [];

foreach ($plantillas as $clase) {
    $diaTexto = $clase['dia_semana'];
    if (!isset($diaMap[$diaTexto]))
        continue;

    $diaClase = $diaMap[$diaTexto];
    $hoy = (int) date('N');

    for ($i = 0; $i < $semanasFuturas; $i++) {
        $fechaClase = new DateTime();
        $offset = $diaClase - $hoy + ($i * 7);
        $fechaClase->modify("$offset days");

        if ($soloHoy && $fechaClase->format('Y-m-d') !== date('Y-m-d'))
            continue;

        $fechaStr = $fechaClase->format('Y-m-d');

        //chequear si la clase figura cancelada en la tabla de auditoria
        $stmtCancelada = $conn->prepare("
        SELECT COUNT(*) FROM clase_cancelada
        WHERE id_clase = :id_clase AND fecha = :fecha
    ");
        $stmtCancelada->execute([
            ':id_clase' => $clase['id_clase'],
            ':fecha' => $fechaStr
        ]);
        $cancelada = $stmtCancelada->fetchColumn();

        if ($cancelada) {
            continue;
        }

        //si no esta cancelada, la mostramos en el calendario
        $inicio = $fechaStr . 'T' . $clase['hora_inicio'];
        $duracion = (int) $clase['duracion_min'];
        $fin = clone $fechaClase;
        $fin->setTime(...explode(':', $clase['hora_inicio']));
        $fin->modify("+$duracion minutes");

        $eventos[] = [
            'title' => $clase['nombre_clase'] . ' (' . $clase['tipo_actividad'] . ')',
            'start' => $inicio,
            'end' => $fin->format('Y-m-d\TH:i:s'),
            'extendedProps' => [
                'id_clase' => $clase['id_clase'],
                'fecha_real' => $fechaClase->format('Y-m-d'),
                'lugar' => $clase['lugar'],
                'tipo' => $clase['tipo_actividad'],
                'duracion' => $clase['duracion_min']
            ]
        ];

    }
}


if (isset($_GET['json'])) {
    header('Content-Type: application/json');
    echo json_encode($eventos);
    exit;
}
