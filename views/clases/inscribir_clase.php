<?php
require_once __DIR__ . '/../../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$idUsuario = $_SESSION['user']['id'] ?? null;
$idClase = $_POST['id_clase'] ?? null;

if (!$idUsuario || !$idClase) {
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

// verificar si ya está inscripto
$stmt = $conn->prepare("
    SELECT COUNT(*) FROM inscripcionclase
    WHERE id_clase = :clase AND id_socio = :socio
");
$stmt->bindValue(':clase', $idClase, PDO::PARAM_INT);
$stmt->bindValue(':socio', $socio['id_socio'], PDO::PARAM_INT);
$stmt->execute();
$yaInscripto = $stmt->fetchColumn();

if ($yaInscripto > 0) {
    echo json_encode(["mensaje" => "Ya estás inscripto en esta clase"]);
    exit;
}

// inscribir al socio
$stmt = $conn->prepare("
    INSERT INTO inscripcionclase (id_clase, id_socio, fecha_inscripcion)
    VALUES (:clase, :socio, CURDATE())
");
$stmt->bindValue(':clase', $idClase, PDO::PARAM_INT);
$stmt->bindValue(':socio', $socio['id_socio'], PDO::PARAM_INT);
$stmt->execute();

echo json_encode(["mensaje" => "Inscripción realizada con éxito"]);
