<?php
// lib/models/employee_model.php

require_once __DIR__ . '/../db.php';

class EmployeeModel
{
    /**
     * Insert a new employee record
     */
    public static function addEmployee($data)
    {
        $pdo = getPDO();
        $sql = "INSERT INTO employees (emp_no, name, address, sex, age, contact_no, position)
                VALUES (:empNo, :name, :address, :sex, :age, :contact_no, :position)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':empNo'      => $data['empNo'],
            ':name'       => $data['empName'],
            ':address'    => $data['empAddress'],
            ':sex'        => $data['empSex'],
            ':age'        => $data['empAge'],
            ':contact_no' => $data['empContact'],
            ':position'   => $data['empPosition']
        ]);
    }

    /**
     * Update an existing employee record
     */
    public static function updateEmployee($id, $data)
    {
        $pdo = getPDO();
        $sql = "UPDATE employees 
                SET emp_no     = :empNo,
                    name       = :name,
                    address    = :address,
                    sex        = :sex,
                    age        = :age,
                    contact_no = :contact_no,
                    position   = :position
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':empNo'      => $data['empNo'],
            ':name'       => $data['empName'],
            ':address'    => $data['empAddress'],
            ':sex'        => $data['empSex'],
            ':age'        => $data['empAge'],
            ':contact_no' => $data['empContact'],
            ':position'   => $data['empPosition'],
            ':id'         => $id
        ]);
    }

    /**
     * Delete an employee by ID
     */
    public static function deleteEmployee($id)
    {
        $pdo = getPDO();
        $sql = "DELETE FROM employees WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    /**
     * Fetch employees (or search by a value)
     */
    public static function getEmployees($searchValue = '')
    {
        $pdo = getPDO();
        if (!empty($searchValue)) {
            $sql = "SELECT * FROM employees 
                    WHERE emp_no LIKE :search
                       OR name LIKE :search
                       OR address LIKE :search
                       OR sex LIKE :search
                       OR contact_no LIKE :search
                       OR position LIKE :search";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':search' => "%$searchValue%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $sql = "SELECT * FROM employees";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
