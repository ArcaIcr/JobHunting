<?php
// pages/dashboard/employer/manage_jobs.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

// Include the jobs model file
require_once '../../../lib/models/jobs_model.php';

// Get employer ID from session
$employerId = $_SESSION['loggedInUser']['id'];
$jobs = getAllJobs($employerId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Jobs</title>
  <link rel="stylesheet" href="/assets/css/employer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
</head>
<body>
  <?php include '../../../components/d-header.php'; ?>

  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>Manage Jobs</h1>
      <div class="jobs-table-container">
        <table>
          <thead>
            <tr>
              <th>Job Title</th>
              <th>Location</th>
              <th>Date Posted</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($jobs as $job) : ?>
              <tr>
                <td><?php echo htmlspecialchars($job['name']); ?></td>
                <td><?php echo htmlspecialchars($job['location']); ?></td>
                <td><?php echo htmlspecialchars($job['posted_at']); ?></td>
                <td>
                  <a href="edit_job.php?id=<?php echo $job['id']; ?>">Edit</a> |
                  <a href="delete_job.php?id=<?php echo $job['id']; ?>">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (count($jobs) === 0): ?>
              <tr>
                <td colspan="4">No jobs posted yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
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
