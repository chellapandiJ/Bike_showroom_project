<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bikes");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all booked orders
$sql = "SELECT * FROM orders WHERE status = 'booked' ORDER BY order_date DESC";
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
<title>Booked Orders - Staff Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f1f3f6;font-family:Arial,sans-serif;}
.container{margin-top:40px;}
table th, table td{text-align:center;vertical-align:middle;}
</style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">Booked Orders Report</h2>

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
                <th>Booking ID</th>
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
                    <td><?= $order['booking_id'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8">No booked orders yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
