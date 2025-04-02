<?php
// login.php
session_start();
include '../config/config.php';

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
                // Redirect to personal info or dashboard page
                header("Location: PI.php");
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Trabaho Nasipit</title>
  <style>
    /* Basic Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    /* Body & Background */
    body {
      background: url('/assets/images/backg.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }
    /* Header Styling */
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
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
    /* Login Container */
    .login-container {
      background: rgba(255,255,255,0.98);
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      width: 320px;
      margin-top: 80px; /* Leaves space for fixed header */
      text-align: center;
    }
    .login-container h1 {
      margin-bottom: 1rem;
      color: #333;
      font-size: 1.8rem;
    }
    .error-message {
      color: #FF0000;
      margin-bottom: 1rem;
      font-size: 0.9rem;
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
    .options {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      margin-bottom: 1rem;
    }
    .options input[type="checkbox"] {
      width: 16px;
      height: 16px;
      margin-right: 0.5rem;
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
    .login-container a {
      color: #FF0000;
      text-decoration: none;
      font-size: 0.9rem;
    }
    .login-container a:hover {
      text-decoration: underline;
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
