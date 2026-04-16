<?php
// Database Connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "bike_showroom";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");

// Start Session globally if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
