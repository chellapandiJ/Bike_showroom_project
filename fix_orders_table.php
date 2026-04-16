<?php
require_once 'includes/db.php';
// Helper to fix missing columns
function addColumnIfNotExists($conn, $table, $column, $definition) {
    try {
        $check = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
        if ($check && $check->num_rows == 0) {
            $conn->query("ALTER TABLE $table ADD COLUMN $column $definition");
            echo "Added column $column to $table.<br>";
        } else {
            echo "Column $column already exists in $table.<br>";
        }
    } catch (Exception $e) {
        echo "Error checking/adding $column: " . $e->getMessage() . "<br>";
    }
}

addColumnIfNotExists($conn, 'orders', 'transaction_id', "VARCHAR(100) AFTER payment_mode");
addColumnIfNotExists($conn, 'orders', 'payment_proof', "VARCHAR(255) AFTER transaction_id");

// Clean the buffer
ob_end_flush();
?>
