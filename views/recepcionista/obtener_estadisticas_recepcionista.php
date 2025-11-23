<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$idUsuario = $_SESSION['user']['id'] ?? null;

if (!$idUsuario || !Permisos::esRol('Recepcionista', $idUsuario)) {
    echo json_encode(["error" => "No sos recepcionista"]);
    exit;
}

try {
    // Datos del recepcionista
    $stmtRecep = $conn->prepare("
        SELECT id_usuario, nombre, apellido, email, telefono, fecha_alta, estado
        FROM usuario
        WHERE id_usuario = :id
        LIMIT 1
    ");
    $stmtRecep->bindValue(':id', $idUsuario, PDO::PARAM_INT);
    $stmtRecep->execute();
    $recepcionista = $stmtRecep->fetch(PDO::FETCH_ASSOC);

    if (!$recepcionista) {
        echo json_encode(["error" => "No se encontraron datos del recepcionista"]);
        exit;
    }

    // Estadísticas
    $sociosActivos = (int) $conn->query("SELECT COUNT(*) FROM socio WHERE estado_membresia='activo'")->fetchColumn();
    $sociosPendientes = (int) $conn->query("SELECT COUNT(*) FROM socio WHERE estado_membresia='pendiente'")->fetchColumn();

    // Pagos del mes desde historialpagos
    $pagosMesStmt = $conn->query("
        SELECT COALESCE(SUM(monto),0)
        FROM historialpagos
        WHERE MONTH(fecha_pago)=MONTH(CURDATE())
          AND YEAR(fecha_pago)=YEAR(CURDATE())
    ");
    $pagosMes = (float) $pagosMesStmt->fetchColumn();

    // Deudas pendientes: socios cuya fecha_vencimiento ya pasó y estado_membresia != 'activo'
    $deudasStmt = $conn->query("
        SELECT COALESCE(SUM(p.precio),0)
        FROM socio s
        JOIN plan p ON s.id_plan = p.id_plan
        WHERE s.fecha_vencimiento < CURDATE()
          AND s.estado_membresia <> 'activo'
    ");
    $deudas = (float) $deudasStmt->fetchColumn();

    // Último socio aprobado (activo más reciente)
    $stmtUltSocio = $conn->query("
        SELECT u.nombre, u.apellido, u.email, s.fecha_inscripcion
        FROM socio s
        JOIN usuario u ON u.id_usuario = s.id_usuario
        WHERE s.estado_membresia='activo'
        ORDER BY s.fecha_inscripcion DESC
        LIMIT 1
    ");
    $ultimoSocio = $stmtUltSocio->fetch(PDO::FETCH_ASSOC) ?: [
        "nombre" => "–",
        "apellido" => "",
        "email" => "–",
        "fecha_inscripcion" => "–"
    ];

    // Último pago registrado
    $stmtUltPago = $conn->query("
        SELECT u.nombre, u.apellido, h.monto, h.fecha_pago
        FROM historialpagos h
        JOIN socio s ON s.id_socio = h.id_socio
        JOIN usuario u ON u.id_usuario = s.id_usuario
        ORDER BY h.fecha_pago DESC
        LIMIT 1
    ");
    $ultimoPagoRow = $stmtUltPago->fetch(PDO::FETCH_ASSOC);
    $ultimoPago = $ultimoPagoRow ? [
        "nombre" => $ultimoPagoRow["nombre"],
        "apellido" => $ultimoPagoRow["apellido"],
        "monto" => (float) $ultimoPagoRow["monto"],
        "fecha_pago" => $ultimoPagoRow["fecha_pago"]
    ] : [
        "nombre" => "–",
        "apellido" => "",
        "monto" => 0.0,
        "fecha_pago" => "–"
    ];

    echo json_encode([
        "recepcionista" => $recepcionista,
        "estadisticas" => [
            "socios_activos" => $sociosActivos,
            "socios_pendientes" => $sociosPendientes,
            "pagos_mes" => number_format($pagosMes, 2, ',', '.'),
            "deudas" => number_format($deudas, 2, ',', '.')
        ],
        "ultimoSocio" => $ultimoSocio,
        "ultimoPago" => $ultimoPago
    ]);
} catch (Throwable $e) {
    echo json_encode([
        "error" => "Error al obtener estadísticas del recepcionista",
        "detalle" => $e->getMessage()
    ]);
}
