<?php
// pages/dashboard/employer/manage_jobs.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

// Include the jobs model file
require_once '../../../lib/models/jobs_model.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobTitle       = $_POST['job_title'] ?? '';
    $jobLocation    = $_POST['location'] ?? '';
    $jobDescription = $_POST['description'] ?? '';
    
    addJob($jobTitle, $jobDescription, $jobLocation);
}

// Fetch all jobs for display
$jobs = getAllJobs();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Jobs</title>
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
      <h1>Manage Jobs</h1>
      <!-- Add Job Form -->
      <form action="" method="POST" style="margin-bottom: 2rem;">
        <div>
          <label for="job_title">Job Title:</label><br>
          <input type="text" id="job_title" name="job_title" required>
        </div>
        <div>
          <label for="location">Location:</label><br>
          <input type="text" id="location" name="location" required>
        </div>
        <div>
          <label for="description">Description:</label><br>
          <textarea id="description" name="description" rows="4" required></textarea>
        </div>
        <button type="submit">Add Job</button>
      </form>

      <!-- Jobs Table -->
      <table>
        <thead>
          <tr>
            <th>Job Title</th>
            <th>Location</th>
            <th>Date Posted</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($jobs as $job) : ?>
            <tr>
              <td><?php echo htmlspecialchars($job['name']); ?></td>
              <td><?php echo htmlspecialchars($job['location']); ?></td>
              <td><?php echo htmlspecialchars($job['posted_at']); ?></td>
              <td>
                <a href="edit_job.php?id=<?php echo $job['id']; ?>">Edit</a> |
                <a href="delete_job.php?id=<?php echo $job['id']; ?>">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (count($jobs) === 0): ?>
            <tr>
              <td colspan="4">No jobs posted yet.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
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
