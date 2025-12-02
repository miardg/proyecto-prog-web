<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$idUsuario = $_SESSION['user']['id'] ?? null;
$idClase = $_POST['id_clase'] ?? null;

if (!$idUsuario || !$idClase) {
    echo json_encode(["exito" => false, "mensaje" => "Datos incompletos"]);
    exit;
}

if (!Permisos::tienePermiso("Cancelar inscripción a clase", $idUsuario)) {
    echo json_encode(["exito" => false, "mensaje" => "No tenés permiso para cancelar inscripciones"]);
    exit;
}

// Buscar el id_socio correspondiente al usuario
$stmt = $conn->prepare("SELECT id_socio FROM socio WHERE id_usuario = :id");
$stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
$stmt->execute();
$socio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$socio) {
    echo json_encode(["exito" => false, "mensaje" => "Socio no encontrado"]);
    exit;
}

$idSocio = $socio['id_socio'];

// Verificar si está inscripto
$stmt = $conn->prepare("SELECT COUNT(*) FROM inscripcionclase WHERE id_socio = :socio AND id_clase = :clase");
$stmt->bindValue(':socio', $idSocio, PDO::PARAM_INT);
$stmt->bindValue(':clase', $idClase, PDO::PARAM_INT);
$stmt->execute();
$inscripto = $stmt->fetchColumn();

if ($inscripto == 0) {
    echo json_encode(["exito" => false, "mensaje" => "No estás inscripto en esta clase"]);
    exit;
}

// Eliminar inscripción
$stmt = $conn->prepare("DELETE FROM inscripcionclase WHERE id_socio = :socio AND id_clase = :clase");
$stmt->bindValue(':socio', $idSocio, PDO::PARAM_INT);
$stmt->bindValue(':clase', $idClase, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo json_encode(["exito" => true]);
} else {
    echo json_encode(["exito" => false, "mensaje" => "No se pudo cancelar la inscripción"]);
}
