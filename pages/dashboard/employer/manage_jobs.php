<?php
// pages/dashboard/employer/manage_jobs.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

/****************************************
 * 1. Connect to the database (PDO example)
 ****************************************/
$dsn    = 'mysql:host=localhost;dbname=system_g6_db;charset=utf8mb4';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    // Set error mode to Exception for easier debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

/****************************************
 * 2. Handle the form submission (Add Job)
 ****************************************/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form inputs safely
    $jobTitle       = $_POST['job_title'] ?? '';
    $jobLocation    = $_POST['location'] ?? '';
    $jobDescription = $_POST['description'] ?? '';

    // Insert into 'jobs' table
    $sql  = "INSERT INTO jobs (name, description, location, posted_at) 
             VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$jobTitle, $jobDescription, $jobLocation]);

    // Optional: Redirect or show a success message
    // header('Location: manage_jobs.php');
    // exit;
}

/****************************************
 * 3. Fetch all jobs to display
 ****************************************/
$sql  = "SELECT * FROM jobs ORDER BY posted_at DESC";
$stmt = $pdo->query($sql);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Jobs</title>
  <!-- Your CSS Files -->
  <link rel="stylesheet" href="/assets/css/employer.css">
  <link rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <!-- Top Bar / Header -->
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

  <!-- Main Wrapper -->
  <div class="dashboard-wrapper">
    <!-- Sidebar -->
    <?php include '../../../components/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="dashboard-content">
      <h1>Manage Jobs</h1>

      <!-- (A) Form to Add New Job -->
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

      <!-- (B) Jobs Table -->
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
                <!-- Edit and Delete placeholders -->
                <a href="edit_job.php?id=<?php echo $job['id']; ?>">Edit</a> | 
                <a href="delete_job.php?id=<?php echo $job['id']; ?>">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
          <!-- If no jobs exist, you could display a fallback row -->
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
