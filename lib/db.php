<?php
// lib/db.php
if (!function_exists('getPDO')) {
    function getPDO() {
        static $pdo = null;
        if ($pdo === null) {
            $dsn    = 'mysql:host=localhost;dbname=system_g6_db;charset=utf8mb4';
            $dbUser = 'root';
            $dbPass = '';
            try {
                $pdo = new PDO($dsn, $dbUser, $dbPass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("DB Connection failed: " . $e->getMessage());
            }
        }
        return $pdo;
    }
}
?>
