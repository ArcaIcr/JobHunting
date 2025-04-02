<?php
session_start();

// Initialize a variable for messages
$responseMessage = '';

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $messageContent = trim($_POST['message'] ?? '');
    
    if ($name && $email && $phone && $subject && $messageContent) {
        // Here you could add logic to save the message to a database or send an email.
        $responseMessage = "Your message has been sent!";
        $responseColor = "green";
    } else {
        $responseMessage = "Please fill out all fields.";
        $responseColor = "red";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trabaho Nasipit - Contact Us</title>
  <style>
    /* Root variables and basic reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    :root {
      --red: #FF0000;
    }
    /* Background styling */
    body {
      background: url('/assets/images/backg.jpg') no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    /* Fixed header */
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
    /* Contact section styling */
    .contact-section {
      margin-top: 100px; /* leave room for header */
      width: 100%;
      padding: 2rem;
      display: flex;
      justify-content: center;
    }
    .contact-container {
      background: #fff;
      padding: 40px;
      border-radius: 10px;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }
    .contact-container h2 {
      text-align: center;
      margin-bottom: 1rem;
      font-size: 2.5rem;
      color: #333;
    }
    .contact-container p {
      text-align: center;
      margin-bottom: 2rem;
      color: #666;
    }
    .contact-container form {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }
    .contact-container form label {
      font-size: 1.2rem;
      color: #333;
    }
    .contact-container form input,
    .contact-container form textarea {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1rem;
    }
    .contact-container form button {
      padding: 0.75rem;
      background: #0077cc;
      color: #fff;
      border: none;
      border-radius: 5px;
      font-size: 1.2rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .contact-container form button:hover {
      background: #005fa3;
    }
    #responseMessage {
      text-align: center;
      margin-top: 1rem;
      font-size: 1.2rem;
    }
  </style>
</head>
<body>
 <?php include '../../components/header.php'; ?>
  <section class="contact-section">
    <div class="contact-container">
      <h2>Contact Us</h2>
      <p>Have any questions? We'd love to hear from you.</p>
      <form id="contactForm" method="post" action="contact.php">
        <div>
          <label for="name">Name</label>
          <input type="text" id="name" name="name" placeholder="Your Name" required>
        </div>
        <div>
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Your Email" required>
        </div>
        <div>
          <label for="phone">Phone</label>
          <input type="tel" id="phone" name="phone" placeholder="Your Phone Number" required>
        </div>
        <div>
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject" placeholder="Subject" required>
        </div>
        <div>
          <label for="message">Message</label>
          <textarea id="message" name="message" rows="4" placeholder="Your Message" required></textarea>
        </div>
        <button type="submit">Send Message</button>
      </form>
      <?php if (!empty($responseMessage)) : ?>
        <p id="responseMessage" style="color: <?php echo $responseColor; ?>;"><?php echo $responseMessage; ?></p>
      <?php endif; ?>
    </div>
  </section>
  <script>
    // Optional: you could add JavaScript for additional form interactivity here.
    document.getElementById("contactForm").addEventListener("submit", function(event) {
      // This event listener is optional because PHP handles the form processing.
      // It prevents the default submission only if needed.
      // event.preventDefault();
      // this.submit();
    });
  </script>
</body>
</html>
