<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';

// Staff also Updates Status
if(isset($_POST['update_status'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    // Logic to ensure Staff can only update if assigned? Or open for all staff?
    // Requirement says "Assign Staff" is admin feature. Detailed flow: Admin assigns -> Staff views.
    // For now, let's allow Staff to view all orders or ideally filter those assigned.
    // Assuming simple flow: Staff sees all pending/approved orders.
    $conn->query("UPDATE orders SET order_status='$status' WHERE order_id=$order_id");
    header("Location: dashboard.php");
}

$orders = $conn->query("SELECT orders.*, users.name as customer_name, bikes.name as bike_name 
                        FROM orders 
                        JOIN users ON orders.user_id = users.user_id 
                        JOIN bikes ON orders.bike_id = bikes.bike_id 
                        ORDER BY order_date DESC");
?>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>STAFF DASHBOARD</h2>
        <span class="badge bg-warning text-dark fs-5">Hello, <?php echo $_SESSION['name']; ?></span>
    </div>

    <div class="card bg-dark text-white border-primary mb-4">
        <div class="card-header border-primary">Task List</div>
        <div class="card-body">
            <h5 class="card-title">Pending Orders</h5>
            <p>Review and process the following bookings.</p>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Bike</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $orders->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['order_id']; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['bike_name']; ?></td>
                    <td><?php echo $row['booking_amount']; ?></td>
                    <td>
                        <span class="badge bg-<?php echo $row['order_status']=='Delivered'?'success':'warning'; ?>">
                            <?php echo $row['order_status']; ?>
                        </span>
                    </td>
                    <td>
                        <form method="post" class="d-flex">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <select name="status" class="form-select form-select-sm me-2">
                                <option value="Booked" <?php if($row['order_status']=='Booked') echo 'selected'; ?>>Booked</option>
                                <option value="Approved" <?php if($row['order_status']=='Approved') echo 'selected'; ?>>Approved</option>
                                <option value="Ready" <?php if($row['order_status']=='Ready') echo 'selected'; ?>>Ready</option>
                                <option value="Delivered" <?php if($row['order_status']=='Delivered') echo 'selected'; ?>>Delivered</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-sm btn-venum">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
