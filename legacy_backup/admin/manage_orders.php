<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';

// Update Status Logic
if(isset($_POST['update_status'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET order_status='$status' WHERE order_id=$order_id");
    header("Location: manage_orders.php");
}

$orders = $conn->query("SELECT orders.*, users.name as customer_name, bikes.name as bike_name 
                        FROM orders 
                        JOIN users ON orders.user_id = users.user_id 
                        JOIN bikes ON orders.bike_id = bikes.bike_id 
                        ORDER BY order_date DESC");
?>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">ORDER MANAGEMENT</h2>
    
    <div class="table-responsive">
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Bike</th>
                    <th>Amount</th>
                    <th>Date</th>
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
                    <td><?php echo date('d M Y', strtotime($row['order_date'])); ?></td>
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
                                <option value="Cancelled" <?php if($row['order_status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
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
