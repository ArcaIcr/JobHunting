<?php
// pages/dashboard/employer/company_profile.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Company Profile</title>
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
      <h1>Company Profile</h1>
      <form action="company_profile.php" method="post" class="card">
        <div>
          <label for="companyName">Company Name:</label>
          <input type="text" id="companyName" name="companyName" value="<?php echo htmlspecialchars($_SESSION['loggedInUser']['company_name'] ?? ''); ?>" required>
        </div>
        <div>
          <label for="companyEmail">Company Email:</label>
          <input type="email" id="companyEmail" name="companyEmail" value="<?php echo htmlspecialchars($_SESSION['loggedInUser']['company_email'] ?? ''); ?>" required>
        </div>
        <div>
          <label for="companyDescription">Company Description:</label>
          <textarea id="companyDescription" name="companyDescription" rows="5" required><?php echo htmlspecialchars($_SESSION['loggedInUser']['company_description'] ?? ''); ?></textarea>
        </div>
        <button type="submit" style="margin-top: 1rem;">Update Profile</button>
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
