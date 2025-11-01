<?php
require_once __DIR__ . '/config.php';

$email = "carlos.admin@kynetik.com";
$password = "123456"; // la clave que usaste para generar el hash

$stmt = $conn->prepare("SELECT * FROM usuario WHERE email = :email LIMIT 1");
$stmt->bindValue(':email', $email);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Usuario no encontrado";
} elseif ($user['estado'] !== 'activo') {
    echo "Usuario inactivo";
} elseif (!password_verify($password, $user['password_hash'])) {
    echo "Contraseña incorrecta";
} else {
    echo "Login correcto. Rol: " . $user['rol'];
}