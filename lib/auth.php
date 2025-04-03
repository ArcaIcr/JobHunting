<?php
// lib/auth.php

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['loggedInUser']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /pages/user/login.php");
        exit;
    }
}

function getUserRole() {
    if (isLoggedIn()) {
        // Assuming the session stores a 'role' (e.g., 'jobseeker' or 'employer')
        return $_SESSION['loggedInUser']['role'] ?? null;
    }
    return null;
}

function requireRole($role) {
    requireLogin();
    if (getUserRole() !== $role) {
        header("Location: /pages/home.php");
        exit;
    }
}
?>
