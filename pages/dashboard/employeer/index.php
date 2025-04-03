<?php
// pages/dashboard/employer/index.php
session_start();
require_once '../../../lib/auth.php';
requireRole('employer');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employer Dashboard Home</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include '../../../components/header.php'; ?>
    <?php include '../../../components/sidebar.php'; ?>

    <main class="dashboard-content">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['loggedInUser']['company_name'] ?? $_SESSION['loggedInUser']['username']); ?></h1>
        <section class="stats">
            <div class="card">
                <h3>Jobs Posted</h3>
                <p>5</p>
            </div>
            <div class="card">
                <h3>Total Applications</h3>
                <p>20</p>
            </div>
            <div class="card">
                <h3>Interviews Scheduled</h3>
                <p>3</p>
            </div>
        </section>
    </main>

    <?php include '../../../components/footer.php'; ?>
</body>
</html>
