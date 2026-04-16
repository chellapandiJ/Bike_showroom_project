<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';

// Fetch Counts
$staffCount = $conn->query("SELECT * FROM users WHERE role='staff'")->num_rows;
$customerCount = $conn->query("SELECT * FROM users WHERE role='customer'")->num_rows;
$bikeCount = $conn->query("SELECT * FROM bikes")->num_rows;
$orderCount = $conn->query("SELECT * FROM orders")->num_rows;
?>
<?php include '../includes/header.php'; ?>

<div class="container-fluid mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar d-none d-md-block">
            <h4 class="text-warning text-center mb-4">ADMIN PANEL</h4>
            <a href="dashboard.php" class="active"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="manage_staff.php"><i class="fas fa-users-cog"></i> Staff Management</a>
            <a href="manage_bikes.php"><i class="fas fa-motorcycle"></i> Bike Management</a>
            <a href="manage_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
            <a href="reports.php"><i class="fas fa-file-invoice-dollar"></i> Reports</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <h2 class="mb-4 text-uppercase">Admin Dashboard</h2>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card bg-dark text-white p-3 border-warning">
                        <h3><?php echo $bikeCount; ?></h3>
                        <p>Total Bikes</p>
                        <i class="fas fa-motorcycle fa-2x text-warning position-absolute end-0 top-0 m-3"></i>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card bg-dark text-white p-3 border-info">
                        <h3><?php echo $staffCount; ?></h3>
                        <p>Total Staff</p>
                        <i class="fas fa-user-tie fa-2x text-info position-absolute end-0 top-0 m-3"></i>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card bg-dark text-white p-3 border-success">
                        <h3><?php echo $customerCount; ?></h3>
                        <p>Total Customers</p>
                        <i class="fas fa-users fa-2x text-success position-absolute end-0 top-0 m-3"></i>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card bg-dark text-white p-3 border-danger">
                        <h3><?php echo $orderCount; ?></h3>
                        <p>Total Orders</p>
                        <i class="fas fa-shopping-bag fa-2x text-danger position-absolute end-0 top-0 m-3"></i>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card p-3">
                        <h4 class="card-title">Recent Orders</h4>
                        <p>View the latest bike bookings.</p>
                        <a href="manage_orders.php" class="btn btn-venum">View Orders</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3">
                        <h4 class="card-title">Add New Staff</h4>
                        <p>Onboard new staff members.</p>
                        <a href="manage_staff.php" class="btn btn-outline-warning">Add Staff</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
