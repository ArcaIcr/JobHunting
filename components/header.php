<?php
// components/header.php
?>
<style>
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
  header .logo span {
    color: var(--red);
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
</style>
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
