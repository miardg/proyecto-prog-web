<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function require_login() {
    if (empty($_SESSION['user'])) {
        header("Location: /proyecto-prog-web/login.php");
        exit;
    }
}

