<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['admin']);

// Fetch Stats
$total_orders = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
// "Pending Requests" now refers to Bookings waiting for approval
$pending_bookings = $conn->query("SELECT COUNT(*) FROM bookings WHERE status = 'Pending'")->fetch_row()[0];
// Revenue is now based on total_amount from completed orders
$total_revenue = $conn->query("SELECT SUM(total_amount) FROM orders WHERE order_status != 'Cancelled'")->fetch_row()[0];
// Bikes count
$total_bikes = $conn->query("SELECT COUNT(*) FROM bikes")->fetch_row()[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold mb-4" data-aos="fade-right">Dashboard Overview</h2>
            
            <div class="row g-4 mb-4">
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="card bg-primary text-white h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-white-50">Total Orders</h5>
                            <h2 class="fw-bold"><?php echo $total_orders; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="card bg-warning text-dark h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-dark-50">Pending Bookings</h5>
                            <h2 class="fw-bold"><?php echo $pending_bookings; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="card bg-success text-white h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-white-50">Total Revenue</h5>
                            <h2 class="fw-bold"><?php echo formatCurrency($total_revenue ?? 0); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="card bg-info text-white h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-white-50">Bikes in Stock</h5>
                            <h2 class="fw-bold"><?php echo $total_bikes; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card shadow-sm border-0" data-aos="fade-up" data-aos-delay="500">
                <div class="card-header bg-white fw-bold">Recent Orders</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#Order ID</th>
                                    <th>Customer</th>
                                    <th>Bike</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT o.*, u.name as user_name, b.name as bike_name 
                                        FROM orders o 
                                        JOIN users u ON o.user_id = u.user_id 
                                        JOIN bikes b ON o.bike_id = b.bike_id 
                                        ORDER BY o.order_date DESC LIMIT 5";
                                $res = $conn->query($sql);
                                if ($res->num_rows > 0):
                                    while($row = $res->fetch_assoc()):
                                ?>
                                <tr>
                                    <td>#<?php echo $row['order_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['bike_name']); ?></td>
                                    <td><?php echo formatCurrency($row['total_amount']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo ($row['order_status'] == 'Booked' ? 'warning' : ($row['order_status'] == 'Cancelled' ? 'danger' : 'success')); ?>">
                                            <?php echo $row['order_status']; ?>
                                        </span>
                                    </td>
                                    <td><a href="manage_orders.php" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                                <?php endwhile; else: ?>
                                    <tr><td colspan="6" class="text-center">No recent orders.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
