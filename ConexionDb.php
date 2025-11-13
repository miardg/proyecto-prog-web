<?php
class ConexionDb {
    private static $conn = null;

    public static function connect() {
        if (self::$conn === null) {
            $host = "localhost";
            $dbname = "kynetik";
            $user = "root";
            $pass = "root";

            try {
                self::$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
