-- Database: bike_showroom

CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff', 'customer') NOT NULL DEFAULT 'customer',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS brands (
    brand_id INT PRIMARY KEY AUTO_INCREMENT,
    brand_name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS bikes (
    bike_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    brand_id INT,
    category_id INT,
    price DECIMAL(10,2) NOT NULL,
    engine_cc VARCHAR(50),
    mileage VARCHAR(50),
    fuel_type VARCHAR(50),
    stock INT DEFAULT 0,
    image VARCHAR(255),
    description TEXT,
    status ENUM('available', 'out_of_stock') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES brands(brand_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

CREATE TABLE IF NOT EXISTS orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    bike_id INT,
    staff_id INT DEFAULT NULL,
    booking_amount DECIMAL(10,2),
    payment_mode ENUM('card', 'upi', 'cash', 'netbanking'),
    address TEXT,
    order_status ENUM('Booked', 'Approved', 'Ready', 'Delivered', 'Cancelled') DEFAULT 'Booked',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (bike_id) REFERENCES bikes(bike_id),
    FOREIGN KEY (staff_id) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS test_rides (
    ride_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    bike_id INT,
    staff_id INT DEFAULT NULL,
    requested_date DATE,
    requested_time TIME,
    status ENUM('Pending', 'Approved', 'Completed', 'Cancelled') DEFAULT 'Pending',
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (bike_id) REFERENCES bikes(bike_id),
    FOREIGN KEY (staff_id) REFERENCES users(user_id)
);

-- Default Admin
INSERT INTO users (name, email, phone, password, role, status) VALUES 
('Super Admin', 'admin@bike.com', '1234567890', '$2y$10$YourHashedPasswordHere', 'admin', 'active');
-- Note: User should update password. For demo, use plain text or handle hashing in PHP code if using simple auth for now, or update this insert with a known hash. 
-- For development simplicity, let's assume the PHP code will verify password_verify. 
-- The hash for 'admin123' is $2y$10$r.g/g/g/g... (using a dummy hash for now or rely on the register script to generic one).
-- I will add a raw insert for initial testing if they use plain text (BAD PRACTICE but common in student projects) or valid hash.
-- Let's stick to using PHP's password_hash on the frontend/seed script.
