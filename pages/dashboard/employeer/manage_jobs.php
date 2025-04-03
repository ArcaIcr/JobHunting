<?php
// pages/dashboard/employer/manage_jobs.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Job Listings</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include '../../../components/header.php'; ?>
    <?php include '../../../components/sidebar.php'; ?>

    <main class="dashboard-content">
        <h1>Manage Job Listings</h1>
        <table>
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Location</th>
                    <th>Date Posted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Software Engineer</td>
                    <td>New York</td>
                    <td>2025-03-10</td>
                    <td>
                        <a href="#">Edit</a> | <a href="#">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>UI/UX Designer</td>
                    <td>San Francisco</td>
                    <td>2025-03-05</td>
                    <td>
                        <a href="#">Edit</a> | <a href="#">Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </main>

    <?php include '../../../components/footer.php'; ?>
</body>
</html>
