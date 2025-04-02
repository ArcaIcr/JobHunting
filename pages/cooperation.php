<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trabaho Nasipit - Cooperation</title>
  <style>
    /* Basic Reset and Variables */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    :root {
      --red: #FF0000;
      --light-gray: #f2f2f2;
    }
    /* Body and Background */
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
    /* Container for Cards */
    .container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      margin: 100px auto;
      padding: 0 2rem;
      max-width: 1200px;
    }
    /* Card Styles */
    .card {
      background: var(--light-gray);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
      text-align: center;
      width: 280px;
      opacity: 0;
      transform: translateY(50px);
      animation: fadeInUp 1s ease-in-out forwards;
      cursor: pointer;
      transition: transform 0.3s ease;
    }
    .card:hover {
      transform: scale(1.05) rotate(2deg);
    }
    .card img {
      width: 50px;
      height: 50px;
      margin-bottom: 15px;
    }
    .card h2 {
      font-size: 1.2rem;
      color: #007BFF;
      margin-bottom: 10px;
    }
    .card p {
      font-size: 0.9rem;
      color: #333;
      margin-bottom: 10px;
      line-height: 1.4;
    }
    .card-link {
      text-decoration: none;
      color: inherit;
    }
    /* Animation Keyframes */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    /* Responsive */
    @media (max-width: 768px) {
      .container {
        margin: 80px auto;
      }
      .card {
        width: 90%;
      }
    }
  </style>
</head>
<body>
  
 <?php include '../components/header.php'; ?>
  
  <div class="container">
    <a href="agong-ong.php" class="card-link">
      <div class="card" style="animation-delay: 0.3s;">
        <img src="https://cdn-icons-png.flaticon.com/512/25/25694.png" alt="Awampco Icon">
        <h2>Agong-ong Watershed Multi-Purpose Cooperative (Awampco)</h2>
        <p>Address: National Road, District 4, Barangay Amontay, Nasipit, 8602</p>
        <p>Contact No.: 0921 965 3955</p>
      </div>
    </a>
    <a href="agusan-banana.php" class="card-link">
      <div class="card" style="animation-delay: 0.6s;">
        <img src="https://cdn-icons-png.flaticon.com/512/25/25694.png" alt="Agusan Banana Icon">
        <h2>Agusan Banana Shippers Multi-Purpose Cooperative</h2>
        <p>Address: X9C2+HPW PPA Office, Nasipit, 8602 Agusan Del Norte</p>
        <p>Contact No.: 0921 965 3955</p>
      </div>
    </a>
    <a href="ficco-nasipit.php" class="card-link">
      <div class="card" style="animation-delay: 0.9s;">
        <img src="https://cdn-icons-png.flaticon.com/512/25/25694.png" alt="FICCO Icon">
        <h2>FICCO NASIPIT</h2>
        <p>Address: X8PV+5PJ, Nasipit Port Rd, Nasipit, Agusan Del Norte</p>
        <p>Contact No.: 0625656899</p>
      </div>
    </a>
  </div>
</body>
</html>
