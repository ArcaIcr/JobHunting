<?php
// lib/models/jobs_model.php

function getPDO() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn    = 'mysql:host=localhost;dbname=system_g6_db;charset=utf8mb4';
        $dbUser = 'root';
        $dbPass = '';
        try {
            $pdo = new PDO($dsn, $dbUser, $dbPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("DB Connection failed: " . $e->getMessage());
        }
    }
    return $pdo;
}

// Already existing functions:
// addJob($jobTitle, $jobDescription, $jobLocation)
// getAllJobs()

/**
 * Add a new job.
 */
function addJob($jobTitle, $jobDescription, $jobLocation) {
    $pdo = getPDO();
    $sql = "INSERT INTO jobs (name, description, location, posted_at) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$jobTitle, $jobDescription, $jobLocation]);
}



/**
 * Fetch all jobs.
 */
function getAllJobs() {
    $pdo = getPDO();
    $sql = "SELECT * FROM jobs ORDER BY posted_at DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



/**
 * Fetch a single job by ID.
 */
function getJobById($id) {
    $pdo = getPDO();
    $sql = "SELECT * FROM jobs WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Update a job.
 */
function updateJob($id, $jobTitle, $jobDescription, $jobLocation) {
    $pdo = getPDO();
    $sql = "UPDATE jobs
            SET name = ?, description = ?, location = ?
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$jobTitle, $jobDescription, $jobLocation, $id]);
}

/**
 * Delete a job by ID.
 */
function deleteJob($id) {
    $pdo = getPDO();
    $sql = "DELETE FROM jobs WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}
