<?php
// Configuration
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "bike_showroom";

// 1. Connect to MySQL Server (without selecting DB)
echo "Connecting to MySQL server...\n";
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

// 2. Create Database
echo "Creating database '$dbname' if it doesn't exist...\n";
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.\n";
} else {
    die("Error creating database: " . $conn->error . "\n");
}

// 3. Select Database
$conn->select_db($dbname);

// 4. Read SQL file
$sqlFile = 'database.sql';
if (!file_exists($sqlFile)) {
    die("Error: database.sql file not found.\n");
}

echo "Reading database.sql...\n";
$sqlContent = file_get_contents($sqlFile);

// 5. Execute Multi-Query
echo "Importing tables and data...\n";
if ($conn->multi_query($sqlContent)) {
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            $result->free();
        }
        // Check if there are more results
    } while ($conn->next_result());
    echo "Database setup completed successfully!\n";
    echo "You can now delete this file and refresh your website.\n";
} else {
    echo "Error importing SQL: " . $conn->error . "\n";
}

$conn->close();
?>
