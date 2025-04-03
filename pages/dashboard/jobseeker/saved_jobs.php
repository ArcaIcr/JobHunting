<?php
// pages/dashboard/jobseeker/saved_jobs.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Saved Jobs</title>
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
        <h1>Saved Jobs</h1>
        <ul>
          <li>
            <a href="#">Senior Developer at Company A</a>
            <span>Saved on: 2025-03-05</span>
          </li>
          <li>
            <a href="#">UI/UX Designer at Company B</a>
            <span>Saved on: 2025-03-07</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</body>
</html>
