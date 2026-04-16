<?php
require_once 'includes/db.php';

// Add columns to users table if they don't exist
$columns = [
    "username" => "VARCHAR(50) UNIQUE AFTER name",
    "gender" => "ENUM('Male', 'Female', 'Other') AFTER phone",
    "dob" => "DATE AFTER gender",
    "age" => "INT AFTER dob"
];

foreach ($columns as $col => $def) {
    try {
        $conn->query("ALTER TABLE users ADD COLUMN $col $def");
        echo "Added column $col<br>";
    } catch (Exception $e) {
        echo "Column $col might already exist or error: " . $e->getMessage() . "<br>";
    }
}

// Update existing users to have a username if empty
$conn->query("UPDATE users SET username = REPLACE(LOWER(name), ' ', '.') WHERE username IS NULL OR username = ''");

echo "Database updated successfully.";
?>
