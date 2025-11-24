<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido");
    }

    $idUsuario = $_SESSION['user']['id'] ?? null;
    if (!$idUsuario || !Permisos::tienePermiso("Crear usuario", $idUsuario)) {
        throw new Exception("No tiene permisos para crear usuarios");
    }

    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $rolNombre = trim($_POST['rol'] ?? '');

    if ($nombre === '' || !preg_match("/^[a-zA-ZÀ-ÿ\s]{2,50}$/", $nombre)) {
        throw new Exception("Nombre inválido");
    }
    if ($apellido === '' || !preg_match("/^[a-zA-ZÀ-ÿ\s]{2,50}$/", $apellido)) {
        throw new Exception("Apellido inválido");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Email inválido");
    }
    if (!preg_match("/^[0-9]{7,15}$/", $telefono)) {
        throw new Exception("Teléfono inválido");
    }
    if (!preg_match("/^[0-9]{7,10}$/", $dni)) {
        throw new Exception("DNI inválido");
    }
    if (strlen($password) < 6) {
        throw new Exception("La contraseña debe tener al menos 6 caracteres");
    }
    if ($rolNombre === '') {
        throw new Exception("Rol inválido");
    }
    $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM roles WHERE LOWER(nombre)=LOWER(:rol)");
    $stmtCheck->execute([':rol' => $rolNombre]);
    if ($stmtCheck->fetchColumn() == 0) {
        throw new Exception("Rol inexistente");
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar usuario
    $stmt = $conn->prepare("
            INSERT INTO usuario (nombre, apellido, email, telefono, dni, password_hash, fecha_alta, estado)
            VALUES (:nombre, :apellido, :email, :telefono, :dni, :password_hash, NOW(), 'activo')
        ");
    $stmt->execute([
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':email' => $email,
        ':telefono' => $telefono,
        ':dni' => $dni,
        ':password_hash' => $passwordHash
    ]);

    $nuevoId = $conn->lastInsertId();

    // Asociar rol
    $stmtRol = $conn->prepare("
    INSERT INTO roles_usuarios (id_usuario, id_rol)
    VALUES (:idUsuario, (SELECT id FROM roles WHERE nombre=:rol LIMIT 1))
");
    $stmtRol->execute([
        ':idUsuario' => $nuevoId,
        ':rol' => $_POST['rol']
    ]);

    $response['success'] = true;
    $response['message'] = "Usuario creado correctamente";
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
} catch (PDOException $e) {
    $response['success'] = false;
    $response['message'] = "Error al crear usuario";
    error_log("Error crear usuario: " . $e->getMessage());
}

echo json_encode($response);