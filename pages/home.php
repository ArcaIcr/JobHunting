<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trabaho Nasipit</title>
  <style>
    /* Basic Reset */
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
    /* Hero Section */
    .hero {
      margin-top: 100px; /* Space for header */
      width: 100%;
      padding: 4rem 2rem;
      text-align: center;
      color: #fff;
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
    }
    .hero h1 {
      font-size: 3rem;
      margin-bottom: 1rem;
    }
    .hero p {
      font-size: 1.5rem;
      margin-bottom: 2rem;
    }
    .hero .btn {
      background: #333;
      color: #fff;
      padding: 1rem 2rem;
      border-radius: 5rem;
      text-decoration: none;
      font-size: 1.2rem;
      transition: background 0.3s ease;
    }
    .hero .btn:hover {
      background: var(--red);
    }
    /* Responsive Design */
    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.5rem;
      }
      .hero p {
        font-size: 1.2rem;
      }
    }
  </style>
</head>
<body>
  <?php include '../components/header.php'; ?>
  <section class="hero">
    <h1>WELCOME TO TRABAHONASIPIT WEB</h1>
    <p>Your gateway to finding your next job opportunity.</p>
    <a href="findjob.php" class="btn">Find a Job Now</a>
  </section>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      console.log("Home page loaded successfully");
    });
  </script>
</body>
</html>
