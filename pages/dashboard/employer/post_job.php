<?php
// pages/dashboard/employer/post_job.php

// Start session only if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../../lib/auth.php';
requireRole('employer');

// Include the jobs model file that contains addJob()
require_once '../../../lib/models/jobs_model.php';

// If form is submitted, handle the logic here
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobTitle       = $_POST['jobTitle'] ?? '';
    $jobDescription = $_POST['jobDescription'] ?? '';
    $location       = $_POST['location'] ?? '';
    $employerId     = $_SESSION['loggedInUser']['id']; // Get employer ID from session

    if (addJob($jobTitle, $jobDescription, $location, $employerId)) {
        $successMessage = "Job posted successfully!";
    } else {
        $errorMessage = "Failed to post job. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Post a Job</title>
  <link rel="stylesheet" href="/assets/css/employer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <?php include '../../../components/d-header.php'; ?>

  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>Post a Job</h1>
      <?php if (isset($successMessage)) : ?>
        <div class="card" style="margin-bottom: 1rem;">
          <p><?php echo htmlspecialchars($successMessage); ?></p>
        </div>
      <?php endif; ?>
      <?php if (isset($errorMessage)) : ?>
        <div class="card" style="margin-bottom: 1rem; color: red;">
          <p><?php echo htmlspecialchars($errorMessage); ?></p>
        </div>
      <?php endif; ?>

      <form method="post" action="post_job.php" class="card">
        <div>
          <label for="jobTitle">Job Title:</label>
          <input type="text" id="jobTitle" name="jobTitle" required>
        </div>
        <div>
          <label for="jobDescription">Job Description:</label>
          <textarea id="jobDescription" name="jobDescription" rows="5" required></textarea>
        </div>
        <div>
          <label for="location">Location:</label>
          <input type="text" id="location" name="location" required>
        </div>
        <button type="submit" style="margin-top: 1rem;">Post Job</button>
      </form>
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
