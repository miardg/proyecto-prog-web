<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_login();

header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
$idUsuario = $_SESSION['user']['id'];

$campo = $input["campo"] ?? "";
$errores = [];

try {
    if ($campo === "password") {
        $password_actual = trim($input["password_actual"] ?? "");
        $password_nueva = trim($input["password_nueva"] ?? "");

        // Verificar contraseña actual
        $stmt = $conn->prepare("SELECT password_hash FROM usuario WHERE id_usuario = :id");
        $stmt->bindParam(":id", $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $hash = $stmt->fetchColumn();

        if (!$hash || !password_verify($password_actual, $hash)) {
            $errores[] = "La contraseña actual es incorrecta";
        } elseif (strlen($password_nueva) < 6) {
            $errores[] = "La nueva contraseña debe tener al menos 6 caracteres";
        } else {
            $nuevoHash = password_hash($password_nueva, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE usuario SET password_hash = :hash WHERE id_usuario = :id");
            $update->bindParam(":hash", $nuevoHash, PDO::PARAM_STR);
            $update->bindParam(":id", $idUsuario, PDO::PARAM_INT);
            $update->execute();
        }
    } else {
        $valor = trim($input["valor"] ?? "");

        // Validaciones según campo
        switch ($campo) {
            case "nombre":
            case "apellido":
                if (strlen($valor) < 2 || strlen($valor) > 60) {
                    $errores[] = ucfirst($campo) . " debe tener entre 2 y 60 caracteres";
                }
                break;
            case "email":
                if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                    $errores[] = "Correo inválido";
                } else {
                    // Validar unicidad de email
                    $check = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE email = :email AND id_usuario <> :id");
                    $check->bindParam(":email", $valor, PDO::PARAM_STR);
                    $check->bindParam(":id", $idUsuario, PDO::PARAM_INT);
                    $check->execute();
                    if ($check->fetchColumn() > 0) {
                        $errores[] = "El correo ya está registrado por otro usuario";
                    }
                }
                break;
            case "telefono":
                if ($valor !== "" && !preg_match("/^[0-9+\-\s]{6,20}$/", $valor)) {
                    $errores[] = "Teléfono inválido";
                }
                break;
            default:
                $errores[] = "Campo no válido";
        }

        // Si no hay errores, actualizar
        if (empty($errores)) {
            $sql = "UPDATE usuario SET $campo = :valor WHERE id_usuario = :id";
            $update = $conn->prepare($sql);
            $update->bindParam(":valor", $valor, PDO::PARAM_STR);
            $update->bindParam(":id", $idUsuario, PDO::PARAM_INT);
            $update->execute();
        }
    }

    if (!empty($errores)) {
        echo json_encode(["success" => false, "errores" => $errores]);
    } else {
        echo json_encode(["success" => true]);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "errores" => ["Error al actualizar perfil", $e->getMessage()]
    ]);
}
