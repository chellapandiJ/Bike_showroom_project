<?php
require_once 'includes/db.php';

// SQL to add showrooom_accounts table and payments table updates
$sqls = [
    // Create Showroom Accounts (For Admin to add GPay/PhonePe numbers)
    "CREATE TABLE IF NOT EXISTS showroom_accounts (
        account_id INT PRIMARY KEY AUTO_INCREMENT,
        account_name VARCHAR(100) NOT NULL, -- e.g., 'Main GPay', 'Support UPI'
        account_number VARCHAR(100) NOT NULL, -- UPI ID or Phone Number
        qr_code_image VARCHAR(255), -- Path to uploaded QR Code
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    // Update Orders table to store transaction proof if needed
    "ALTER TABLE orders ADD COLUMN transaction_id VARCHAR(100) AFTER payment_mode",
    "ALTER TABLE orders ADD COLUMN payment_proof VARCHAR(255) AFTER transaction_id",
    
    // Ensure users table matches staff requirements (if not already done)
    "ALTER TABLE users 
     ADD COLUMN username VARCHAR(50) UNIQUE AFTER user_id,
     ADD COLUMN gender ENUM('Male', 'Female', 'Other') AFTER phone,
     ADD COLUMN dob DATE AFTER gender,
     ADD COLUMN age INT AFTER dob"
];

foreach ($sqls as $sql) {
    try {
        $conn->query($sql);
        echo "Executed: " . substr($sql, 0, 50) . "...<br>";
    } catch (Exception $e) {
        echo "Skipped/Error: " . $e->getMessage() . "<br>";
    }
}

echo "Database schema updated for Payment & Accounts.";
?>
