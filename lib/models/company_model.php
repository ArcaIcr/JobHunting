<?php
// lib/models/company_model.php

/**
 * Returns a PDO instance.
 */
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

/**
 * Fetch the company profile for a given employer.
 */
function getCompanyProfile($userId) {
    $pdo = getPDO();
    $sql = "SELECT * FROM employer_profiles WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Update the company profile for a given employer.
 */
function updateCompanyProfile($userId, $companyName, $companyWebsite, $companyDescription, $location, $contactEmail, $contactPhone, $logoFilename) {
    $pdo = getPDO();
    $sql = "UPDATE employer_profiles
            SET company_name = ?, company_website = ?, company_description = ?, location = ?, contact_email = ?, contact_phone = ?, logo = ?
            WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$companyName, $companyWebsite, $companyDescription, $location, $contactEmail, $contactPhone, $logoFilename, $userId]);
}

/**
 * Create a new company profile for a given employer.
 */
function createCompanyProfile($userId, $companyName, $companyWebsite, $companyDescription, $location, $contactEmail, $contactPhone, $logoFilename) {
    $pdo = getPDO();
    $sql = "INSERT INTO employer_profiles (user_id, company_name, company_website, company_description, location, contact_email, contact_phone, logo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$userId, $companyName, $companyWebsite, $companyDescription, $location, $contactEmail, $contactPhone, $logoFilename]);
}
