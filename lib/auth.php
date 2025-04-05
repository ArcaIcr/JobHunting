<?php
// lib/auth.php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
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
        // Normalize the role: trim and convert to lowercase.
        return strtolower(trim($_SESSION['loggedInUser']['role'] ?? ''));
    }
    return null;
}

function requireRole($role) {
    requireLogin();
    $currentRole = getUserRole();
    $requiredRole = strtolower(trim($role));
    
    if ($currentRole !== $requiredRole) {
        // If role mismatch, redirect based on the current role.
        if ($currentRole === 'employer') {
            header("Location: /pages/dashboard/employer/index.php");
        } elseif ($currentRole === 'jobseeker') {
            header("Location: /pages/dashboard/jobseeker/index.php");
        } else {
            header("Location: /pages/user/login.php");
        }
        exit;
    }
}
?>
