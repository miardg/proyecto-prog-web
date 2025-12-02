<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false, 'message' => '', 'field' => null];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception(json_encode(['field' => null, 'message' => 'Método no permitido']));
    }

    $idUsuario = $_SESSION['user']['id'] ?? null;
    if (!$idUsuario || !Permisos::tienePermiso("Modificar clases", $idUsuario)) {
        throw new Exception(json_encode(['field' => null, 'message' => 'No tiene permisos para modificar clases']));
    }

    $idClase = intval($_POST['id_clase'] ?? 0);
    $nombre = trim($_POST['nombre_clase'] ?? '');
    $tipo = trim($_POST['tipo_actividad'] ?? '');
    $dia = trim($_POST['dia_semana'] ?? '');
    $hora = trim($_POST['hora_inicio'] ?? '');
    $duracion = trim($_POST['duracion_min'] ?? '');
    $lugar = trim($_POST['lugar'] ?? '');
    $cupo = trim($_POST['cupo_maximo'] ?? '');
    $profesor = trim($_POST['profesor_id'] ?? '');

    if ($idClase <= 0 || $nombre === '' || $tipo === '' || $dia === '' || $hora === '' || $duracion === '' || $lugar === '' || $cupo === '' || $profesor === '') {
        throw new Exception(json_encode(['field' => null, 'message' => 'Todos los campos son obligatorios']));
    }
    if (!preg_match("/^[a-zA-ZÀ-ÿ\s]{2,50}$/", $nombre)) {
        throw new Exception(json_encode(['field' => 'nombre_clase', 'message' => 'Nombre de clase inválido (2–50 letras)']));
    }
    if (!preg_match("/^[a-zA-ZÀ-ÿ\s]{2,50}$/", $tipo)) {
        throw new Exception(json_encode(['field' => 'tipo_actividad', 'message' => 'Tipo de actividad inválido (2–50 letras)']));
    }
    if (!in_array($dia, ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'], true)) {
        throw new Exception(json_encode(['field' => 'dia_semana', 'message' => 'Día inválido']));
    }
    if (!preg_match("/^(?:[01]\d|2[0-3]):[0-5]\d$/", $hora)) {
        throw new Exception(json_encode(['field' => 'hora_inicio', 'message' => 'Hora inválida (formato HH:MM 24h)']));
    }
    if (!filter_var($duracion, FILTER_VALIDATE_INT, ["options" => ["min_range" => 15, "max_range" => 300]])) {
        throw new Exception(json_encode(['field' => 'duracion_min', 'message' => 'Duración inválida (15 a 300 minutos)']));
    }
    if (!preg_match("/^[a-zA-Z0-9À-ÿ\s]{2,100}$/", $lugar)) {
        throw new Exception(json_encode(['field' => 'lugar', 'message' => 'Lugar inválido (2–100 caracteres, alfanumérico)']));
    }
    if (!filter_var($cupo, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 100]])) {
        throw new Exception(json_encode(['field' => 'cupo_maximo', 'message' => 'Cupo inválido (1 a 100)']));
    }

    // validar solapamiento en PHP (excluimos la clase que se modifica)
    $stmtCheck = $conn->prepare("
        SELECT hora_inicio, duracion_min 
        FROM clase 
        WHERE dia_semana = :dia 
          AND lugar = :lugar 
          AND estado = 'activa'
          AND id_clase <> :idClase
    ");
    $stmtCheck->bindValue(':dia', $dia);
    $stmtCheck->bindValue(':lugar', $lugar);
    $stmtCheck->bindValue(':idClase', $idClase, PDO::PARAM_INT);
    $stmtCheck->execute();
    $clases = $stmtCheck->fetchAll(PDO::FETCH_ASSOC);

    $inicioNueva = strtotime($hora);
    $finNueva = $inicioNueva + ((int) $duracion * 60);

    foreach ($clases as $clase) {
        $inicioExistente = strtotime($clase['hora_inicio']);
        $finExistente = $inicioExistente + ((int) $clase['duracion_min'] * 60);

        if ($inicioNueva < $finExistente && $finNueva > $inicioExistente) {
            throw new Exception(json_encode([
                'field' => 'hora_inicio',
                'message' => 'Ya existe una clase activa que se superpone en ese horario y lugar'
            ]));
        }
    }

    // update
    $stmt = $conn->prepare("
        UPDATE clase
        SET nombre_clase = :nombre,
            tipo_actividad = :tipo,
            dia_semana = :dia,
            hora_inicio = :hora,
            duracion_min = :duracion,
            lugar = :lugar,
            profesor_id = :profesor,
            cupo_maximo = :cupo
        WHERE id_clase = :idClase
    ");
    $stmt->execute([
        ':nombre' => $nombre,
        ':tipo' => $tipo,
        ':dia' => $dia,
        ':hora' => $hora,
        ':duracion' => (int) $duracion,
        ':lugar' => $lugar,
        ':profesor' => (int) $profesor,
        ':cupo' => (int) $cupo,
        ':idClase' => $idClase
    ]);

    $response['success'] = true;
    $response['message'] = "Clase modificada correctamente";

} catch (Exception $e) {
    $decoded = json_decode($e->getMessage(), true);
    if (is_array($decoded)) {
        $response['success'] = false;
        $response['field'] = $decoded['field'];
        $response['message'] = $decoded['message'];
    } else {
        $response['success'] = false;
        $response['message'] = $e->getMessage();
    }
} catch (PDOException $e) {
    $response['success'] = false;
    $response['message'] = "Error al modificar la clase";
    error_log("Error modificar clase: " . $e->getMessage());
}

echo json_encode($response);
