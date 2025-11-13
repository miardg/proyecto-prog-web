<?php
require_once __DIR__ . '/../../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idUsuario = $_SESSION['user']['id'] ?? null;
$idBeneficio = $_POST['id_beneficio'] ?? null;
$accion = $_POST['accion'] ?? null;

if (!$idUsuario || !$idBeneficio || !$accion) {
    echo json_encode(["mensaje" => "Datos incompletos"]);
    exit;
}

// buscar socio vinculado al usuario
$stmt = $conn->prepare("SELECT id_socio FROM socio WHERE id_usuario = :id");
$stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
$stmt->execute();
$socio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$socio) {
    echo json_encode(["mensaje" => "No sos socio"]);
    exit;
}

// procesar canje o descanje
if ($accion === "canjear") {
    // verificar si ya fue canjeado
    $stmt = $conn->prepare("
        SELECT COUNT(*) FROM canje_beneficio
        WHERE id_socio = :socio AND id_beneficio = :beneficio
    ");
    $stmt->bindValue(':socio', $socio['id_socio'], PDO::PARAM_INT);
    $stmt->bindValue(':beneficio', $idBeneficio, PDO::PARAM_INT);
    $stmt->execute();
    $yaCanjeado = $stmt->fetchColumn();

    if ($yaCanjeado > 0) {
        echo json_encode(["mensaje" => "Ya canjeaste este beneficio"]);
        exit;
    }

    // registrar canje
    $stmt = $conn->prepare("
        INSERT INTO canje_beneficio (id_socio, id_beneficio, fecha_canje, estado)
        VALUES (:socio, :beneficio, CURDATE(), 'canjeado')
    ");
    $stmt->bindValue(':socio', $socio['id_socio'], PDO::PARAM_INT);
    $stmt->bindValue(':beneficio', $idBeneficio, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["mensaje" => "Beneficio canjeado correctamente"]);
} else {
    // revertir canje
    $stmt = $conn->prepare("
        DELETE FROM canje_beneficio
        WHERE id_socio = :socio AND id_beneficio = :beneficio
    ");
    $stmt->bindValue(':socio', $socio['id_socio'], PDO::PARAM_INT);
    $stmt->bindValue(':beneficio', $idBeneficio, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["mensaje" => "Canje revertido"]);
}
