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

/* Existing functions for employer_profiles... */
function getCompanyProfile($userId) {
    $pdo = getPDO();
    $sql = "SELECT * FROM employer_profiles WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateCompanyProfile($userId, $companyName, $companyWebsite, $companyDescription, $location, $contactEmail, $contactPhone, $logoFilename) {
    $pdo = getPDO();
    $sql = "UPDATE employer_profiles
            SET company_name = ?, company_website = ?, company_description = ?, location = ?, contact_email = ?, contact_phone = ?, logo = ?
            WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$companyName, $companyWebsite, $companyDescription, $location, $contactEmail, $contactPhone, $logoFilename, $userId]);
}

function createCompanyProfile($userId, $companyName, $companyWebsite, $companyDescription, $location, $contactEmail, $contactPhone, $logoFilename) {
    $pdo = getPDO();
    $sql = "INSERT INTO employer_profiles (user_id, company_name, company_website, company_description, location, contact_email, contact_phone, logo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$userId, $companyName, $companyWebsite, $companyDescription, $location, $contactEmail, $contactPhone, $logoFilename]);
}

/* --- New functions for handling companies table --- */

/**
 * Fetches all companies from the companies table.
 */
function getAllCompanies() {
    $pdo = getPDO();
    $sql = "SELECT * FROM companies ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Gets a single company by its ID.
 */
function getCompanyById($id) {
    $pdo = getPDO();
    $sql = "SELECT * FROM companies WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Adds a new company to the companies table.
 */
function addCompany($name, $address, $contact) {
    $pdo = getPDO();
    $sql = "INSERT INTO companies (name, address, contact) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$name, $address, $contact]);
}

/**
 * Updates an existing company.
 */
function updateCompany($id, $name, $address, $contact) {
    $pdo = getPDO();
    $sql = "UPDATE companies SET name = ?, address = ?, contact = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$name, $address, $contact, $id]);
}

/**
 * Deletes a company.
 */
function deleteCompany($id) {
    $pdo = getPDO();
    $sql = "DELETE FROM companies WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}
?>
