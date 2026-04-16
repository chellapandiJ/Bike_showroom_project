<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Showroom | VENUM EDITION</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/bikeshowroom/assets/css/style.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/bikeshowroom/index.php">
            <i class="fas fa-motorcycle"></i> VENUM BIKES
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/bikeshowroom/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/bikeshowroom/bikes.php">Bikes</a></li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="/bikeshowroom/admin/dashboard.php">Admin Panel</a></li>
                    <?php elseif ($_SESSION['role'] == 'staff'): ?>
                        <li class="nav-item"><a class="nav-link" href="/bikeshowroom/staff/dashboard.php">Staff Panel</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="/bikeshowroom/profile.php">My Profile</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="/bikeshowroom/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/bikeshowroom/login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="/bikeshowroom/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
