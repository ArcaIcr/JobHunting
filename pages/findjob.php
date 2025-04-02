<?php
// findjob.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config/config.php';

$jobs = [];
$searchPerformed = false;

if (isset($_GET['job-name']) && isset($_GET['location'])) {
    $searchPerformed = true;
    $jobName = trim($_GET['job-name']);
    $location = trim($_GET['location']);
    
    // Prepared statement for security
    $query = "SELECT * FROM jobs WHERE title LIKE ? AND location = ? AND status = 'available'";
    $stmt = $conn->prepare($query);
    $likeJobName = "%" . $jobName . "%";
    $stmt->bind_param("ss", $likeJobName, $location);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Job Search - Trabaho Nasipit</title>
  <!-- Link to the external CSS file -->
  <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
  <!-- Include header.php for the header and navbar -->
  <?php include '../components/header.php'; ?>

  <section class="search-container">
    <div class="search-box">
      <form method="get" action="findjob.php">
        <label for="job-name"><h2>JOB NAME:</h2></label>
        <input type="text" id="job-name" name="job-name" placeholder="Enter job title" required>
        
        <label for="location"><h2>LOCATION:</h2></label>
        <select id="location" name="location" required>
          <option value="">Select location</option>
          <option value="P1">Poblacion 1</option>
          <option value="P2">Poblacion 2</option>
          <option value="P3">Poblacion 3</option>
          <option value="P4">Poblacion 4</option>
          <option value="P5">Poblacion 5</option>
          <option value="P6">Poblacion 6</option>
          <option value="P7">Poblacion 7</option>
        </select>
        <button id="search-btn" type="submit">SEARCH</button>
      </form>
    </div>
  </section>

  <?php if ($searchPerformed): ?>
    <section class="job-results">
      <h2>Search Results</h2>
      <?php if (count($jobs) > 0): ?>
        <?php foreach ($jobs as $job): ?>
          <div class="job-item">
            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
            <p><?php echo htmlspecialchars($job['description']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
            <p><small>Posted on: <?php echo htmlspecialchars($job['posted_at']); ?></small></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No jobs found matching your search criteria.</p>
      <?php endif; ?>
    </section>
  <?php endif; ?>
</body>
</html>
