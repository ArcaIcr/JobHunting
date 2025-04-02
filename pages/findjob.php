<?php
// findjob.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config/config.php';

// Initialize variables
$jobs = [];
$searchPerformed = false;

if (isset($_GET['job-name']) && isset($_GET['location'])) {
    $searchPerformed = true;
    $jobName = trim($_GET['job-name']);
    $location = trim($_GET['location']);
    
    // Use a prepared statement to search for available jobs matching the title and location
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
  <style>
    /* Root variables and basic resets */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    :root {
      --red: #FF0000;
    }
    /* Background and Layout */
    body {
      background: url('/assets/images/backg.jpg') no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    /* Fixed Header */
    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: rgba(255, 255, 255, 0.95);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      z-index: 100;
      width: 100%;
    }
    header .logo {
      font-size: 1.8rem;
      font-weight: bold;
      color: #333;
    }
    header .navbar a {
      margin-left: 1rem;
      text-decoration: none;
      color: #555;
      font-size: 1rem;
      transition: color 0.3s ease;
    }
    header .navbar a:hover {
      color: var(--red);
    }
    /* Search Container */
    .search-container {
      width: 100%;
      padding-top: 150px; /* Leaves room for the fixed header */
      display: flex;
      justify-content: center;
    }
    .search-box {
      background: rgba(255, 229, 229, 0.5); /* Light pink with transparency */
      padding: 20px;
      border: 2px solid whitesmoke;
      border-radius: 5px;
      width: 90%;
      max-width: 600px;
      text-align: left;
    }
    .search-box label {
      font-size: 1.2rem;
      margin-bottom: 10px;
      display: block;
      color: #333;
    }
    .search-box input,
    .search-box select {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 4px;
      margin-bottom: 15px;
    }
    #search-btn {
      background: grey;
      color: white;
      border: none;
      padding: 10px;
      width: 100%;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
      transition: background 0.3s ease;
    }
    #search-btn:hover {
      background: var(--red);
    }
    /* Job Results */
    .job-results {
      margin: 20px 2rem;
      padding: 20px;
      background: #fff;
      border-radius: 5px;
      box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
      max-width: 800px;
      width: 100%;
    }
    .job-results h2 {
      margin-bottom: 20px;
      font-size: 1.8rem;
      color: #333;
      text-align: center;
    }
    .job-item {
      border-bottom: 1px solid #ccc;
      padding: 15px 0;
    }
    .job-item:last-child {
      border-bottom: none;
    }
    .job-item h3 {
      font-size: 1.5rem;
      color: #007BFF;
      margin-bottom: 8px;
    }
    .job-item p {
      font-size: 1rem;
      color: #555;
      margin-bottom: 5px;
    }
    .job-item p strong {
      color: #333;
    }
  </style>
</head>
<body>
  <header>
    <a href="#" class="logo">trabahosanasipit <span>.</span></a>
    <nav class="navbar">
      <a href="home.php">Home</a>
      <a href="findjob.php">Job Search</a>
      <a href="cooperation.php">Cooperation</a>
      <a href="contact.php">Contact</a>
      <a href="login.php">Login</a>
      <a href="signup.php">Register</a>
    </nav>
  </header>
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
