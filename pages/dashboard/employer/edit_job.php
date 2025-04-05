<?php
// pages/dashboard/employer/edit_job.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

require_once '../../../lib/models/jobs_model.php';

// 1. Get the job ID from the URL
if (!isset($_GET['id'])) {
    die("No job ID provided.");
}
$jobId = $_GET['id'];

// 2. If the form is submitted, update the job
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobTitle       = $_POST['job_title'] ?? '';
    $jobDescription = $_POST['description'] ?? '';
    $jobLocation    = $_POST['location'] ?? '';

    updateJob($jobId, $jobTitle, $jobDescription, $jobLocation);

    // Redirect back to manage_jobs page
    header("Location: manage_jobs.php");
    exit;
}

// 3. Fetch the existing job data
$job = getJobById($jobId);
if (!$job) {
    die("Job not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Job</title>
  <link rel="stylesheet" href="/assets/css/employer.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* Modern styling for Edit Job page */
    .hero {
      background: linear-gradient(135deg, #4e54c8, #8f94fb);
      color: #fff;
      padding: 2rem;
      border-radius: 8px;
      text-align: center;
      margin-bottom: 2rem;
    }
    .hero h1 {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
    }
    .hero p {
      font-size: 1.2rem;
      margin: 0;
    }
    .edit-job-card {
      background: #fff;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      max-width: 600px;
      margin: 0 auto;
    }
    .edit-job-card form > div {
      margin-bottom: 1rem;
    }
    .edit-job-card label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: bold;
      color: #333;
    }
    .edit-job-card input[type="text"],
    .edit-job-card textarea {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
      font-size: 1rem;
    }
    .edit-job-card button {
      background: var(--primary);
      color: #fff;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
      transition: background 0.3s ease;
      width: 100%;
      margin-top: 1rem;
    }
    .edit-job-card button:hover {
      background: #d65a3f;
    }
  </style>
</head>
<body>
  <?php include '../../../components/d-header.php'; ?>

  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <div class="hero">
        <h1>Edit Job</h1>
        <p>Update the job details below</p>
      </div>
      <div class="edit-job-card">
        <form action="" method="POST">
          <div>
            <label for="job_title">Job Title:</label>
            <input type="text" id="job_title" name="job_title" value="<?php echo htmlspecialchars($job['name']); ?>" required>
          </div>
          <div>
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" required>
          </div>
          <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($job['description']); ?></textarea>
          </div>
          <button type="submit">Update Job</button>
        </form>
      </div>
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
