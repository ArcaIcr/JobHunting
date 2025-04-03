<?php
// pages/dashboard/jobseeker/saved_jobs.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Saved Jobs</title>
    <link rel="stylesheet" href="/assets/css/jobseeker.css">
</head>
<body>
    <?php include '../../../components/sidebar.php'; ?>

    <main class="dashboard-content">
        <h1>Saved Jobs</h1>
        <ul>
            <li>
                <a href="#">Senior Developer at Company A</a>
                <span>Saved on: 2025-03-05</span>
            </li>
            <li>
                <a href="#">UI/UX Designer at Company B</a>
                <span>Saved on: 2025-03-07</span>
            </li>
        </ul>
    </main>
</body>
</html>
