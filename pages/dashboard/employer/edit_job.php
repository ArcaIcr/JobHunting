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
  <link rel="stylesheet" href="/assets/css/employer.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
</head>
<body>
  <?php include '../../../components/d-header.php'; ?>

  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <div class="hero">
        <h1>Edit Job</h1>
        <p>Update the job details below</p>
      </div>
      <div class="edit-job-card">
        <form action="" method="POST">
          <div>
            <label for="job_title">Job Title:</label>
            <input type="text" id="job_title" name="job_title" value="<?php echo htmlspecialchars($job['name']); ?>" required>
          </div>
          <div>
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" required>
          </div>
          <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($job['description']); ?></textarea>
          </div>
          <button type="submit">Update Job</button>
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
