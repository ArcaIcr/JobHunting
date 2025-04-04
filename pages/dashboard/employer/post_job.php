<?php
// pages/dashboard/employer/post_job.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

// Include the jobs model file that contains addJob()
require_once '../../../lib/models/jobs_model.php';

// If form is submitted, handle logic here...
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $jobTitle = $_POST['jobTitle'] ?? '';
  $jobDescription = $_POST['jobDescription'] ?? '';
  $location = $_POST['location'] ?? '';

  // Attempt to add the job to the database
  if (addJob($jobTitle, $jobDescription, $location)) {
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
