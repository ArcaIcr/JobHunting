<?php
// pages/dashboard/employer/applications.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

// Include the model file that contains getApplicationsForEmployer()
require_once '../../../lib/models/jobs_model.php';

// Get the logged-in employer's ID from the session
$employerId = $_SESSION['loggedInUser']['id'] ?? null;
if (!$employerId) {
    die("Employer ID not found in session.");
}

// Fetch all applications for jobs posted by this employer
$applications = getApplicationsForEmployer($employerId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Applications</title>
  <link rel="stylesheet" href="/assets/css/employer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <?php include '../../../components/d-header.php'; ?>

  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>Applications</h1>
      <table>
        <thead>
          <tr>
            <th>Applicant Name</th>
            <th>Job Title</th>
            <th>Date Applied</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($applications)) : ?>
            <?php foreach ($applications as $app) : ?>
              <tr>
                <td><?php echo htmlspecialchars($app['applicant_name']); ?></td>
                <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                <td><?php echo htmlspecialchars($app['date_applied']); ?></td>
                <td><?php echo htmlspecialchars($app['status']); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="4">No applications found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
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
