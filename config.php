<?php
session_start();
require_once __DIR__ . '/ConexionDb.php';

// conexión global
$conn = ConexionDb::connect();
