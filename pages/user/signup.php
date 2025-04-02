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
    $role            = trim($_POST['role']); // New field for role

    // Validate role and password
    if($role !== 'employer' && $role !== 'jobseeker') {
        $error = "Invalid role selected.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert the new user into the users table with role
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashedPassword, $email, $role);
        
        if ($stmt->execute()) {
            // Get the inserted user's id
            $userId = $conn->insert_id;
            
            // Insert into the appropriate profile table based on the role
            if ($role == 'employer') {
                // Retrieve company name; fallback to fullName if not provided
                $companyName = trim($_POST['companyName']);
                if(empty($companyName)){
                    $companyName = $fullName;
                }
                $stmtProfile = $conn->prepare("INSERT INTO employer_profiles (user_id, company_name) VALUES (?, ?)");
                $stmtProfile->bind_param("is", $userId, $companyName);
                $stmtProfile->execute();
                $stmtProfile->close();
            } elseif ($role == 'jobseeker') {
                // Insert a basic record into jobseeker_profiles; additional fields can be updated later
                $stmtProfile = $conn->prepare("INSERT INTO jobseeker_profiles (user_id) VALUES (?)");
                $stmtProfile->bind_param("i", $userId);
                $stmtProfile->execute();
                $stmtProfile->close();
            }
            
            // Redirect to login page on successful signup
            header("Location: login.php");
            exit;
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
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
  <style>
    /* Optional: Some inline CSS to help with the display of the company field */
    #companyField {
        display: none;
    }
  </style>
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
      <!-- Role Selection -->
      <div class="input-group">
        <label for="role">I am a:</label>
        <select name="role" id="role" required onchange="toggleCompanyField(this.value)">
          <option value="">Select Role</option>
          <option value="jobseeker">Jobseeker</option>
          <option value="employer">Employer</option>
        </select>
      </div>
      <!-- Company Name field (visible only if employer is selected) -->
      <div class="input-group" id="companyField">
        <input type="text" name="companyName" id="companyName" placeholder="Company Name">
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
    
    // Function to toggle display of Company Name field based on role selection
    function toggleCompanyField(role) {
      var companyField = document.getElementById('companyField');
      if(role === 'employer') {
          companyField.style.display = 'block';
      } else {
          companyField.style.display = 'none';
      }
    }
  </script>
</body>
</html>
