<div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
             <h5 class="text-white">Admin Panel</h5>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active bg-primary text-white' : 'text-white-50'; ?>" href="dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_orders.php' ? 'active bg-primary text-white' : 'text-white-50'; ?>" href="manage_orders.php">
                    <i class="fas fa-shopping-cart me-2"></i> Orders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_bookings.php' ? 'active bg-primary text-white' : 'text-white-50'; ?>" href="manage_bookings.php">
                    <i class="fas fa-file-contract me-2"></i> Bookings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_bikes.php' ? 'active bg-primary text-white' : 'text-white-50'; ?>" href="manage_bikes.php">
                    <i class="fas fa-motorcycle me-2"></i> Bikes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_brands.php' ? 'active bg-primary text-white' : 'text-white-50'; ?>" href="manage_brands.php">
                    <i class="fas fa-tags me-2"></i> Brands & Cats
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_staff.php' ? 'active bg-primary text-white' : 'text-white-50'; ?>" href="manage_staff.php">
                    <i class="fas fa-users me-2"></i> Staff
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active bg-primary text-white' : 'text-white-50'; ?>" href="reports.php">
                    <i class="fas fa-chart-line me-2"></i> Reports
                </a>
            </li>

            <li class="nav-item mt-4">
                <a class="nav-link text-danger bg-light rounded" href="../logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>
