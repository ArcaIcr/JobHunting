<?php
// pages/dashboard/jobseeker/applications.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');

// Include the application model to fetch applications
require_once '../../../lib/models/application_model.php';

// Get the current jobseeker's ID from the session
$jobseekerId = $_SESSION['loggedInUser']['id'];

// Fetch applications dynamically
$applications = getApplicationsForJobseeker($jobseekerId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Job Applications</title>
  <link rel="stylesheet" href="/assets/css/jobseeker.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
</head>
<body>
  <!-- Shared Top Bar/Header -->
  <?php include '../../../components/d-header.php'; ?>

  <!-- Main Wrapper for Sidebar and Content -->
  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <div class="applications-header">
        <h1>My Job Applications</h1>
        <p>Track the status of the jobs you applied for</p>
      </div>
      <div class="applications-container">
        <?php if (!empty($applications)): ?>
          <table class="applications-table">
            <thead>
              <tr>
                <th>Job Title</th>
                <th>Company</th>
                <th>Date Applied</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($applications as $app): ?>
                <tr>
                  <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                  <td><?php echo htmlspecialchars($app['company_name']); ?></td>
                  <td><?php echo htmlspecialchars($app['date_applied']); ?></td>
                  <td><?php echo htmlspecialchars($app['status']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No applications found. Start applying for jobs now!</p>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('dashboardSidebar');
      sidebar.classList.toggle('collapsed');
    }
  </script>
</body>
</html>
