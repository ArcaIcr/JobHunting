<?php
// models/jobs_model.php

// Function to get a PDO connection (singleton pattern)
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

// Function to add a new job
function addJob($jobTitle, $jobDescription, $jobLocation) {
    $pdo = getPDO();
    $sql = "INSERT INTO jobs (name, description, location, posted_at) 
            VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$jobTitle, $jobDescription, $jobLocation]);
}

// Function to get all jobs
function getAllJobs() {
    $pdo = getPDO();
    $sql = "SELECT * FROM jobs ORDER BY posted_at DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
