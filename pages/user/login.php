<?php
// login.php
session_start();
include '../../config/config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Prepare and execute SQL query to fetch user by username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        // Check if the user exists
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify the password (assumes password stored is hashed)
            if (password_verify($password, $user['password'])) {
                // Set session variables (or any other login logic)
                $_SESSION['loggedInUser'] = $user;
                
                // Role-based redirection to the respective dashboard
                if ($user['role'] === 'employer') {
                    header("Location: /pages/dashboard/employer/index.php");
                } else {
                    header("Location: /pages/dashboard/jobseeker/index.php");
                }
                exit;
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "User not found! Please sign up first.";
        }
    } else {
        $error = "An error occurred. Please try again later.";
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
  <!-- Link to external CSS -->
  <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>
  <!-- Include header component -->
  <?php include '../../components/header.php'; ?>

  <div class="login-container">
    <h1>Log in</h1>
    <?php if (!empty($error)): ?>
      <p class="error-message"><?php echo $error; ?></p>
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
    <p><a href="home.php">Return to Home</a></p>
  </div>

  <script>
    // Function to toggle password visibility
    function togglePassword(fieldId) {
      var field = document.getElementById(fieldId);
      field.type = (field.type === "password") ? "text" : "password";
    }
  </script>
</body>
</html>
