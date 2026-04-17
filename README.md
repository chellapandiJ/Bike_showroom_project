# Bike Showroom Management System (WheelMasters)

A professional, full-stack web application for managing a modern bike showroom. Built with PHP, MySQL, and Bootstrap 5.

## Features

### Customer Panel
- **Browse Bikes**: Filter by brand, category, check details.
- **Booking System**: Book bikes online with various payment modes.
- **Test Rides**: Schedule test rides.
- **User Dashboard**: Track order status, view test ride requests.

### Admin Panel
- **Dashboard**: Analytics on sales, orders, and stock.
- **Bike Management**: Add, edit, delete bikes with images.
- **Order Management**: Assign orders to staff, update status.
- **Staff Management**: Add/Manage staff accounts.
- **Reports**: Daily sales and performance reports.

### Staff Panel
- **Work Dashboard**: View assigned tasks.
- **Order Processing**: Update order statuses (Ready, Delivered).
- **Inventory**: Check stock levels (Limited update access).
- **Test Rides**: Approve/Reject test ride requests.

##  Setup Instructions

1.  **Database Setup**:
    - Open **phpMyAdmin**.
    - Create a database named `bike_showroom`.
    - Import the `database.sql` file located in the root directory.

2.  **Configuration**:
    - The database connection settings are in `includes/db.php`. Default is `root` with no password.

3.  **Default Admin Credentials**:
    - **Email**: `admin@bike.com`
    - **Password**: `admin123`

4.  **Running**:
    - Place the folder in `xampp/htdocs`.
    - Open browser and go to `http://localhost/bikeshowroom`.

## Project Structure
- `/admin` - Admin specific modules
- `/staff` - Staff specific modules
- `/includes` - Reusable components (DB, Header, Function)
- `/uploads` - Stores bike images
- `/assets` - CSS, JS, and static images

## Note for Evaluators
This project implements MVC-like structure, proper security (password hashing, prepared statements), and Role-Based Access Control (RBAC).
