<?php
// pages/dashboard/employer/index.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

// Include necessary models
require_once '../../../lib/models/jobs_model.php';

// Get employer ID and fetch jobs for stats and recent activity
$employerId = $_SESSION['loggedInUser']['id'];
$jobs = getAllJobs($employerId);
$totalJobs = count($jobs);

// Helper function to get pending applications count for this employer
function getPendingApplicationsCountForEmployer($employerId) {
    $pdo = getPDO();
    $sql = "SELECT COUNT(*) 
            FROM applications a 
            JOIN jobs j ON a.job_id = j.id 
            WHERE j.employer_id = ? AND a.status = 'Under Review'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$employerId]);
    return $stmt->fetchColumn();
}
$pendingApplications = getPendingApplicationsCountForEmployer($employerId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employer Dashboard Home</title>
  <link rel="stylesheet" href="/assets/css/employer.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
  <?php include '../../../components/d-header.php'; ?>
  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <div class="header-banner">
        <h1>Employer Dashboard</h1>
        <p>Monitor your job postings and manage applications at a glance</p>
      </div>
      <div class="stats-grid">
        <div class="stat-card">
          <h2><?php echo $totalJobs; ?></h2>
          <p>Total Jobs Posted</p>
        </div>
        <div class="stat-card">
          <h2><?php echo $pendingApplications; ?></h2>
          <p>Pending Applications</p>
        </div>
      </div>
      <div class="quick-links">
        <a class="quick-link" href="post_job.php"><i class="fas fa-plus"></i> Post a Job</a>
        <a class="quick-link" href="manage_jobs.php"><i class="fas fa-briefcase"></i> Manage Jobs</a>
        <a class="quick-link" href="applications.php"><i class="fas fa-inbox"></i> Applications</a>
        <a class="quick-link" href="company_profile.php"><i class="fas fa-building"></i> Company Profile</a>
      </div>
      <div class="recent-jobs">
        <h2>Recent Jobs Posted</h2>
        <?php if (count($jobs) > 0): ?>
          <ul>
            <?php foreach (array_slice($jobs, 0, 5) as $job): ?>
              <li class="recent-job-item">
                <strong><?php echo htmlspecialchars($job['name']); ?></strong>
                <p>Location: <?php echo htmlspecialchars($job['location']); ?></p>
                <p>Posted on: <?php echo htmlspecialchars($job['posted_at']); ?></p>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>No jobs posted yet.</p>
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
