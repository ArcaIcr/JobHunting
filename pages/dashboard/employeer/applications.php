<?php
// pages/dashboard/employer/applications.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Applications</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include '../../../components/header.php'; ?>
    <?php include '../../../components/sidebar.php'; ?>

    <main class="dashboard-content">
        <h1>Applications for Your Jobs</h1>
        <table>
            <thead>
                <tr>
                    <th>Applicant Name</th>
                    <th>Job Title</th>
                    <th>Date Applied</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>John Doe</td>
                    <td>Software Engineer</td>
                    <td>2025-03-12</td>
                    <td>Under Review</td>
                </tr>
                <tr>
                    <td>Jane Smith</td>
                    <td>UI/UX Designer</td>
                    <td>2025-03-08</td>
                    <td>Interview Scheduled</td>
                </tr>
            </tbody>
        </table>
    </main>

    <?php include '../../../components/footer.php'; ?>
</body>
</html>
