<?php
// pages/dashboard/jobseeker/index.php
session_start();
require_once '../../../lib/models/jobs_model.php';


// Include the jobs model to retrieve job postings
require_once '../../../lib/models/jobs_model.php';

// Retrieve all job postings
$jobs = getAllJobs();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Jobseeker Dashboard Home</title>
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

  <!-- Main Wrapper for Sidebar and Content -->
  <div class="dashboard-wrapper">
    <!-- Sidebar -->
    <?php include '../../../components/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="dashboard-content">
      <h1>Welcome, <?php echo htmlspecialchars($_SESSION['loggedInUser']['username']); ?></h1>
      
      <!-- Existing Stats Section -->
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
      
      <!-- New Job Listings Section -->
      <section class="job-listings">
        <h2>Available Job Postings</h2>
        <?php if (!empty($jobs)): ?>
          <table>
            <thead>
              <tr>
                <th>Job Title</th>
                <th>Location</th>
                <th>Date Posted</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($jobs as $job): ?>
              <tr>
                <td><?php echo htmlspecialchars($job['name']); ?></td>
                <td><?php echo htmlspecialchars($job['location']); ?></td>
                <td><?php echo htmlspecialchars($job['posted_at']); ?></td>
                <td>
                  <!-- Link to the application page or process (adjust URL as needed) -->
                  <a href="apply.php?job_id=<?php echo $job['id']; ?>" class="btn">Apply</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No job postings available at the moment.</p>
        <?php endif; ?>
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
