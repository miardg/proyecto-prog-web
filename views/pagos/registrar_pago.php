<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuarioSesion = $_SESSION['user']['id'] ?? null;

if (!Permisos::tienePermiso('Registrar pago de cuota', $idUsuarioSesion)) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["success" => false, "error" => "No tenés permisos"]);
    exit;
}

header('Content-Type: application/json; charset=utf-8');
$data = json_decode(file_get_contents("php://input"), true);

$idSocio   = $data['idSocio']   ?? null;
$monto     = $data['monto']     ?? null;
$metodo    = $data['metodo']    ?? null;
$fechaPago = $data['fechaPago'] ?? null;
$periodo   = $data['periodo']   ?? null;

if (!$idSocio || !$monto || !$metodo || !$fechaPago || !$periodo) {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
    exit;
}

try {
    // Validar socio activo y obtener fecha de vencimiento actual
    $stmt = $conn->prepare("
        SELECT u.estado, s.fecha_vencimiento
        FROM socio s
        JOIN usuario u ON u.id_usuario = s.id_usuario
        WHERE s.id_socio = :idSocio
    ");
    $stmt->bindValue(':idSocio', $idSocio, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(["success" => false, "error" => "Socio no encontrado"]);
        exit;
    }

    if ($row['estado'] !== 'activo') {
        echo json_encode(["success" => false, "error" => "No se puede registrar pago de un socio inactivo"]);
        exit;
    }

    // Insertar en historialpagos
    $stmt = $conn->prepare("
        INSERT INTO historialpagos (id_socio, fecha_pago, monto, metodo_pago, periodo_correspondiente, registrado_por)
        VALUES (:idSocio, :fechaPago, :monto, :metodo, :periodo, :registradoPor)
    ");
    $stmt->execute([
        ':idSocio'       => $idSocio,
        ':fechaPago'     => $fechaPago,
        ':monto'         => $monto,
        ':metodo'        => $metodo,
        ':periodo'       => $periodo,
        ':registradoPor' => $idUsuarioSesion
    ]);

    // Calcular nuevo vencimiento:
    // - Si la fecha de vencimiento actual es mayor a la fecha de pago (vigente), extender desde la fecha de vencimiento.
    // - Si ya está vencida o no existe, extender desde la fecha de pago.
    $fechaVencimientoActual = $row['fecha_vencimiento'];
    if ($fechaVencimientoActual && strtotime($fechaVencimientoActual) > strtotime($fechaPago)) {
        $base = $fechaVencimientoActual;
    } else {
        $base = $fechaPago;
    }

    $nuevoVencimiento = date('Y-m-d', strtotime("+1 month", strtotime($base)));

    $stmt = $conn->prepare("UPDATE socio SET fecha_vencimiento = :nuevoVencimiento WHERE id_socio = :idSocio");
    $stmt->execute([
        ':nuevoVencimiento' => $nuevoVencimiento,
        ':idSocio'          => $idSocio
    ]);

    echo json_encode(["success" => true, "nuevo_vencimiento" => $nuevoVencimiento]);
} catch (Throwable $e) {
    echo json_encode([
        "success" => false,
        "error"   => "Error al registrar pago",
        "detalle" => $e->getMessage()
    ]);
}
