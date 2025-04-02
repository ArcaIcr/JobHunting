<?php
// signup.php
include '../config/config.php';

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
  <style>
    /* Basic reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    /* Background and body styling */
    body {
      background: url('/assets/images/backg.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }
    /* Header styling */
    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: rgba(255, 255, 255, 0.95);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      z-index: 100;
    }
    header .logo {
      font-size: 1.8rem;
      font-weight: bold;
      color: #333;
    }
    header .navbar a {
      margin-left: 1rem;
      text-decoration: none;
      color: #555;
      font-size: 1rem;
      transition: color 0.3s ease;
    }
    header .navbar a:hover {
      color: #FF0000;
    }
    /* Signup container styling */
    .signup-container {
      background: rgba(255, 255, 255, 0.98);
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      width: 320px;
      margin-top: 80px; /* To leave space for fixed header */
    }
    .signup-container h2 {
      text-align: center;
      margin-bottom: 1rem;
      color: #333;
    }
    .error-message {
      text-align: center;
      color: #FF0000;
      margin-bottom: 1rem;
    }
    .input-group {
      margin-bottom: 1rem;
      position: relative;
    }
    .input-group input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 1rem;
    }
    .input-group input:focus {
      border-color: #FF0000;
      outline: none;
    }
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 1rem;
    }
    button {
      width: 100%;
      padding: 0.75rem;
      background: #333;
      border: none;
      border-radius: 4px;
      color: #fff;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    button:hover {
      background: #555;
    }
    .signup-container p {
      text-align: center;
      margin-top: 1rem;
      font-size: 0.9rem;
      color: #666;
    }
    .signup-container p a {
      color: #FF0000;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <header>
    <a href="#" class="logo">trabahosanasipit <span>.</span></a>
    <nav class="navbar">
      <a href="home.php">Home</a>
      <a href="findjob.php">Job Search</a>
      <a href="cooperation.php">Cooperation</a>
      <a href="contact.php">Contact</a>
      <a href="login.php">Login</a>
      <a href="signup.php">Register</a>
    </nav>
  </header>
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
