<?php
// pages/dashboard/jobseeker/save_job.php

session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');

// Include the saved jobs model
require_once '../../../lib/models/saved_jobs_model.php';

if (!isset($_GET['job_id'])) {
    die("No job specified.");
}

$job_id = $_GET['job_id'];
$jobseekerId = $_SESSION['loggedInUser']['id'];

// Check if the job is already saved
if (hasJobBeenSaved($jobseekerId, $job_id)) {
    header("Location: index.php?message=" . urlencode("Job already saved."));
    exit;
}

// Attempt to save the job
if (saveJobForJobseeker($jobseekerId, $job_id)) {
    header("Location: index.php?message=" . urlencode("Job saved successfully."));
    exit;
} else {
    die("Failed to save job. Please try again.");
}
?>
