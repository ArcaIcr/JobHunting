<?php
// pages/dashboard/employer/user_settings.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

// Include your user model (or wherever you fetch/update user data)
require_once '../../../lib/models/user_model.php';

$userId = $_SESSION['loggedInUser']['id'];
$user   = getUserById($userId);

$successMessage = '';
$errorMessage   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process profile picture upload if provided
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

    // Process email and password updates
    $email = $_POST['email'] ?? '';
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Update email if provided
    if (!empty($email)) {
        if (updateUserEmail($userId, $email)) {
            $successMessage .= "Email updated successfully! ";
        } else {
            $errorMessage .= "Failed to update email. ";
        }
    }

    // Update password if provided
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

    // Refresh user data
    $user = getUserById($userId);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Settings</title>
  <link rel="stylesheet" href="/assets/css/employer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <!-- Top Bar -->
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

  <!-- Wrapper for Sidebar + Content -->
  <div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    <main class="dashboard-content">
      <h1>User Settings</h1>

      <?php if ($successMessage): ?>
        <div class="feedback success">
          <?php echo htmlspecialchars($successMessage); ?>
        </div>
      <?php endif; ?>

      <?php if ($errorMessage): ?>
        <div class="feedback error">
          <?php echo htmlspecialchars($errorMessage); ?>
        </div>
      <?php endif; ?>

      <!-- Form for updating profile picture, email, and password -->
      <form method="POST" action="user_settings.php" enctype="multipart/form-data" class="card">
        <div>
          <label>Current Profile Picture:</label><br>
          <img src="/assets/images/avatars/<?php echo htmlspecialchars($user['avatar'] ?? 'default.png'); ?>" alt="Profile Picture" style="max-width: 150px;">
        </div>
        <div>
          <label for="avatar">Upload New Profile Picture:</label>
          <input type="file" id="avatar" name="avatar" accept="image/*">
        </div>
        <div>
          <label>Username (read-only):</label>
          <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
        </div>
        <div>
          <label>Email:</label>
          <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
        </div>
        <hr>
        <div>
          <label>Current Password:</label>
          <input type="password" name="current_password">
        </div>
        <div>
          <label>New Password:</label>
          <input type="password" name="new_password">
        </div>
        <div>
          <label>Confirm New Password:</label>
          <input type="password" name="confirm_password">
        </div>
        <button type="submit">Update Settings</button>
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
