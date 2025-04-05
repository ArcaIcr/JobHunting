<?php
// pages/dashboard/employer/index.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

// Include necessary models
require_once '../../../lib/models/jobs_model.php';
// If you have an application model, you can include that instead.
// For now, we'll add a helper function here to get pending applications count.

// Get employer ID and fetch jobs for stats and recent activity
$employerId = $_SESSION['loggedInUser']['id'];
$jobs = getAllJobs($employerId);
$totalJobs = count($jobs);

// Function to get pending applications count for the employer's jobs
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
  <!-- Top Bar -->
  <?php include '../../../components/d-header.php'; ?>

  <!-- Main Wrapper for Sidebar + Content -->
  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>Welcome, <?php echo htmlspecialchars($_SESSION['loggedInUser']['username']); ?>!</h1>
      
      <!-- Statistics Section -->
      <div class="stats-container">
        <div class="stat-card">
          <h2><?php echo $totalJobs; ?></h2>
          <p>Total Jobs Posted</p>
        </div>
        <div class="stat-card">
          <h2><?php echo $pendingApplications; ?></h2>
          <p>Pending Applications</p>
        </div>
        <!-- You can add more stat cards if needed -->
      </div>

      <!-- Quick Links Section -->
      <div class="quick-links">
        <a href="post_job.php"><i class="fas fa-plus"></i> Post a Job</a>
        <a href="manage_jobs.php"><i class="fas fa-briefcase"></i> Manage Jobs</a>
        <a href="applications.php"><i class="fas fa-inbox"></i> Applications</a>
        <a href="company_profile.php"><i class="fas fa-building"></i> Company Profile</a>
      </div>

      <!-- Recent Activity Section -->
      <div class="recent-activity">
        <h2>Recent Jobs Posted</h2>
        <?php if (count($jobs) > 0): ?>
          <ul>
            <?php foreach (array_slice($jobs, 0, 5) as $job): ?>
              <li>
                <strong><?php echo htmlspecialchars($job['name']); ?></strong> &mdash;
                Posted on <?php echo htmlspecialchars($job['posted_at']); ?>
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
