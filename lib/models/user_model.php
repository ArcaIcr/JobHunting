<?php
// lib/models/user_model.php

require_once __DIR__ . '/../db.php';

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


function updateUserAvatar($userId, $avatarFilename) {
    $pdo = getPDO();
    $sql = "UPDATE users SET avatar = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$avatarFilename, $userId]);
}