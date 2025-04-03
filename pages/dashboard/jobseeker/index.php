<?php
// pages/dashboard/jobseeker/index.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Jobseeker Dashboard Home</title>
  <link rel="stylesheet" href="/assets/css/jobseeker.css">
</head>
<body>
  <div class="dashboard-container">
    <?php include '../../../components/sidebar.php'; ?>
    <div class="main-content">
      <div class="top-bar">
        <div class="search-bar">
          <input type="text" placeholder="Search...">
        </div>
        <div class="user-profile">
          <img src="/assets/images/profile.png" alt="User" />
          <span><?php echo htmlspecialchars($_SESSION['loggedInUser']['username']); ?></span>
        </div>
      </div>
      <div class="dashboard-content">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['loggedInUser']['username']); ?></h1>
        <section class="stats">
          <div class="card">
            <h3>Applications Sent</h3>
            <p>12</p>
          </div>
          <div class="card">
            <h3>Interviews Scheduled</h3>
            <p>3</p>
          </div>
          <div class="card">
            <h3>Jobs Saved</h3>
            <p>8</p>
          </div>
        </section>
      </div>
    </div>
  </div>
</body>
</html>
