<?php
function require_login() {
    if (empty($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
}

function require_role(array $roles) {
    require_login();
    $rol = $_SESSION['user']['rol'] ?? '';
    if (!in_array($rol, $roles, true)) {
        http_response_code(403);
        echo "Acceso denegado";
        exit;
    }
}
