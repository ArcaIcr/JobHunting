<?php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

// Include the company model file
require_once '../../../lib/models/company_model.php';

$userId = $_SESSION['loggedInUser']['id'];
$profile = getCompanyProfile($userId);

$successMessage = '';
$errorMessage   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data, using null coalescing for safety
    $companyName        = $_POST['company_name'] ?? '';
    $companyWebsite     = $_POST['company_website'] ?? '';
    $companyDescription = $_POST['company_description'] ?? '';
    $location           = $_POST['location'] ?? '';
    $contactEmail       = $_POST['contact_email'] ?? '';
    $contactPhone       = $_POST['contact_phone'] ?? '';
    
    // Process file upload for logo, if provided
    $logoFilename = $profile['logo'] ?? '';  // Keep existing logo by default
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $_FILES['logo']['tmp_name']);
        finfo_close($fileInfo);

        if (in_array($mimeType, $allowedMimeTypes)) {
            // Create a unique filename with original extension
            $extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $logoFilename = uniqid('logo_', true) . '.' . $extension;
            // Define the destination path
            $destination = __DIR__ . '/../../../assets/images/logos/' . $logoFilename;

            if (!move_uploaded_file($_FILES['logo']['tmp_name'], $destination)) {
                $errorMessage = "Failed to upload logo.";
            }
        } else {
            $errorMessage = "Invalid file type. Please upload a JPEG, PNG, or GIF image.";
        }
    }
    
    // Only proceed if there is no error from file upload
    if (!$errorMessage) {
        if ($profile) {
            // Update existing profile (including logo and additional fields)
            if (updateCompanyProfile($userId, $companyName, $companyWebsite, $companyDescription, $location, $contactEmail, $contactPhone, $logoFilename)) {
                $successMessage = "Profile updated successfully!";
            } else {
                $errorMessage = "Failed to update profile.";
            }
        } else {
            // Create a new profile row
            if (createCompanyProfile($userId, $companyName, $companyWebsite, $companyDescription, $location, $contactEmail, $contactPhone, $logoFilename)) {
                $successMessage = "Profile created successfully!";
            } else {
                $errorMessage = "Failed to create profile.";
            }
        }
        // Refresh the profile data
        $profile = getCompanyProfile($userId);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Company Profile</title>
  <link rel="stylesheet" href="/assets/css/employer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <?php include '../../../components/d-header.php'; ?>

  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>Company Profile</h1>
      <?php if ($successMessage): ?>
        <div class="feedback success">
          <p><?php echo htmlspecialchars($successMessage); ?></p>
        </div>
      <?php endif; ?>
      <?php if ($errorMessage): ?>
        <div class="feedback error">
          <p><?php echo htmlspecialchars($errorMessage); ?></p>
        </div>
      <?php endif; ?>

      <!-- Display company profile -->
      <?php if ($profile): ?>
        <div class="profile-card">
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
      <form method="post" action="company_profile.php" enctype="multipart/form-data" class="card">
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
        <div>
          <label for="logo">Company Logo:</label>
          <input type="file" id="logo" name="logo" accept="image/*">
        </div>
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
