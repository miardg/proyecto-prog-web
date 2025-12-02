<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_login();

header("Content-Type: application/json");

$idUsuario = $_SESSION['user']['id'];

try {
    $stmt = $conn->prepare("
        SELECT id_usuario, nombre, apellido, email, telefono, dni 
        FROM usuario 
        WHERE id_usuario = :id_usuario
    ");
    $stmt->bindParam(":id_usuario", $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        echo json_encode(["success" => true, "usuario" => $usuario]);
    } else {
        echo json_encode(["success" => false, "errores" => ["Usuario no encontrado"]]);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "errores" => ["Error al obtener datos", $e->getMessage()]
    ]);
}
