<?php
// adminlogin_process.php

session_start();
include '../../config/config.php'; // Adjust path if needed
require_once __DIR__ . '/../../lib/auth.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['user_email'] ?? '');
    $password = trim($_POST['user_pass'] ?? '');

    if ($username === '' || $password === '') {
        $_SESSION['error'] = 'Username and Password are required.';
        header("Location: /pages/user/adminlogin.php");
        exit;
    }

    // Prepare and execute the query to get the user record from DB.
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify the password using the built-in password_verify()
            if (password_verify($password, $user['password'])) {
                // Check if this user is admin
                $role = strtolower(trim($user['role'] ?? ''));
                if ($role === 'admin') {
                    $_SESSION['loggedInUser'] = $user;
                    header("Location: /pages/dashboard/admin/index.php");
                    exit;
                } else {
                    $_SESSION['error'] = 'This account is not authorized as admin.';
                }
            } else {
                $_SESSION['error'] = 'Incorrect password. Please try again.';
            }
        } else {
            $_SESSION['error'] = 'User not found! Please check your credentials.';
        }
    } else {
        $_SESSION['error'] = 'An error occurred. Please try again later.';
    }

    $stmt->close();
    header("Location: /pages/user/adminlogin.php");
    exit;
} else {
    // If accessed without POST data, redirect back to adminlogin
    header("Location: /pages/user/adminlogin.php");
    exit;
}
?>
