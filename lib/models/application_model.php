<?php
// lib/models/application_model.php

require_once __DIR__ . '/../db.php';  // Ensure getPDO() is available

/**
 * Fetch all job applications for the given jobseeker.
 * Assumes:
 *   - The applications table has columns: id, job_id, user_id, applicant_name, resume, date_applied, status.
 *   - The jobs table contains the job details (like name).
 *   - The employer_profiles table contains the company_name.
 */
function getApplicationsForJobseeker($jobseekerId) {
    $pdo = getPDO();
    $sql = "SELECT a.*, j.name AS job_title, ep.company_name
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN employer_profiles ep ON j.employer_id = ep.user_id
            WHERE a.user_id = ?
            ORDER BY a.date_applied DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$jobseekerId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Insert a new application for a given job, including a resume file.
 *
 * @param int    $job_id
 * @param int    $jobseekerId
 * @param string $applicantName
 * @param string $resumeFilename (can be empty if no resume was attached)
 * @return bool
 */
function applyForJob($job_id, $jobseekerId, $applicantName, $resumeFilename) {
    $pdo = getPDO();
    $sql = "INSERT INTO applications (job_id, user_id, applicant_name, resume, date_applied, status) 
            VALUES (?, ?, ?, ?, NOW(), 'Under Review')";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$job_id, $jobseekerId, $applicantName, $resumeFilename]);
}

/**
 * Returns the total number of applications sent by the jobseeker.
 */
function getApplicationCountForJobseeker($jobseekerId) {
    $pdo = getPDO();
    $sql = "SELECT COUNT(*) FROM applications WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$jobseekerId]);
    return $stmt->fetchColumn();
}

/**
 * Returns the number of applications with status 'Interview Scheduled'.
 */
function getInterviewScheduledCountForJobseeker($jobseekerId) {
    $pdo = getPDO();
    $sql = "SELECT COUNT(*) FROM applications WHERE user_id = ? AND status = 'Interview Scheduled'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$jobseekerId]);
    return $stmt->fetchColumn();
}

/**
 * Check if the jobseeker has already applied for a specific job.
 *
 * @param int $userId
 * @param int $jobId
 * @return bool
 */
function hasUserApplied($userId, $jobId) {
    $pdo = getPDO();
    $sql = "SELECT COUNT(*) FROM applications WHERE user_id = ? AND job_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId, $jobId]);
    $count = $stmt->fetchColumn();
    return $count > 0;
}

/**
 * Updates the status of an application.
 *
 * @param int $app_id
 * @param string $status
 * @return bool
 */
function updateApplicationStatus($app_id, $status) {
    $pdo = getPDO();
    $sql = "UPDATE applications SET status = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$status, $app_id]);
}


function getApplicationForJob($userId, $jobId) {
    $pdo = getPDO();
    $sql = "SELECT * FROM applications WHERE user_id = ? AND job_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId, $jobId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

?>
