<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "bikes");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle confirm button
if(isset($_POST['confirm_order'])){
    $order_id = intval($_POST['order_id']);
    $booking_id = time() + $order_id; // unique booking ID

    // Update status to 'booked' and add booking ID
    $conn->query("UPDATE orders SET status='booked', booking_id='$booking_id' WHERE id='$order_id'");
    $message = "Order #$order_id booked successfully with Booking ID: $booking_id";
}

// Fetch today's orders
$today = date('Y-m-d');
$sql = "SELECT * FROM orders WHERE DATE(order_date)='$today' ORDER BY order_date DESC";
$result = $conn->query($sql);
$orders = [];
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Today's Orders - Staff Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f1f3f6;font-family:Arial,sans-serif;}
.container{margin-top:40px;}
table th, table td{text-align:center;vertical-align:middle;}
.btn-confirm{background:#28a745;color:#fff;}
.btn-confirm:hover{background:#218838;}
</style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">Today's Orders</h2>

    <?php if(isset($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Bike ID</th>
                <th>Bike Name</th>
                <th>Customer Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Booking ID</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if(count($orders) > 0): ?>
            <?php foreach($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['bike_id'] ?></td>
                    <td><?= htmlspecialchars($order['bike_name']) ?></td>
                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td><?= htmlspecialchars($order['phone']) ?></td>
                    <td><?= htmlspecialchars($order['address']) ?></td>
                    <td><?= $order['order_date'] ?></td>
                    <td><?= ucfirst($order['status']) ?></td>
                    <td><?= $order['booking_id'] ?? '-' ?></td>
                    <td>
                        <?php if($order['status'] != 'booked'): ?>
                            <form method="post" style="margin:0;">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <button type="submit" name="confirm_order" class="btn btn-confirm btn-sm">Confirm</button>
                            </form>
                        <?php else: ?>
                            <span class="text-success">Booked</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="10">No orders placed today.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
