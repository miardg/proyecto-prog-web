<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$idUsuario = $_SESSION['user']['id'] ?? null;

try {
    if (!$idUsuario || !Permisos::tienePermiso("Modificar usuario", $idUsuario)) {
        throw new Exception("No tiene permisos para modificar usuarios");
    }

    $id = intval($_POST['id_usuario'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $rol = trim($_POST['rol'] ?? '');

    if ($id <= 0 || !$nombre || !$apellido || !$email || !$dni) {
        throw new Exception("Datos incompletos");
    }

    $stmt = $conn->prepare("UPDATE usuario SET nombre=:nombre, apellido=:apellido, email=:email, telefono=:telefono, dni=:dni WHERE id_usuario=:id");
    $stmt->execute([
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':email' => $email,
        ':telefono' => $telefono,
        ':dni' => $dni,
        ':id' => $id
    ]);

    if ($rol) {
        $stmtRol = $conn->prepare("UPDATE roles_usuarios SET id_rol = (SELECT id FROM roles WHERE nombre=:rol LIMIT 1) WHERE id_usuario=:id");
        $stmtRol->execute([':rol' => $rol, ':id' => $id]);
    }

    echo json_encode(['success' => true, 'message' => 'Usuario modificado correctamente']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
