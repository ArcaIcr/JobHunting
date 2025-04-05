<?php
// pages/dashboard/jobseeker/index.php

session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');

// Include models
require_once '../../../lib/models/jobs_model.php';
require_once '../../../lib/models/application_model.php';

// For saved jobs, include the saved jobs model if available
if (file_exists('../../../lib/models/saved_jobs_model.php')) {
    require_once '../../../lib/models/saved_jobs_model.php';
}

$jobseekerId = $_SESSION['loggedInUser']['id'];

// Retrieve all job postings (for jobseekers, we show all jobs)
$jobs = getAllJobs();  // This should return all jobs if no employer ID is provided

// Retrieve dynamic statistics
$appCount = getApplicationCountForJobseeker($jobseekerId);           // Total applications sent
$interviewCount = getInterviewScheduledCountForJobseeker($jobseekerId); // Interviews scheduled
$savedCount = getJobsSavedCountForJobseeker($jobseekerId);             // Total saved jobs
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Jobseeker Dashboard Home</title>
  <link rel="stylesheet" href="/assets/css/jobseeker.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* Additional inline styling for modern design (you may move these to jobseeker.css) */
    .hero {
      background: linear-gradient(135deg, #4e54c8, #8f94fb);
      color: #fff;
      padding: 2rem;
      border-radius: 8px;
      margin-bottom: 2rem;
      text-align: center;
    }
    .hero h1 {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
    }
    .hero p {
      font-size: 1.2rem;
      margin: 0;
    }
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }
    .stat-card {
      background: #fff;
      border-radius: var(--border-radius);
      box-shadow: var(--card-shadow);
      padding: 1rem;
      text-align: center;
    }
    .stat-card h3 {
      font-size: 1.2rem;
      margin-bottom: 0.3rem;
    }
    .stat-card p {
      font-size: 1.5rem;
      margin: 0;
      font-weight: bold;
    }
    .job-listings {
      background: #fff;
      border-radius: var(--border-radius);
      box-shadow: var(--card-shadow);
      padding: 1rem;
    }
    .job-listings h2 {
      margin-bottom: 1rem;
    }
    .job-listings table {
      width: 100%;
      border-collapse: collapse;
    }
    .job-listings th, .job-listings td {
      padding: 1rem;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    .job-listings th {
      background: #f4f4f4;
    }
    .btn-apply, .btn-save {
      display: inline-block;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      text-decoration: none;
      font-size: 0.9rem;
      transition: background 0.3s ease;
      margin-right: 0.5rem;
    }
    .btn-apply {
      background: var(--primary);
      color: #fff;
    }
    .btn-apply:hover {
      background: #d65a3f;
    }
    .btn-save {
      background: #007BFF;
      color: #fff;
    }
    .btn-save:hover {
      background: #0056b3;
    }
    .btn-disabled {
      background: #ccc;
      color: #666;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      text-decoration: none;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
  <!-- Shared Top Bar/Header -->
  <?php include '../../../components/d-header.php'; ?>

  <!-- Main Wrapper for Sidebar and Content -->
  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <div class="hero">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['loggedInUser']['username']); ?>!</h1>
        <p>Your journey to your dream job starts here.</p>
      </div>
      
      <!-- Dynamic Statistics Section -->
      <div class="stats-grid">
        <div class="stat-card">
          <h3>Applications Sent</h3>
          <p><?php echo htmlspecialchars($appCount); ?></p>
        </div>
        <div class="stat-card">
          <h3>Interviews Scheduled</h3>
          <p><?php echo htmlspecialchars($interviewCount); ?></p>
        </div>
        <div class="stat-card">
          <h3>Jobs Saved</h3>
          <p><?php echo htmlspecialchars($savedCount); ?></p>
        </div>
      </div>
      
      <!-- Job Listings Section -->
      <section class="job-listings">
        <h2>Available Job Postings</h2>
        <?php if (!empty($jobs)): ?>
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
              <?php foreach ($jobs as $job): ?>
                <?php
                  $alreadyApplied = hasUserApplied($jobseekerId, $job['id']);
                  $alreadySaved   = hasJobBeenSaved($jobseekerId, $job['id']);
                ?>
                <tr>
                  <td><?php echo htmlspecialchars($job['name']); ?></td>
                  <td><?php echo htmlspecialchars($job['location']); ?></td>
                  <td><?php echo htmlspecialchars($job['posted_at']); ?></td>
                  <td>
                    <?php if ($alreadyApplied): ?>
                      <span class="btn-disabled">Already Applied</span>
                    <?php else: ?>
                      <a href="apply.php?job_id=<?php echo $job['id']; ?>" class="btn-apply">Apply</a>
                    <?php endif; ?>
                    
                    <?php if ($alreadySaved): ?>
                      <span class="btn-disabled">Saved</span>
                    <?php else: ?>
                      <a href="save_job.php?job_id=<?php echo $job['id']; ?>" class="btn-save">Save</a>
                    <?php endif; ?>
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
  
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('dashboardSidebar');
      sidebar.classList.toggle('collapsed');
    }
  </script>
</body>
</html>
