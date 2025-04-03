<?php
// pages/dashboard/jobseeker/profile.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jobseeker Profile</title>
    <link rel="stylesheet" href="/assets/css/jobseeker.css">
</head>
<body>
    <?php include '../../../components/sidebar.php'; ?>

    <main class="dashboard-content">
        <h1>Your Profile</h1>
        <form action="profile.php" method="post">
            <div class="input-group">
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($_SESSION['loggedInUser']['fullname'] ?? ''); ?>" required>
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['loggedInUser']['email'] ?? ''); ?>" required>
            </div>
            <button type="submit">Update Profile</button>
        </form>
    </main>
</body>
</html>
