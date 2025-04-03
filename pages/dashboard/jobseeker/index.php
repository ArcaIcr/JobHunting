<?php
// pages/dashboard/jobseeker/index.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Jobseeker Dashboard Home</title>
  <!-- Dashboard-specific CSS -->
  <link rel="stylesheet" href="/assets/css/jobseeker.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

  <!-- Top Bar (Full Width) -->
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
      <img src="/assets/images/profile.png" alt="User" />
      <span><?php echo htmlspecialchars($_SESSION['loggedInUser']['username']); ?></span>
    </div>
  </header>

  <!-- Main Wrapper for Sidebar + Content -->
  <div class="dashboard-wrapper">
    <!-- Sidebar -->
    <?php include '../../../components/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="dashboard-content">
      <h1>Welcome, <?php echo htmlspecialchars($_SESSION['loggedInUser']['username']); ?></h1>
      <section class="stats">
        <div class="card">
          <h3>Applications Sent</h3>
          <p>12</p>
        </div>
        <div class="card">
          <h3>Interviews Scheduled</h3>
          <p>3</p>
        </div>
        <div class="card">
          <h3>Jobs Saved</h3>
          <p>8</p>
        </div>
      </section>
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
