<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];
$orders = $conn->query("SELECT orders.*, bikes.name as bike_name, bikes.image 
                        FROM orders 
                        JOIN bikes ON orders.bike_id = bikes.bike_id 
                        WHERE orders.user_id = $user_id ORDER BY order_date DESC");
?>

<div class="container mt-5" style="min-height: 70vh;">
    <h2 class="mb-4 text-warning">MY PROFILE & ORDERS</h2>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card p-3 text-center">
                <i class="fas fa-user-circle fa-5x text-muted mb-3"></i>
                <h4><?php echo $_SESSION['name']; ?></h4>
                <p>Customer</p>
                <a href="logout.php" class="btn btn-danger-venum w-100">Logout</a>
            </div>
        </div>

        <div class="col-md-8">
            <h4 class="mb-3">My Bookings</h4>
            <?php if($orders->num_rows > 0): ?>
                <?php while($order = $orders->fetch_assoc()): ?>
                <div class="card mb-3 p-3">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-3">
                            <img src="<?php echo $order['image']; ?>" class="img-fluid rounded-start" alt="Bike">
                        </div>
                        <div class="col-md-9">
                            <div class="card-body">
                                <h5 class="card-title text-warning"><?php echo $order['bike_name']; ?></h5>
                                <p class="card-text">
                                    <strong>Status:</strong> <span class="badge bg-secondary"><?php echo $order['order_status']; ?></span><br>
                                    <strong>Amount:</strong> $<?php echo number_format($order['booking_amount']); ?><br>
                                    <strong>Date:</strong> <?php echo $order['order_date']; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No orders found. <a href="bikes.php">Book a bike now!</a></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
