<?php
// config/config.php

$servername = "localhost";
$username = "root";       // Default for XAMPP
$password = "";           // Default for XAMPP (often empty)
$dbname = "system_g6_db"; // Your database name

// Create connection using mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
