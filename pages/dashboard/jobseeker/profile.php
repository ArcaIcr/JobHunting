<?php
// pages/dashboard/jobseeker/profile.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jobseeker Profile</title>
    <link rel="stylesheet" href="/assets/css/jobseeker.css">
</head>
<body>
  <div class="dashboard-container">
    <?php include '../../../components/sidebar.php'; ?>
    <div class="main-content">
      <!-- Top Bar -->
      <div class="top-bar">
        <div class="search-bar">
          <input type="text" placeholder="Search...">
        </div>
        <div class="user-profile">
          <img src="/assets/images/profile.png" alt="User">
          <span><?php echo htmlspecialchars($_SESSION['loggedInUser']['username']); ?></span>
        </div>
      </div>

      <!-- Dashboard Content -->
      <div class="dashboard-content">
        <h1>Your Profile</h1>
        <form action="profile.php" method="post">
          <div class="input-group">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($_SESSION['loggedInUser']['fullname'] ?? ''); ?>" required>
          </div>
          <div class="input-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['loggedInUser']['email'] ?? ''); ?>" required>
          </div>
          <button type="submit">Update Profile</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
