<?php
// pages/dashboard/jobseeker/applications.php
session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Applications</title>
    <link rel="stylesheet" href="/assets/css/jobseeker.css">
</head>
<body>
    <?php include '../../../components/sidebar.php'; ?>

    <main class="dashboard-content">
        <h1>Job Applications</h1>
        <table>
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Company</th>
                    <th>Date Applied</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Software Engineer</td>
                    <td>ABC Corp</td>
                    <td>2025-03-01</td>
                    <td>Under Review</td>
                </tr>
                <tr>
                    <td>Front-end Developer</td>
                    <td>XYZ Inc</td>
                    <td>2025-02-25</td>
                    <td>Interview Scheduled</td>
                </tr>
            </tbody>
        </table>
    </main>
</body>
</html>
