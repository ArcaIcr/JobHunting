<?php
// pages/dashboard/employer/company_profile.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Profile</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include '../../../components/header.php'; ?>
    <?php include '../../../components/sidebar.php'; ?>

    <main class="dashboard-content">
        <h1>Company Profile</h1>
        <form method="post" action="company_profile.php">
            <div class="input-group">
                <label for="companyName">Company Name:</label>
                <input type="text" id="companyName" name="companyName" value="<?php echo htmlspecialchars($_SESSION['loggedInUser']['company_name'] ?? ''); ?>" required>
            </div>
            <div class="input-group">
                <label for="companyEmail">Company Email:</label>
                <input type="email" id="companyEmail" name="companyEmail" value="<?php echo htmlspecialchars($_SESSION['loggedInUser']['company_email'] ?? ''); ?>" required>
            </div>
            <div class="input-group">
                <label for="companyDescription">Company Description:</label>
                <textarea id="companyDescription" name="companyDescription" required><?php echo htmlspecialchars($_SESSION['loggedInUser']['company_description'] ?? ''); ?></textarea>
            </div>
            <button type="submit">Update Profile</button>
        </form>
    </main>

    <?php include '../../../components/footer.php'; ?>
</body>
</html>
