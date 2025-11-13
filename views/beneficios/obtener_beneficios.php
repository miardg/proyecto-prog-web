<?php
require_once __DIR__ . '/../../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idUsuario = $_SESSION['user']['id'] ?? null;

if (!$idUsuario) {
    echo json_encode([]);
    exit;
}

// Consulta: beneficios asociados al plan del socio
$stmt = $conn->prepare("
    SELECT b.id_beneficio, b.nombre, b.descripcion
    FROM beneficio b
    JOIN planbeneficio pb ON pb.id_beneficio = b.id_beneficio
    JOIN socio s ON s.id_plan = pb.id_plan
    WHERE s.id_usuario = :id
");
$stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
$stmt->execute();

$beneficios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Siempre devolver JSON, aunque esté vacío
echo json_encode($beneficios ?: []);
