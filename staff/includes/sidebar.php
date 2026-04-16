<div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
             <h5 class="text-white">Staff Panel</h5>
             <small class="text-muted">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active bg-primary text-white' : 'text-white-50'; ?>" href="dashboard.php">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'my_tasks.php' ? 'active bg-primary text-white' : 'text-white-50'; ?>" href="my_tasks.php">
                    <i class="fas fa-tasks me-2"></i> My Tasks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'inventory.php' ? 'active bg-primary text-white' : 'text-white-50'; ?>" href="inventory.php">
                    <i class="fas fa-boxes me-2"></i> Inventory Status
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'approved_bookings.php' ? 'active bg-primary text-white' : 'text-white-50'; ?>" href="approved_bookings.php">
                    <i class="fas fa-check-circle me-2"></i> Process Bookings
                </a>
            </li>
             <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'test_rides.php' ? 'active bg-primary text-white' : 'text-white-50'; ?>" href="test_rides.php">
                    <i class="fas fa-motorcycle me-2"></i> Test Rides
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
