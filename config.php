<?php
// Database configuration
$localhost = "localhost";
$username = "root";
$password = "";
$database = "bill";

// Create connection
$conn = new mysqli($localhost, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");

// Function to generate unique bill number
function generateBillNumber() {
    return 'BILL-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
}

// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>