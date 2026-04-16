<?php
require_once 'includes/db.php';

$sql = "CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    bike_id INT,
    customer_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (bike_id) REFERENCES bikes(bike_id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'bookings' created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}
?>
