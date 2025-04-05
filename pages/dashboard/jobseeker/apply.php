<?php
// pages/dashboard/jobseeker/apply.php

session_start();
require_once '../../../lib/auth.php';
requireRole('jobseeker');

// Include models for applications and jobs
require_once '../../../lib/models/application_model.php';
require_once '../../../lib/models/jobs_model.php';

// Get job_id from GET parameter
if (!isset($_GET['job_id'])) {
    die("No job specified.");
}
$job_id = $_GET['job_id'];

// Retrieve job details; ensure getJobById() exists in your jobs model.
$job = getJobById($job_id);
if (!$job) {
    die("Job not found.");
}

$jobseekerId   = $_SESSION['loggedInUser']['id'];
$applicantName = $_SESSION['loggedInUser']['username']; // Using username as applicant name

$resumeFilename = ''; // Initialize resume filename
$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process resume file upload
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        // Validate file type
        $allowedMime = 'application/pdf';
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $_FILES['resume']['tmp_name']);
        finfo_close($fileInfo);

        if ($mimeType !== $allowedMime) {
            $errorMessage .= "Invalid file type for resume. Please upload a PDF file. ";
        } else {
            // Generate a unique filename for the resume
            $extension = 'pdf';
            $resumeFilename = uniqid('resume_', true) . '.' . $extension;
            $destination = $_SERVER['DOCUMENT_ROOT'] . '/assets/resumes/' . $resumeFilename;

            // Ensure destination directory exists
            if (!is_dir(dirname($destination))) {
                mkdir(dirname($destination), 0755, true);
            }

            if (!move_uploaded_file($_FILES['resume']['tmp_name'], $destination)) {
                $errorMessage .= "Failed to upload resume. ";
            }
        }
    } else {
        $errorMessage .= "Please attach your resume in PDF format. ";
    }

    // Only proceed if there are no errors
    if (empty($errorMessage)) {
        if (applyForJob($job_id, $jobseekerId, $applicantName, $resumeFilename)) {
            $successMessage = "Application submitted successfully!";
            header("Location: applications.php?success=1");
            exit;
        } else {
            $errorMessage .= "Failed to submit application. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply for Job</title>
    <link rel="stylesheet" href="/assets/css/jobseeker.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Modernized styling for the apply page */
        .apply-hero {
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            color: #fff;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 2rem;
        }
        .apply-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .apply-hero p {
            font-size: 1.2rem;
            margin: 0;
        }
        .apply-card {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            max-width: 700px;
            margin: 0 auto;
        }
        .apply-card h2 {
            margin-bottom: 1rem;
        }
        .apply-card p {
            margin-bottom: 1rem;
        }
        .apply-card form > div {
            margin-bottom: 1rem;
        }
        .apply-card label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .apply-card input[type="file"] {
            width: 100%;
        }
        .apply-card button {
            background: var(--primary);
            color: #fff;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }
        .apply-card button:hover {
            background: #d65a3f;
        }
        .feedback {
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 4px;
        }
        .feedback.success {
            background-color: #d4edda;
            color: #155724;
        }
        .feedback.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <!-- Shared Top Bar/Header -->
    <?php include '../../../components/d-header.php'; ?>

    <!-- Main Wrapper for Sidebar and Content -->
    <div class="dashboard-wrapper">
        <?php include '../../../components/sidebar.php'; ?>
        <main class="dashboard-content">
            <div class="apply-hero">
                <h1>Apply for Job</h1>
                <p>Submit your application along with your resume</p>
            </div>
            <div class="apply-card">
                <?php if (!empty($errorMessage)): ?>
                    <div class="feedback error"><?php echo htmlspecialchars($errorMessage); ?></div>
                <?php endif; ?>
                <?php if (!empty($successMessage)): ?>
                    <div class="feedback success"><?php echo htmlspecialchars($successMessage); ?></div>
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($job['name']); ?></h2>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($job['description']); ?></p>
                <form method="POST" action="apply.php?job_id=<?php echo $job_id; ?>" enctype="multipart/form-data">
                    <div>
                        <label for="resume">Attach Your Resume (PDF only):</label>
                        <input type="file" id="resume" name="resume" accept="application/pdf" required>
                    </div>
                    <button type="submit">Submit Application</button>
                </form>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('dashboardSidebar');
            sidebar.classList.toggle('collapsed');
        }
    </script>
</body>
</html>
