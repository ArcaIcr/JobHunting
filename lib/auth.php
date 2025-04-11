<?php
// lib/auth.php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if a user is logged in by verifying if 'loggedInUser' exists in the session.
 */
function isLoggedIn() {
    return isset($_SESSION['loggedInUser']);
}

/**
 * Safely retrieve and normalize the user's role.
 * This prevents null from being passed to trim() or strtolower().
 */
function getUserRole() {
    if (isLoggedIn()) {
        $role = $_SESSION['loggedInUser']['role'] ?? '';
        return strtolower(trim($role));
    }
    return null;
}

/**
 * Require that a user is logged in as an admin.
 * If not, redirect them to the admin login page.
 */
function requireAdminLogin() {
    if (!isLoggedIn() || getUserRole() !== 'admin') {
        header("Location: /pages/user/adminlogin.php");
        exit;
    }
}

/**
 * Require that a user is logged in for non‑admin pages.
 * If not, redirect them to the non‑admin login page.
 */
function requireUserLogin() {
    if (!isLoggedIn()) {
        header("Location: /pages/user/login.php");
        exit;
    }
}

/**
 * Require a specific role for access; otherwise, redirect to an appropriate page.
 * This function assumes the user is already logged in.
 */
function requireRole($role) {
    // We assume the caller has already ensured the user is logged in.
    $currentRole = getUserRole();
    $requiredRole = strtolower(trim($role));

    if ($currentRole !== $requiredRole) {
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
