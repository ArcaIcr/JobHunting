<?php
// pages/dashboard/employer/post_job.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jobTitle = trim($_POST['jobTitle']);
    $jobDescription = trim($_POST['jobDescription']);
    $location = trim($_POST['location']);
    // Insert job posting into your database here
    $successMessage = "Job posted successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post a New Job</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include '../../../components/header.php'; ?>
    <?php include '../../../components/sidebar.php'; ?>

    <main class="dashboard-content">
        <h1>Post a New Job</h1>
        <?php if (isset($successMessage)) echo "<p>$successMessage</p>"; ?>
        <form method="post" action="post_job.php">
            <div class="input-group">
                <label for="jobTitle">Job Title:</label>
                <input type="text" id="jobTitle" name="jobTitle" required>
            </div>
            <div class="input-group">
                <label for="jobDescription">Job Description:</label>
                <textarea id="jobDescription" name="jobDescription" required></textarea>
            </div>
            <div class="input-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>
            </div>
            <button type="submit">Post Job</button>
        </form>
    </main>

    <?php include '../../../components/footer.php'; ?>
</body>
</html>
