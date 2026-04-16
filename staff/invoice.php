<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
// Staff or Admin can view invoice? Assuming Staff.
checkRole(['staff', 'admin']);

if (!isset($_GET['order_id'])) {
    redirect('dashboard.php');
}

$order_id = intval($_GET['order_id']);

// Fetch Order Details
$sql = "SELECT o.*, u.name as customer_name, u.phone, u.email, b.name as bike_name, b.price, b.engine_cc, b.fuel_type, b.mileage, s.name as staff_name 
        FROM orders o 
        JOIN users u ON o.user_id = u.user_id 
        JOIN bikes b ON o.bike_id = b.bike_id 
        LEFT JOIN users s ON o.staff_id = s.user_id 
        WHERE o.order_id = $order_id";
$res = $conn->query($sql);

if ($res->num_rows == 0) {
    die("Invoice not found.");
}
$order = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; }
        .invoice-box {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            background: #fff;
        }
        @media print {
            body { background: #fff; }
            .invoice-box { box-shadow: none; border: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="text-center mb-4 no-print">
        <button onclick="window.print()" class="btn btn-primary btn-lg me-2">Print Invoice</button>
        <a href="dashboard.php" class="btn btn-secondary btn-lg">Back to Dashboard</a>
    </div>

    <div class="invoice-box">
        <div class="row mb-4">
            <div class="col-8">
                <h2 class="fw-bold text-primary">PREMIUM BIKES SHOWROOM</h2>
                <p>123, Race Course Road,<br>Coimbatore, Tamil Nadu 641018<br>Phone: +91 98765 43210<br>Email: contact@premiumbikes.com</p>
            </div>
            <div class="col-4 text-end">
                <h3 class="fw-bold">INVOICE</h3>
                <p><strong>Invoice #:</strong> <?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?><br>
                <strong>Date:</strong> <?php echo date('d M Y', strtotime($order['order_date'])); ?></p>
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-6">
                <h5 class="fw-bold">Bill To:</h5>
                <p>
                    <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                    <?php echo htmlspecialchars($order['delivery_address']); ?><br>
                    Phone: <?php echo htmlspecialchars($order['phone']); ?><br>
                    Email: <?php echo htmlspecialchars($order['email']); ?>
                </p>
            </div>
            <div class="col-6 text-end">
                <h5 class="fw-bold">Order Details:</h5>
                <p>
                    <strong>Served By:</strong> <?php echo htmlspecialchars($order['staff_name']); ?><br>
                    <strong>Payment Mode:</strong> <?php echo ucfirst($order['payment_mode']); ?><br>
                    <?php if($order['transaction_id']): ?>
                    <strong>Transaction ID:</strong> <?php echo htmlspecialchars($order['transaction_id']); ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Description</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($order['bike_name']); ?></strong><br>
                        <small class="text-muted">
                            Engine: <?php echo $order['engine_cc']; ?> CC | 
                            Fuel: <?php echo $order['fuel_type']; ?> | 
                            Mileage: <?php echo $order['mileage']; ?>
                        </small>
                    </td>
                    <td class="text-end"><?php echo formatCurrency($order['total_amount']); ?></td>
                </tr>
                <!-- Can add tax rows here later -->
                <tr class="table-light fw-bold">
                    <td class="text-end">Grand Total</td>
                    <td class="text-end fs-5"><?php echo formatCurrency($order['total_amount']); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <p class="mb-0">Thank you for your business!</p>
                <small class="text-muted">This is a computer-generated invoice.</small>
            </div>
        </div>
    </div>
</div>

</body>
</html>
