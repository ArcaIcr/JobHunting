<?php
// pages/dashboard/jobseeker/profile.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');

require_once '../../../lib/models/user_model.php';

// Get the current user ID from session and fetch user info from DB
$userId = $_SESSION['loggedInUser']['id'];
$user   = getUserById($userId);

$successMessage = '';
$errorMessage   = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Handle Profile Picture Upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $_FILES['avatar']['tmp_name']);
        finfo_close($fileInfo);

        if (!in_array($mimeType, $allowedMimeTypes)) {
            $errorMessage .= "Invalid file type for profile picture. ";
        } else {
            // Generate a unique filename for the avatar
            $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $avatarFilename = uniqid('avatar_', true) . '.' . $extension;
            $destination = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/avatars/' . $avatarFilename;

            // Ensure destination directory exists
            if (!is_dir(dirname($destination))) {
                mkdir(dirname($destination), 0755, true);
            }

            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
                $errorMessage .= "Failed to upload profile picture. ";
            } else {
                // Update the user's avatar in the database
                if (updateUserAvatar($userId, $avatarFilename)) {
                    $successMessage .= "Profile picture updated successfully! ";
                } else {
                    $errorMessage .= "Failed to update profile picture in the database. ";
                }
            }
        }
    }

    // 2) Handle Email Update
    $email = $_POST['email'] ?? '';
    if (!empty($email)) {
        if (updateUserEmail($userId, $email)) {
            $successMessage .= "Email updated successfully! ";
        } else {
            $errorMessage .= "Failed to update email. ";
        }
    }

    // 3) Handle Password Update
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!empty($currentPassword) && !empty($newPassword) && !empty($confirmPassword)) {
        if (password_verify($currentPassword, $user['password'])) {
            if ($newPassword === $confirmPassword) {
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                if (updateUserPassword($userId, $hashedNewPassword)) {
                    $successMessage .= "Password updated successfully! ";
                } else {
                    $errorMessage .= "Failed to update password. ";
                }
            } else {
                $errorMessage .= "New passwords do not match. ";
            }
        } else {
            $errorMessage .= "Current password is incorrect. ";
        }
    }

    // Refresh user data from DB and update session accordingly
    $user = getUserById($userId);
    $_SESSION['loggedInUser']['email']  = $user['email'];
    $_SESSION['loggedInUser']['avatar'] = $user['avatar'] ?? null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Jobseeker Profile</title>
  <link rel="stylesheet" href="/assets/css/jobseeker.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 
</head>
<body>
  <!-- Top Bar -->
  <?php include '../../../components/d-header.php'; ?>

  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <div class="profile-hero">
        <h1>User Settings</h1>
        <p>Manage your personal details, profile picture, and password</p>
      </div>

      <div class="profile-card">
        <!-- Feedback messages -->
        <?php if (!empty($successMessage)): ?>
          <div class="feedback success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
          <div class="feedback error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <div class="avatar-container">
          <img src="/assets/images/avatars/<?php echo htmlspecialchars($user['avatar'] ?? 'default.png'); ?>" alt="Profile Picture">
        </div>
        
        <form method="POST" action="profile.php" enctype="multipart/form-data">
          <div class="input-group">
            <label for="avatar">Upload New Profile Picture:</label>
            <input type="file" id="avatar" name="avatar" accept="image/*">
          </div>
          <div class="input-group">
            <label>Username (read-only):</label>
            <input type="text" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" readonly>
          </div>
          <div class="input-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
          </div>
          <hr>
          <div class="input-group">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password">
          </div>
          <div class="input-group">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password">
          </div>
          <div class="input-group">
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password">
          </div>
          <button type="submit">Update Settings</button>
        </form>
      </div>
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
