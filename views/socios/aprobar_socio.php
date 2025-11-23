<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuarioSesion = $_SESSION['user']['id'] ?? null;

if (!Permisos::tienePermiso('Aprobar nuevos socios', $idUsuarioSesion)) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["success" => false, "error" => "No tenés permisos para aprobar socios"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$idUsuario        = $data['idUsuario'] ?? null;
$plan             = $data['plan'] ?? null;
$fechaAlta        = $data['fechaAlta'] ?? null;
$fechaVencimiento = $data['fechaVencimiento'] ?? null;

// Nuevos datos del primer pago
$monto   = $data['monto'] ?? null;
$metodo  = $data['metodo'] ?? null;
$periodo = $data['periodo'] ?? null;

if (!$idUsuario || !$plan || !$fechaAlta || !$fechaVencimiento || !$monto || !$metodo || !$periodo) {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
    exit;
}

try {
    // Insertar en socio
    $stmt = $conn->prepare("
        INSERT INTO socio (id_usuario, id_plan, fecha_inscripcion, fecha_vencimiento)
        VALUES (:idUsuario, :plan, :fechaAlta, :fechaVencimiento)
    ");
    $stmt->execute([
        ':idUsuario'        => $idUsuario,
        ':plan'             => $plan,
        ':fechaAlta'        => $fechaAlta,
        ':fechaVencimiento' => $fechaVencimiento
    ]);

    $idSocio = $conn->lastInsertId();

    // Activar usuario
    $stmt = $conn->prepare("UPDATE usuario SET estado = 'activo' WHERE id_usuario = :idUsuario");
    $stmt->execute([':idUsuario' => $idUsuario]);

    // Registrar primer pago
    $stmt = $conn->prepare("
        INSERT INTO historialpagos (id_socio, fecha_pago, monto, metodo_pago, periodo_correspondiente, registrado_por)
        VALUES (:idSocio, :fechaPago, :monto, :metodo, :periodo, :registradoPor)
    ");
    $stmt->execute([
        ':idSocio'       => $idSocio,
        ':fechaPago'     => $fechaAlta, // la fecha de alta se toma como fecha del primer pago
        ':monto'         => $monto,
        ':metodo'        => $metodo,
        ':periodo'       => $periodo,
        ':registradoPor' => $idUsuarioSesion
    ]);

    echo json_encode(["success" => true]);
} catch (Throwable $e) {
    echo json_encode([
        "success" => false,
        "error"   => "Error al aprobar socio",
        "detalle" => $e->getMessage()
    ]);
}
