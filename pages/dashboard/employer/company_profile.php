<?php
// pages/dashboard/employer/company_profile.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

require_once '../../../lib/models/company_model.php';

$userId = $_SESSION['loggedInUser']['id'];

// Fetch existing profile
$profile = getCompanyProfile($userId);

// Handle form submission for updating the profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyName      = $_POST['company_name'] ?? '';
    $companyWebsite   = $_POST['company_website'] ?? '';
    $companyDescription = $_POST['company_description'] ?? '';
    // You can add more fields if needed
    
    // Check if a profile exists
    if ($profile) {
        updateCompanyProfile($userId, $companyName, $companyWebsite, $companyDescription);
    } else {
        createCompanyProfile($userId, $companyName, $companyWebsite, $companyDescription);
    }
    
    // Refresh the profile data
    $profile = getCompanyProfile($userId);
    $successMessage = "Profile updated successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Company Profile</title>
  <link rel="stylesheet" href="/assets/css/employer.css">
  <style>
    /* Add any additional styling you require */
    .profile-card { padding: 1rem; background: #fff; margin-bottom: 1rem; border-radius: 4px; }
    .profile-card img { max-width: 150px; }
    .feedback { padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem; }
    .feedback.success { background-color: #d4edda; color: #155724; }
  </style>
</head>
<body>
  <header class="dashboard-top-bar">
    <div class="left-group">
      <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
      </button>
      <h2>TrabahoNasipit</h2>
    </div>
    <div class="search-bar">
      <input type="text" placeholder="Search...">
    </div>
    <div class="user-profile">
      <img src="/assets/images/profile.png" alt="User">
      <span><?php echo htmlspecialchars($_SESSION['loggedInUser']['username']); ?></span>
    </div>
  </header>

  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>Company Profile</h1>
      <?php if (isset($successMessage)) : ?>
        <div class="feedback success">
          <p><?php echo htmlspecialchars($successMessage); ?></p>
        </div>
      <?php endif; ?>

      <!-- Display company profile -->
      <?php if ($profile): ?>
        <div class="profile-card">
          <!-- If you have a logo, adjust the path accordingly. -->
          <img src="/assets/images/logos/<?php echo htmlspecialchars($profile['logo'] ?? 'default.png'); ?>" alt="Company Logo">
          <h2><?php echo htmlspecialchars($profile['company_name'] ?? ''); ?></h2>
          <p><strong>Description:</strong> <?php echo htmlspecialchars($profile['company_description'] ?? ''); ?></p>
          <p><strong>Location:</strong> <?php echo htmlspecialchars($profile['location'] ?? ''); ?></p>
          <p><strong>Email:</strong> <?php echo htmlspecialchars($profile['contact_email'] ?? ''); ?></p>
          <p><strong>Phone:</strong> <?php echo htmlspecialchars($profile['contact_phone'] ?? ''); ?></p>
          <p><strong>Website:</strong> 
            <a href="<?php echo htmlspecialchars($profile['company_website'] ?? '#'); ?>" target="_blank">
              <?php echo htmlspecialchars($profile['company_website'] ?? ''); ?>
            </a>
          </p>
        </div>
      <?php else: ?>
        <p>No profile information available. Please create your company profile.</p>
      <?php endif; ?>

      <!-- Edit form -->
      <form method="post" action="company_profile.php" class="card" style="margin-top: 2rem;">
        <h2>Edit Company Profile</h2>
        <div>
          <label for="company_name">Company Name:</label>
          <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($profile['company_name'] ?? ''); ?>" required>
        </div>
        <div>
          <label for="company_description">Description:</label>
          <textarea id="company_description" name="company_description" rows="4" required><?php echo htmlspecialchars($profile['company_description'] ?? ''); ?></textarea>
        </div>
        <div>
          <label for="location">Location:</label>
          <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($profile['location'] ?? ''); ?>" required>
        </div>
        <div>
          <label for="contact_email">Email:</label>
          <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($profile['contact_email'] ?? ''); ?>">
        </div>
        <div>
          <label for="contact_phone">Phone:</label>
          <input type="text" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($profile['contact_phone'] ?? ''); ?>">
        </div>
        <div>
          <label for="company_website">Website:</label>
          <input type="url" id="company_website" name="company_website" value="<?php echo htmlspecialchars($profile['company_website'] ?? ''); ?>">
        </div>
        <!-- Optionally, add file input for logo upload -->
        <button type="submit">Update Profile</button>
      </form>
    </main>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('dashboardSidebar');
      sidebar.classList.toggle('collapsed');
    }
  </script>
</body>
</html>
