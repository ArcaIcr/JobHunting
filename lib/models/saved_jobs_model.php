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
?>
