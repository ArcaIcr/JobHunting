<?php
// components/sidebar.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$role = $_SESSION['loggedInUser']['role'] ?? '';
?>
<aside class="sidebar" id="dashboardSidebar">
  <nav class="sidebar-nav">
    <?php if ($role === 'jobseeker'): ?>
      <ul>
        <li>
          <a href="/pages/dashboard/jobseeker/index.php">
            <i class="fas fa-home"></i>
            <span class="link-text">Dashboard Home</span>
          </a>
        </li>
        <li>
          <a href="/pages/dashboard/jobseeker/profile.php">
            <i class="fas fa-user"></i>
            <span class="link-text">Profile</span>
          </a>
        </li>
        <li>
          <a href="/pages/dashboard/jobseeker/applications.php">
            <i class="fas fa-briefcase"></i>
            <span class="link-text">Applications</span>
          </a>
        </li>
        <li>
          <a href="/pages/dashboard/jobseeker/saved_jobs.php">
            <i class="fas fa-bookmark"></i>
            <span class="link-text">Saved Jobs</span>
          </a>
        </li>
        <!-- Logout Button -->
        <div class="logout-container">
          <a href="/logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <span class="link-text">Logout</span>
          </a>
        </div>
      </ul>
    <?php elseif ($role === 'employer'): ?>
      <ul>
        <li>
          <a href="/pages/dashboard/employer/index.php">
            <i class="fas fa-tachometer-alt"></i>
            <span class="link-text">Dashboard Home</span>
          </a>
        </li>
        <li>
          <a href="/pages/dashboard/employer/post_job.php">
            <i class="fas fa-plus-circle"></i>
            <span class="link-text">Post a Job</span>
          </a>
        </li>
        <li>
          <a href="/pages/dashboard/employer/manage_jobs.php">
            <i class="fas fa-edit"></i>
            <span class="link-text">Manage Jobs</span>
          </a>
        </li>
        <li>
          <a href="/pages/dashboard/employer/applications.php">
            <i class="fas fa-briefcase"></i>
            <span class="link-text">Applications</span>
          </a>
        </li>
        <li>
          <a href="/pages/dashboard/employer/company_profile.php">
            <i class="fas fa-building"></i>
            <span class="link-text">Company Profile</span>
          </a>
        </li>
      </ul>
    <?php else: ?>
      <p>No dashboard links available.</p>
    <?php endif; ?>
  </nav>
</aside>
