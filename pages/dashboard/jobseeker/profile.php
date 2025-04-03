<?php
// pages/dashboard/jobseeker/profile.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Jobseeker Profile</title>
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

  <!-- Main Wrapper for Sidebar and Content -->
  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>Your Profile</h1>
      <form action="profile.php" method="post">
        <div class="input-group">
          <label for="fullname">Full Name:</label>
          <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($_SESSION['loggedInUser']['fullname'] ?? ''); ?>" required>
        </div>
        <div class="input-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['loggedInUser']['email'] ?? ''); ?>" required>
        </div>
        <button type="submit">Update Profile</button>
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
