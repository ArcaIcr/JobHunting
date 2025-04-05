<?php
// lib/models/saved_jobs_model.php

require_once __DIR__ . '/../db.php';  // Ensure getPDO() is available

/**
 * Returns the total number of jobs saved by the jobseeker.
 */
function getJobsSavedCountForJobseeker($jobseekerId) {
    $pdo = getPDO();
    $sql = "SELECT COUNT(*) FROM saved_jobs WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$jobseekerId]);
    return $stmt->fetchColumn();
}


/**
 * Retrieves the saved jobs for a given jobseeker.
 * Joins saved_jobs with jobs and employer_profiles to get job details.
 *
 * @param int $jobseekerId The ID of the jobseeker.
 * @return array An array of saved jobs.
 */
function getSavedJobsForJobseeker($jobseekerId) {
    $pdo = getPDO();
    $sql = "SELECT sj.*, j.name AS job_title, j.location, j.posted_at, ep.company_name
            FROM saved_jobs sj
            JOIN jobs j ON sj.job_id = j.id
            JOIN employer_profiles ep ON j.employer_id = ep.user_id
            WHERE sj.user_id = ?
            ORDER BY sj.saved_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$jobseekerId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Checks if the jobseeker has already saved a given job.
 *
 * @param int $jobseekerId
 * @param int $jobId
 * @return bool
 */
function hasJobBeenSaved($jobseekerId, $jobId) {
    $pdo = getPDO();
    $sql = "SELECT COUNT(*) FROM saved_jobs WHERE user_id = ? AND job_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$jobseekerId, $jobId]);
    return $stmt->fetchColumn() > 0;
}


/**
 * Saves a job for the jobseeker.
 *
 * @param int $jobseekerId
 * @param int $jobId
 * @return bool
 */
function saveJobForJobseeker($jobseekerId, $jobId) {
    $pdo = getPDO();
    $sql = "INSERT INTO saved_jobs (user_id, job_id, saved_at) VALUES (?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$jobseekerId, $jobId]);
}

?>
