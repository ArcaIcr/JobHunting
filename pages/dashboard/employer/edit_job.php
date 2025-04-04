<?php
// pages/dashboard/employer/edit_job.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

require_once '../../../lib/models/jobs_model.php';

// 1. Get the job ID from the URL
if (!isset($_GET['id'])) {
    die("No job ID provided.");
}

$jobId = $_GET['id'];

// 2. If the form is submitted, update the job
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobTitle       = $_POST['job_title'] ?? '';
    $jobDescription = $_POST['description'] ?? '';
    $jobLocation    = $_POST['location'] ?? '';

    updateJob($jobId, $jobTitle, $jobDescription, $jobLocation);

    // Redirect back to manage_jobs page
    header("Location: manage_jobs.php");
    exit;
}

// 3. Fetch the existing job data
$job = getJobById($jobId);

if (!$job) {
    die("Job not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Job</title>
  <!-- Make sure this path matches your folder structure -->
  <link rel="stylesheet" href="/assets/css/employer.css">
  <!-- Optional FontAwesome icons -->
  <link rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <!-- HEADER (copy the same structure as manage_jobs.php) -->
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

  <!-- WRAPPER -->
  <div class="dashboard-wrapper">
    <!-- SIDEBAR -->
    <?php include '../../../components/sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
      <h1>Edit Job</h1>
      <form action="" method="POST">
        <div>
          <label for="job_title">Job Title:</label><br>
          <input type="text" id="job_title" name="job_title"
                 value="<?php echo htmlspecialchars($job['name']); ?>" required>
        </div>
        <div>
          <label for="location">Location:</label><br>
          <input type="text" id="location" name="location"
                 value="<?php echo htmlspecialchars($job['location']); ?>" required>
        </div>
        <div>
          <label for="description">Description:</label><br>
          <textarea id="description" name="description" rows="4" required><?php
            echo htmlspecialchars($job['description']);
          ?></textarea>
        </div>
        <button type="submit">Update Job</button>
      </form>
    </main>
  </div>

  <!-- SIDEBAR TOGGLE SCRIPT (same as manage_jobs.php) -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('dashboardSidebar');
      sidebar.classList.toggle('collapsed');
    }
  </script>
</body>
</html>
