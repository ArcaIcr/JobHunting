<?php
// pages/dashboard/employer/process_application.php

session_start();
require_once '../../../lib/auth.php';
requireRole('employer');

// Include the application model (which defines updateApplicationStatus())
require_once '../../../lib/models/application_model.php';
// Also include the database connection if needed (if not already included by application_model.php)
require_once '../../../lib/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // This branch handles the "Schedule Interview" action from the modal form.
    if (!isset($_POST['app_id']) || !isset($_POST['action'])) {
        die("Invalid request.");
    }
    $app_id = $_POST['app_id'];
    $action = $_POST['action'];

    if ($action !== 'interview') {
        die("Invalid action for POST request.");
    }
    
    // Retrieve interview details from POST
    $interview_date     = $_POST['interview_date'] ?? '';
    $interview_time     = $_POST['interview_time'] ?? '';
    $interview_location = $_POST['interview_location'] ?? '';

    if (empty($interview_date) || empty($interview_time) || empty($interview_location)) {
        die("Please provide all interview details.");
    }
    
    // Update the application record with interview details and status
    $pdo = getPDO();
    $sql = "UPDATE applications 
            SET status = 'Interview Scheduled', 
                interview_date = ?, 
                interview_time = ?, 
                interview_location = ?
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$interview_date, $interview_time, $interview_location, $app_id])) {
        header("Location: applications.php?message=" . urlencode("Interview scheduled successfully."));
        exit;
    } else {
        die("Failed to schedule interview.");
    }
} else {
    // This branch handles GET requests for Approve or Reject actions.
    if (!isset($_GET['app_id']) || !isset($_GET['action'])) {
        die("Invalid request.");
    }
    $app_id = $_GET['app_id'];
    $action = $_GET['action'];

    if ($action === 'approve') {
        $newStatus = "Approved";
    } elseif ($action === 'reject') {
        $newStatus = "Rejected";
    } else {
        die("Invalid action.");
    }

    if (updateApplicationStatus($app_id, $newStatus)) {
        header("Location: applications.php?message=" . urlencode("Application updated successfully."));
        exit;
    } else {
        die("Failed to update application status.");
    }
}
?>
