<?php
// pages/dashboard/jobseeker/applications.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Applications</title>
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
        <h1>Job Applications</h1>
        <table>
          <thead>
            <tr>
              <th>Job Title</th>
              <th>Company</th>
              <th>Date Applied</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Software Engineer</td>
              <td>ABC Corp</td>
              <td>2025-03-01</td>
              <td>Under Review</td>
            </tr>
            <tr>
              <td>Front-end Developer</td>
              <td>XYZ Inc</td>
              <td>2025-02-25</td>
              <td>Interview Scheduled</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
