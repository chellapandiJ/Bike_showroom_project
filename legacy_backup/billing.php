<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bikes");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle invoice generation
$invoice = null;
if(isset($_POST['generate_invoice'])){
    $order_id = intval($_POST['order_id']);

    // Get order and bike details
    $sql = "SELECT o.*, b.price FROM orders o 
            JOIN bikes b ON o.bike_id = b.id
            WHERE o.id = $order_id AND o.status='booked' LIMIT 1";
    $res = $conn->query($sql);
    if($res->num_rows > 0){
        $invoice = $res->fetch_assoc();
        $invoice['total'] = $invoice['price']; // Add more charges if needed
    }
}

// Fetch all booked orders
$orders_sql = "SELECT o.*, b.name as bike_name FROM orders o
               JOIN bikes b ON o.bike_id = b.id
               WHERE o.status='booked' ORDER BY o.order_date DESC";
$orders_res = $conn->query($orders_sql);
$orders = [];
if($orders_res->num_rows > 0){
    while($row = $orders_res->fetch_assoc()){
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Billing - Staff Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f1f3f6;font-family:Arial,sans-serif;}
.container{margin-top:40px;}
.table th, .table td{text-align:center;}
.invoice-box{background:#fff;padding:20px;border:1px solid #ccc;margin-top:20px;}
</style>
</head>
<body>
<div class="container">
    <h2>Billing / Invoice Generation</h2>

    <h4 class="mt-4">Booked Orders</h4>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Bike Name</th>
                <th>Customer Name</th>
                <th>Phone</th>
                <th>Order Date</th>
                <th>Booking ID</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if(count($orders) > 0): ?>
            <?php foreach($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= htmlspecialchars($order['bike_name']) ?></td>
                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                <td><?= htmlspecialchars($order['phone']) ?></td>
                <td><?= $order['order_date'] ?></td>
                <td><?= $order['booking_id'] ?></td>
                <td>
                    <form method="post" style="margin:0;">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" name="generate_invoice" class="btn btn-primary btn-sm">Generate Invoice</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7">No booked orders available.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <?php if($invoice): ?>
    <div class="invoice-box">
        <h4>Invoice for Order #<?= $invoice['id'] ?></h4>
        <p><strong>Customer:</strong> <?= htmlspecialchars($invoice['customer_name']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($invoice['phone']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($invoice['address']) ?></p>
        <p><strong>Bike:</strong> <?= htmlspecialchars($invoice['bike_name']) ?></p>
        <p><strong>Booking ID:</strong> <?= $invoice['booking_id'] ?></p>
        <p><strong>Order Date:</strong> <?= $invoice['order_date'] ?></p>
        <p><strong>Amount:</strong> ₹<?= number_format($invoice['total'],2) ?></p>
        <hr>
        <p><strong>Total Payable:</strong> ₹<?= number_format($invoice['total'],2) ?></p>
        <button onclick="window.print()" class="btn btn-success btn-sm">Print Invoice</button>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
