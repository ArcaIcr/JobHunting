<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trabaho Nasipit - Cooperation</title>
  <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
  <?php include '../components/header.php'; ?>
  <div class="container">
    <a href="cooperatives/agong-ong.php" class="card-link">
      <div class="card" style="animation-delay: 0.3s;">
        <img src="https://cdn-icons-png.flaticon.com/512/25/25694.png" alt="Awampco Icon">
        <h2>Agong-ong Watershed Multi-Purpose Cooperative (Awampco)</h2>
        <p>Address: National Road, District 4, Barangay Amontay, Nasipit, 8602</p>
        <p>Contact No.: 0921 965 3955</p>
      </div>
    </a>
    <a href="cooperatives/agusan-banana.php" class="card-link">
      <div class="card" style="animation-delay: 0.6s;">
        <img src="https://cdn-icons-png.flaticon.com/512/25/25694.png" alt="Agusan Banana Icon">
        <h2>Agusan Banana Shippers Multi-Purpose Cooperative</h2>
        <p>Address: X9C2+HPW PPA Office, Nasipit, 8602 Agusan Del Norte</p>
        <p>Contact No.: 0921 965 3955</p>
      </div>
    </a>
    <a href="cooperatives/ficco-nasipit.php" class="card-link">
      <div class="card" style="animation-delay: 0.9s;">
        <img src="https://cdn-icons-png.flaticon.com/512/25/25694.png" alt="FICCO Icon">
        <h2>FICCO NASIPIT</h2>
        <p>Address: X8PV+5PJ, Nasipit Port Rd, Nasipit, Agusan Del Norte</p>
        <p>Contact No.: 0625656899</p>
      </div>
    </a>
  </div>
  <?php include '../components/footer.php'; ?>
</body>
</html>
