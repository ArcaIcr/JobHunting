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
  <!-- HEADER -->
  <header class="dashboard-top-bar">
    <div class="left-group">
      <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
      </button>
      <h2>TrabahoNasipit</h2>
    </div>
    <div class="search-bar">
      <input type="text" placeholder="Search...">
    </div>
    <div class="user-profile">
      <img src="/assets/images/profile.png" alt="User">
      <span><?php echo htmlspecialchars($_SESSION['loggedInUser']['username']); ?></span>
    </div>
  </header>

  <!-- DASHBOARD WRAPPER -->
  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>Delete Job</h1>
      <p>
        Are you sure you want to delete the job: 
        <strong><?php echo htmlspecialchars($job['name']); ?></strong>?
      </p>
      <form action="" method="POST">
        <button type="submit" name="confirm" value="Yes">Yes, delete</button>
        <button type="submit" name="confirm" value="No">No, cancel</button>
      </form>
    </main>
  </div>

  <!-- SIDEBAR TOGGLE SCRIPT -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('dashboardSidebar');
      sidebar.classList.toggle('collapsed');
    }
  </script>
</body>
</html>
