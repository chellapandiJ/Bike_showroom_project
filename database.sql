-- Database Schema for Bike Showroom Management System

CREATE DATABASE IF NOT EXISTS bike_showroom;
USE bike_showroom;

-- 1. Users Table (Admin, Staff, Customer)
CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff', 'customer') NOT NULL DEFAULT 'customer',
    address TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Brands Table
CREATE TABLE IF NOT EXISTS brands (
    brand_id INT PRIMARY KEY AUTO_INCREMENT,
    brand_name VARCHAR(50) UNIQUE NOT NULL,
    brand_logo VARCHAR(255)
);

-- 3. Categories Table (e.g., Sports, Cruiser, Commuter)
CREATE TABLE IF NOT EXISTS categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(50) UNIQUE NOT NULL
);

-- 4. Bikes Table
CREATE TABLE IF NOT EXISTS bikes (
    bike_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    brand_id INT,
    category_id INT,
    price DECIMAL(10,2) NOT NULL,
    engine_cc VARCHAR(50),
    mileage VARCHAR(50),
    fuel_type VARCHAR(50),
    power VARCHAR(50),
    torque VARCHAR(50),
    brakes VARCHAR(100),
    tyre_type VARCHAR(100),
    color_variants VARCHAR(255), -- Comma separated colors
    stock INT DEFAULT 0,
    description TEXT,
    is_featured BOOLEAN DEFAULT FALSE,
    status ENUM('available', 'out_of_stock', 'discontinued') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES brands(brand_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

-- 5. Bike Images (Multiple images per bike)
CREATE TABLE IF NOT EXISTS bike_images (
    image_id INT PRIMARY KEY AUTO_INCREMENT,
    bike_id INT,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (bike_id) REFERENCES bikes(bike_id) ON DELETE CASCADE
);

-- 6. Orders (Bookings)
CREATE TABLE IF NOT EXISTS orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    bike_id INT,
    staff_id INT DEFAULT NULL, -- Staff who processed the order
    booking_amount DECIMAL(10,2),
    total_amount DECIMAL(10,2),
    payment_mode ENUM('card', 'upi', 'cash', 'netbanking'),
    payment_status ENUM('Pending', 'Paid', 'Failed') DEFAULT 'Pending',
    delivery_address TEXT,
    order_status ENUM('Booked', 'Confirmed', 'Ready for Delivery', 'Delivered', 'Cancelled') DEFAULT 'Booked',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    delivery_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (bike_id) REFERENCES bikes(bike_id),
    FOREIGN KEY (staff_id) REFERENCES users(user_id)
);

-- 7. Test Rides
CREATE TABLE IF NOT EXISTS test_rides (
    ride_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    bike_id INT,
    staff_id INT DEFAULT NULL,
    requested_date DATE NOT NULL,
    requested_time TIME NOT NULL,
    status ENUM('Pending', 'Approved', 'Completed', 'Cancelled') DEFAULT 'Pending',
    admin_remark TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (bike_id) REFERENCES bikes(bike_id),
    FOREIGN KEY (staff_id) REFERENCES users(user_id)
);

-- 8. Staff Activity Logs
CREATE TABLE IF NOT EXISTS staff_activity_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT,
    activity VARCHAR(255) NOT NULL,
    details TEXT,
    log_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (staff_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- 9. Wishlist
CREATE TABLE IF NOT EXISTS wishlist (
    wishlist_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    bike_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (bike_id) REFERENCES bikes(bike_id) ON DELETE CASCADE
);

-- Default Admin User (Password: admin123)
-- Hash generated using password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (name, email, phone, password, role, status) 
VALUES ('Super Admin', 'admin@bike.com', '9876543210', '$2y$10$8.uXv.E1X.E1X.E1X.E1X.E1X.E1X.E1X.E1X.E1X.E1X.E1', 'admin', 'active')
ON DUPLICATE KEY UPDATE email=email;

-- Sample Data (Brands)
INSERT INTO brands (brand_name) VALUES ('Yamaha'), ('Royal Enfield'), ('Honda'), ('KTM'), ('TVS') ON DUPLICATE KEY UPDATE brand_name=brand_name;
INSERT INTO categories (category_name) VALUES ('Sports'), ('Cruiser'), ('Commuter'), ('Scooter'), ('Off-road') ON DUPLICATE KEY UPDATE category_name=category_name;
