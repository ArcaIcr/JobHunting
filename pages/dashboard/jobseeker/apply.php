<?php
// pages/dashboard/jobseeker/apply.php

session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');

// Include the application model and jobs model
require_once '../../../lib/models/application_model.php';
require_once '../../../lib/models/jobs_model.php';

// Get job_id from the URL
if (!isset($_GET['job_id'])) {
    die("No job specified.");
}
$job_id = $_GET['job_id'];

// Retrieve job details; ensure getJobById() exists in your jobs model.
$job = getJobById($job_id);
if (!$job) {
    die("Job not found.");
}

$jobseekerId = $_SESSION['loggedInUser']['id'];

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // For simplicity, we use the logged-in user's username as the applicant name.
    $applicantName = $_SESSION['loggedInUser']['username'];

    if (applyForJob($job_id, $jobseekerId, $applicantName)) {
        // Redirect to applications page with a success indicator
        header("Location: applications.php?success=1");
        exit;
    } else {
        $errorMessage = "Failed to submit application. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Apply for Job</title>
  <link rel="stylesheet" href="/assets/css/jobseeker.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <!-- Top Bar -->
  <?php include '../../../components/d-header.php'; ?>

  <!-- Main Wrapper for Sidebar and Content -->
  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>Apply for Job</h1>
      <?php if (isset($errorMessage)): ?>
        <div class="feedback error"><?php echo htmlspecialchars($errorMessage); ?></div>
      <?php endif; ?>
      <div class="card">
        <h2><?php echo htmlspecialchars($job['name']); ?></h2>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($job['description']); ?></p>
        <!-- Additional job details can be shown here -->
        <form method="POST" action="apply.php?job_id=<?php echo $job_id; ?>">
          <button type="submit">Confirm Application</button>
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
