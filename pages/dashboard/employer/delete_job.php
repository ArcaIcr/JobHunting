<?php
// pages/dashboard/employer/delete_job.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

require_once '../../../lib/models/jobs_model.php';

if (!isset($_GET['id'])) {
    die("No job ID provided.");
}

$jobId = $_GET['id'];

// Fetch the job details for confirmation
$job = getJobById($jobId);
if (!$job) {
    die("Job not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If confirmed, delete the job; otherwise, do nothing and redirect
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'Yes') {
        deleteJob($jobId);
    }
    header("Location: manage_jobs.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete Job</title>
  <link rel="stylesheet" href="/assets/css/employer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
</head>
<body>
  <?php include '../../../components/d-header.php'; ?>

  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <div class="hero">
        <h1>Delete Job</h1>
        <p>Confirm deletion of job posting</p>
      </div>
      <div class="delete-job-card">
        <p>
          Are you sure you want to delete the job: 
          <strong><?php echo htmlspecialchars($job['name']); ?></strong>?
        </p>
        <form action="" method="POST">
          <button type="submit" name="confirm" value="Yes">Yes, delete</button>
          <button type="submit" name="confirm" value="No" class="cancel-btn">No, cancel</button>
        </form>
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
