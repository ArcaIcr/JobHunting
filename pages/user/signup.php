<?php
// signup.php
include '../../config/config.php';

$error = ''; // Variable to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and trim form inputs
    $fullName        = trim($_POST['fullName']);
    $email           = trim($_POST['email']);
    $username        = trim($_POST['username']);
    $password        = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate passwords
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert the new user into the database using a prepared statement
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashedPassword, $email);
        
        if ($stmt->execute()) {
            // Redirect to login page on successful signup
            header("Location: login.php");
            exit;
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Trabaho Nasipit</title>
  <!-- Link to external CSS -->
  <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>
  <!-- Include header component -->
  <?php include '../../components/header.php'; ?>

  <div class="signup-container">
    <h2>Sign Up</h2>
    <?php if (!empty($error)): ?>
      <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>
    <form id="signupForm" method="post" action="signup.php">
      <div class="input-group">
        <input type="text" name="fullName" id="fullName" placeholder="Full Name" required>
      </div>
      <div class="input-group">
        <input type="email" name="email" id="email" placeholder="Email" required>
      </div>
      <div class="input-group">
        <input type="text" name="username" id="username" placeholder="Username" required>
      </div>
      <div class="input-group">
        <input type="password" name="password" id="password" placeholder="Password" required>
        <span class="toggle-password" onclick="togglePassword('password')">👁</span>
      </div>
      <div class="input-group">
        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required>
        <span class="toggle-password" onclick="togglePassword('confirmPassword')">👁</span>
      </div>
      <button type="submit">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Log in</a></p>
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
