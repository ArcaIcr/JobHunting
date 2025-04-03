<?php
// components/sidebar.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$role = $_SESSION['loggedInUser']['role'] ?? '';
?>
<aside class="sidebar">
  <nav class="sidebar-nav">
    <?php if ($role === 'jobseeker'): ?>
      <ul>
        <li><a href="/pages/dashboard/jobseeker/index.php">Dashboard Home</a></li>
        <li><a href="/pages/dashboard/jobseeker/profile.php">Profile</a></li>
        <li><a href="/pages/dashboard/jobseeker/applications.php">Applications</a></li>
        <li><a href="/pages/dashboard/jobseeker/saved_jobs.php">Saved Jobs</a></li>
      </ul>
    <?php elseif ($role === 'employer'): ?>
      <ul>
        <li><a href="/pages/dashboard/employer/index.php">Dashboard Home</a></li>
        <li><a href="/pages/dashboard/employer/post_job.php">Post a Job</a></li>
        <li><a href="/pages/dashboard/employer/manage_jobs.php">Manage Jobs</a></li>
        <li><a href="/pages/dashboard/employer/applications.php">Applications</a></li>
        <li><a href="/pages/dashboard/employer/company_profile.php">Company Profile</a></li>
      </ul>
    <?php else: ?>
      <p>No dashboard links available.</p>
    <?php endif; ?>
  </nav>
</aside>
