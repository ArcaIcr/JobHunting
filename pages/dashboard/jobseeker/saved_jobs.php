<?php
// pages/dashboard/jobseeker/saved_jobs.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');

// Include the saved jobs model
require_once '../../../lib/models/saved_jobs_model.php';

$jobseekerId = $_SESSION['loggedInUser']['id'];
$savedJobs = getSavedJobsForJobseeker($jobseekerId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Saved Jobs</title>
  <link rel="stylesheet" href="/assets/css/jobseeker.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* Additional styling for the saved jobs list */
    .saved-jobs-list {
      list-style: none;
      padding-left: 0;
    }
    .saved-jobs-list li {
      background: #fff;
      padding: 1rem;
      margin-bottom: 1rem;
      border-radius: var(--border-radius);
      box-shadow: var(--card-shadow);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .saved-jobs-list li a {
      text-decoration: none;
      color: var(--text-dark);
      font-weight: bold;
    }
    .saved-jobs-list li span {
      font-size: 0.9rem;
      color: #555;
    }
  </style>
</head>
<body>
  <!-- Top Bar -->
  <?php include '../../../components/d-header.php'; ?>

  <!-- Main Wrapper: Sidebar + Content -->
  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>Saved Jobs</h1>
      <?php if (!empty($savedJobs)): ?>
        <ul class="saved-jobs-list">
          <?php foreach ($savedJobs as $job): ?>
            <li>
              <a href="job_detail.php?job_id=<?php echo $job['job_id']; ?>">
                <?php echo htmlspecialchars($job['job_title']); ?> at <?php echo htmlspecialchars($job['company_name']); ?>
              </a>
              <span>Saved on: <?php echo htmlspecialchars($job['saved_at']); ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>You have no saved jobs.</p>
      <?php endif; ?>
    </main>
  </div>

  <!-- Sidebar Toggle Script -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('dashboardSidebar');
      sidebar.classList.toggle('collapsed');
    }
  </script>
</body>
</html>
