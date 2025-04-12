<?php
// lib/models/Vacancy.php

// Include the database connection – adjust path accordingly.
require_once __DIR__ . '/../db.php';  // Or config.php if that file sets up $conn

class Vacancy {

    // Retrieve all vacancies from the database.
    public static function getAll() {
        global $conn;
        $sql = "SELECT * FROM vacancies ORDER BY created_at DESC";
        $result = $conn->query($sql);
        $vacancies = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()){
                $vacancies[] = $row;
            }
        }
        return $vacancies;
    }

    // Retrieve a single vacancy by its ID.
    public static function getById($id) {
        global $conn;
        $sql = "SELECT * FROM vacancies WHERE id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()){
            $result = $stmt->get_result();
            if ($result && $result->num_rows === 1) {
                return $result->fetch_assoc();
            }
        }
        return null;
    }

    // Insert a new vacancy.
    public static function insert($data) {
        global $conn;
        $sql = "INSERT INTO vacancies (company_name, title, employees_needed, salary, duration, qualification, description, preferred_sex, sector)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        // Bind parameters – adjust types accordingly:
        // s = string, i = integer, d = double/decimal.
        $stmt->bind_param("ssissssss", $data['company_name'], $data['title'], $data['employees_needed'], $data['salary'], $data['duration'], $data['qualification'], $data['description'], $data['preferred_sex'], $data['sector']);
        return $stmt->execute();
    }

    // Update an existing vacancy.
    public static function update($id, $data) {
        global $conn;
        $sql = "UPDATE vacancies SET company_name=?, title=?, employees_needed=?, salary=?, duration=?, qualification=?, description=?, preferred_sex=?, sector=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissssssi", $data['company_name'], $data['title'], $data['employees_needed'], $data['salary'], $data['duration'], $data['qualification'], $data['description'], $data['preferred_sex'], $data['sector'], $id);
        return $stmt->execute();
    }

    // Delete a vacancy by its ID.
    public static function delete($id) {
        global $conn;
        $sql = "DELETE FROM vacancies WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
