<?php
// pages/user/login.php

session_start();
include '../../config/config.php';  // Your database configuration
require_once __DIR__ . '/../../lib/auth.php';

// Retrieve any error message from a previous failed login attempt.
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

// Process the login submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data safely using the null coalescing operator.
    // Note: The input names are now 'username' and 'password' as per the form.
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Ensure both fields are not empty.
    if ($username === '' || $password === '') {
        $_SESSION['error'] = 'Username and Password are required.';
        header("Location: /pages/user/login.php");
        exit;
    }
    
    // Prepare the SQL query to fetch the user by username.
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        // If exactly one user is found:
        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify the password using password_verify
            if (password_verify($password, $user['password'])) {
                // Normalize role safely.
                $userRole = strtolower(trim($user['role'] ?? ''));
                // If the user trying to log in via this page is an admin,
                // set an error because admins should use the admin login page.
                if ($userRole === 'admin') {
                    $_SESSION['error'] = 'Please use the admin login page for admin access.';
                    header("Location: /pages/user/login.php");
                    exit;
                }
                
                // For non-admin roles (assume 'jobseeker' or 'employer'),
                // save the user data into the session.
                $_SESSION['loggedInUser'] = $user;
                
                // Redirect based on role.
                if ($userRole === 'employer') {
                    header("Location: /pages/dashboard/employer/index.php");
                    exit;
                } elseif ($userRole === 'jobseeker') {
                    header("Location: /pages/dashboard/jobseeker/index.php");
                    exit;
                } else {
                    $_SESSION['error'] = 'Invalid user role.';
                    header("Location: /pages/user/login.php");
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Incorrect password. Please try again.';
                header("Location: /pages/user/login.php");
                exit;
            }
        } else {
            $_SESSION['error'] = 'User not found! Please check your credentials.';
            header("Location: /pages/user/login.php");
            exit;
        }
    } else {
        $_SESSION['error'] = 'An error occurred. Please try again later.';
        header("Location: /pages/user/login.php");
        exit;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Trabaho Nasipit</title>
  <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>
  <?php include '../../components/header.php'; ?>
  <div class="login-container">
    <h1>Log in</h1>
    <?php if (!empty($error)): ?>
      <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form id="loginForm" method="post" action="login.php">
      <div class="input-group">
        <input type="text" name="username" id="username" placeholder="Username" required>
      </div>
      <div class="input-group">
        <input type="password" name="password" id="password" placeholder="Password" required>
        <span class="toggle-password" onclick="togglePassword('password')">👁</span>
      </div>
      <div class="options">
        <input type="checkbox" id="rememberMe">
        <label for="rememberMe">Remember Me</label>
      </div>
      <button type="submit">Log in</button>
    </form>
    <p><a href="/pages/home/home.php">Return to Home</a></p>
  </div>
  <script>
    function togglePassword(fieldId) {
      var field = document.getElementById(fieldId);
      field.type = (field.type === "password") ? "text" : "password";
    }
  </script>
</body>
</html>
