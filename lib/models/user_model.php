<?php
// lib/models/user_model.php

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

function getUserById($userId) {
    $pdo = getPDO();
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateUserEmail($userId, $email) {
    $pdo = getPDO();
    $sql = "UPDATE users SET email = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$email, $userId]);
}

function updateUserPassword($userId, $hashedPassword) {
    $pdo = getPDO();
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$hashedPassword, $userId]);
}
