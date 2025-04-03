<?php
// pages/dashboard/jobseeker/saved_jobs.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Saved Jobs</title>
  <link rel="stylesheet" href="/assets/css/jobseeker.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <!-- Top Bar -->
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

  <!-- Main Wrapper: Sidebar + Content -->
  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>Saved Jobs</h1>
      <ul class="saved-jobs-list">
        <li>
          <a href="#">Senior Developer at Company A</a>
          <span>Saved on: 2025-03-05</span>
        </li>
        <li>
          <a href="#">UI/UX Designer at Company B</a>
          <span>Saved on: 2025-03-07</span>
        </li>
      </ul>
    </main>
  </div>

  <!-- Sidebar Toggle Script -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('dashboardSidebar');
      sidebar.classList.toggle('collapsed');
    }
  </script>
</body>
</html>
