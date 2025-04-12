<?php
// lib/models/user_model.php

require_once __DIR__ . '/../db.php';

function getAllUsers() {
    $pdo = getPDO();
    // Only select columns that exist in your table
    $sql = "SELECT id, username, email, role, created_at, avatar FROM users";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createUser($username, $email, $role, $password = 'password123') {
    // You can handle hashing of the default password here or require it as a param
    $pdo = getPDO();
    // Insert only the columns that exist
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, role, password) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$username, $email, $role, $hashedPassword]);
}

function updateUser($userId, $username, $email, $role) {
    $pdo = getPDO();
    $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$username, $email, $role, $userId]);
}

function deleteUser($userId) {
    $pdo = getPDO();
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$userId]);
}

// Additional functions for updating password, avatar, etc., as needed.


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