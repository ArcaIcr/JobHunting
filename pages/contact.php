<?php
session_start();

// Initialize a variable for messages
$responseMessage = '';

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name           = trim($_POST['name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $subject        = trim($_POST['subject'] ?? '');
    $messageContent = trim($_POST['message'] ?? '');
    
    if ($name && $email && $phone && $subject && $messageContent) {
        // Here you could add logic to save the message to a database or send an email.
        $responseMessage = "Your message has been sent!";
        $responseColor   = "green";
    } else {
        $responseMessage = "Please fill out all fields.";
        $responseColor   = "red";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trabaho Nasipit - Contact Us</title>
  <!-- Link to external CSS -->
  <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
  <!-- Include header -->
  <?php include '../components/header.php'; ?>

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
    // Optional JavaScript for additional form interactivity.
    document.getElementById("contactForm").addEventListener("submit", function(event) {
      // event.preventDefault(); // Uncomment if you need to handle submission via AJAX.
      // this.submit();
    });
  </script>
</body>
</html>
