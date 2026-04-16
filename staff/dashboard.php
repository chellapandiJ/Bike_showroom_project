<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['staff']);

$staff_id = $_SESSION['user_id'];

// Stats
$assigned_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE staff_id = $staff_id AND order_status NOT IN ('Delivered', 'Cancelled')")->fetch_row()[0];
$completed_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE staff_id = $staff_id AND order_status = 'Delivered'")->fetch_row()[0];
$pending_test_rides = $conn->query("SELECT COUNT(*) FROM test_rides WHERE status = 'Pending'")->fetch_row()[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold mb-4">Staff Dashboard</h2>
            
            <div class="row g-4 mb-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card bg-warning text-dark h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-muted">Pending Orders</h5>
                            <h2 class="fw-bold"><?php echo $assigned_orders; ?></h2>
                            <p class="small mb-0">Assigned to you</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card bg-success text-white h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-white-50">Completed Orders</h5>
                            <h2 class="fw-bold"><?php echo $completed_orders; ?></h2>
                            <p class="small mb-0">Lifetime delivered</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card bg-info text-white h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-white-50">Test Ride Requests</h5>
                            <h2 class="fw-bold"><?php echo $pending_test_rides; ?></h2>
                            <p class="small mb-0">Needing attention</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0" data-aos="fade-up" data-aos-delay="400">
                <div class="card-header bg-white fw-bold">Recent Assigned Tasks</div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Bike</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT o.*, u.name as cx_name, b.name as bike_name 
                                    FROM orders o 
                                    JOIN users u ON o.user_id = u.user_id 
                                    JOIN bikes b ON o.bike_id = b.bike_id 
                                    WHERE o.staff_id = $staff_id AND o.order_status NOT IN ('Delivered', 'Cancelled')
                                    ORDER BY o.order_date DESC LIMIT 5";
                            $res = $conn->query($sql);
                            if ($res->num_rows > 0):
                                while($row = $res->fetch_assoc()):
                            ?>
                            <tr>
                                <td>#<?php echo $row['order_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['cx_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['bike_name']); ?></td>
                                <td><?php echo $row['order_status']; ?></td>
                                <td><a href="my_tasks.php" class="btn btn-sm btn-outline-primary">Process</a></td>
                            </tr>
                            <?php endwhile; else: ?>
                                <tr><td colspan="5" class="text-center">No pending tasks.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
