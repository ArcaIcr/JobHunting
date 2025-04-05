<?php
// lib/models/application_model.php

require_once __DIR__ . '/../db.php';  // Ensure getPDO() is available

/**
 * Fetch all job applications for the given jobseeker.
 * Assumes:
 *   - The applications table has a 'job_id', 'user_id', 'date_applied', and 'status' column.
 *   - The jobs table contains a 'name' column for the job title and an 'employer_id' column.
 *   - The employer_profiles table contains a 'company_name' column and links to jobs via 'user_id' in employer_profiles.
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
 * Insert a new application for a given job.
 *
 * @param int $job_id
 * @param int $jobseekerId
 * @param string $applicantName
 * @return bool
 */
function applyForJob($job_id, $jobseekerId, $applicantName) {
    $pdo = getPDO();
    $sql = "INSERT INTO applications (job_id, user_id, applicant_name, date_applied, status) 
            VALUES (?, ?, ?, NOW(), 'Under Review')";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$job_id, $jobseekerId, $applicantName]);
}
?>