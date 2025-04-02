<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trabaho Nasipit</title>
  <!-- Link to the global CSS file -->
  <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>
  <!-- Include the modular header -->
  <?php include '../../components/header.php'; ?>

  <!-- Hero Section -->
  <section class="hero">
    <h1>WELCOME TO TRABAHONASIPIT WEB</h1>
    <p>Your gateway to finding your next job opportunity.</p>
    <a href="../findjob.php" class="btn">Find a Job Now</a>
  </section>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      console.log("Home page loaded successfully");
    });
  </script>
</body>
</html>
