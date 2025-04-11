<?php
// adminlogin.php

// Start the session if it hasn't been started already.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the authentication library.
require_once __DIR__ . '/../../lib/auth.php';

// If a user is already logged in...
if (isLoggedIn()) {
    // ...and their role is admin, redirect them to the admin dashboard.
    if (getUserRole() === 'admin') {
        header("Location: /pages/dashboard/admin/index.php");
        exit;
    } else {
        // If they're logged in but not as an admin,
        // clear the session and set an error message.
        unset($_SESSION['loggedInUser']);
        $_SESSION['error'] = "Access denied. You are not an admin. Please log in with an admin account.";
    }
}

// Retrieve any error message from a previous failed login attempt.
$errorMessage = '';
if (isset($_SESSION['error'])) {
    $errorMessage = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ADMIN | Log in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.min.css">
    <style>
        /* CSS Variables */
        *:root {
            --red: #FF0000;
        }
        /* Overall page styling */
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        /* Container for the login form */
        .login-box {
            width: 300px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .login-box h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        /* Input styling */
        .form-control {
            border-radius: 5px;
            height: 35px;
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #ccc;
        }
        /* Button styling */
        .btn {
            width: 100%;
            border-radius: 5px;
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: var(--red);
        }
        /* Error message styling */
        .error-message {
            background-color: #f8d7da;
            color: #a94442;
            border: 1px solid #a94442;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <h1>Login to ADMIN</h1>
        <!-- Display any error message if one exists -->
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>
        <form action="adminlogin_process.php" method="post">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Username" name="user_email" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="user_pass" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" name="btnLogin" class="btn">Log In</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        // Initialize iCheck if available (optional)
        document.addEventListener("DOMContentLoaded", function () {
            const inputs = document.querySelectorAll('input');
            if (typeof iCheck !== 'undefined') {
                $(inputs).iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%'
                });
            }
        });
    </script>
</body>
</html>
