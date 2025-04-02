<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trabaho Nasipit - Agusan Banana Shippers Multi-Purpose Cooperative</title>
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
    /* Main Content */
    .main-content {
      margin-top: 100px; /* Leaves space for header */
      width: 100%;
      padding: 2rem;
      display: flex;
      justify-content: center;
    }
    .content-container {
      background: rgba(255, 255, 255, 0.98);
      padding: 2rem;
      border-radius: 8px;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      text-align: center;
    }
    .content-container h2 {
      font-size: 2rem;
      color: #333;
      margin-bottom: 1rem;
    }
    .content-container span {
      font-size: 1.1rem;
      color: #333;
      display: block;
      margin-bottom: 1rem;
    }
    .content-container p {
      font-size: 1.1rem;
      color: #555;
      margin-bottom: 1rem;
      line-height: 1.5;
    }
    /* Responsive */
    @media (max-width: 768px) {
      .content-container {
        padding: 1.5rem;
      }
      .content-container h2 {
        font-size: 1.8rem;
      }
      .content-container span,
      .content-container p {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>
 <?php include '../../components/header.php'; ?>
  <main class="main-content">
    <div class="content-container">
      <h2>Agusan Banana Shippers Multi-Purpose Cooperative</h2>
      <span>Address: X9C2+HPW PPA Office, Nasipit, 8602 Agusan Del Norte</span>
      <p>Contact No.: 0921 965 3955</p>
      <p>We empower our members and promote sustainable agricultural practices in Agusan Del Norte.</p>
    </div>
  </main>
</body>
</html>
