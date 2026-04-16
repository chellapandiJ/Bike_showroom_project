<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['staff']);

if (!isset($_GET['booking_id'])) {
    redirect('approved_bookings.php');
}

$booking_id = intval($_GET['booking_id']);
$staff_id = $_SESSION['user_id'];

// Get booking details
$sql = "SELECT b.*, bik.name as bike_name, bik.price, bik.stock 
        FROM bookings b 
        JOIN bikes bik ON b.bike_id = bik.bike_id 
        WHERE b.booking_id = $booking_id AND b.status = 'Approved'";
$res = $conn->query($sql);

if ($res->num_rows == 0) {
    setFlashMessage('error', 'Booking not found or not approved.');
    redirect('approved_bookings.php');
}
$booking = $res->fetch_assoc();

// Process Billing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_invoice'])) {
    if ($booking['stock'] <= 0) {
        setFlashMessage('error', 'Bike out of stock!');
        redirect('approved_bookings.php');
    }
    
    $payment_mode = $_POST['payment_mode'];
    $transaction_id = sanitize($conn, $_POST['transaction_id']);
    $final_price = floatval($_POST['final_price']);
    
    $conn->begin_transaction();
    try {
        // 1. Insert into orders table
        // Note: Using existing 'orders' table structure. 
        // Need user_id if booking has associated user, else assume guest/walk-in handled via staff?
        // bookings table has user_id.
        $user_id = $booking['user_id'];
        $bike_id = $booking['bike_id'];
        $address = sanitize($conn, $booking['address']); // use booking address
        
        // Orders table expects 'booking_amount'. For full payment, booking_amount = total_amount? Or booking + remaining?
        // Let's assume full payment now.
        
        $sql_ord = "INSERT INTO orders (user_id, bike_id, staff_id, booking_amount, total_amount, payment_mode, payment_status, delivery_address, order_status, transaction_id) 
                    VALUES (?, ?, ?, ?, ?, ?, 'Paid', ?, 'Delivered', ?)";
        $stmt = $conn->prepare($sql_ord);
        $stmt->bind_param("iiiddsss", $user_id, $bike_id, $staff_id, $final_price, $final_price, $payment_mode, $address, $transaction_id);
        $stmt->execute();
        $order_id = $conn->insert_id;
        
        // 2. Update booking status to Completed/Billed
        $start_upd = $conn->prepare("UPDATE bookings SET status = 'Completed' WHERE booking_id = ?");
        $start_upd->bind_param("i", $booking_id);
        $start_upd->execute();
        
        // 3. Update Inventory
        $conn->query("UPDATE bikes SET stock = stock - 1 WHERE bike_id = $bike_id");
        
        $conn->commit();
        
        redirect("invoice.php?order_id=$order_id");
        
    } catch (Exception $e) {
        $conn->rollback();
        setFlashMessage('error', 'Billing Failed: ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Billing & Invoice - Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold mb-4">Generate Invoice</h2>
            
            <?php if(function_exists('displayFlashMessage')) displayFlashMessage(); ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Invoice Details</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Customer Name</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['customer_name']); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Phone Number</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['phone_number']); ?>" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Address</label>
                                    <textarea class="form-control" rows="2" readonly><?php echo htmlspecialchars($booking['address']); ?></textarea>
                                </div>
                                
                                <hr>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Bike Model</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['bike_name']); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Base Price (₹)</label>
                                        <input type="number" class="form-control" value="<?php echo $booking['price']; ?>" readonly>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-success">Final Amount to Pay (₹)</label>
                                    <input type="number" name="final_price" class="form-control fw-bold border-success" value="<?php echo $booking['price']; ?>" required>
                                    <small class="text-muted">Adjust if any discount is applied.</small>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Payment Mode</label>
                                        <select name="payment_mode" class="form-select" required>
                                            <option value="cash">Cash</option>
                                            <option value="card">Credit/Debit Card</option>
                                            <option value="upi">UPI / GPay / PhonePe</option>
                                            <option value="netbanking">Net Banking</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Transaction ID / Ref No.</label>
                                        <input type="text" name="transaction_id" class="form-control" placeholder="Optional for Cash">
                                    </div>
                                </div>
                                
                                <div class="d-grid mt-4">
                                    <button type="submit" name="generate_invoice" class="btn btn-primary btn-lg">
                                        <i class="fas fa-print me-2"></i> Generate Invoice & Complete Order
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow-sm bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Booking Summary</h5>
                            <p class="mb-1"><strong>Booking ID:</strong> #<?php echo $booking['booking_id']; ?></p>
                            <p class="mb-1"><strong>Date:</strong> <?php echo date('d M Y', strtotime($booking['created_at'])); ?></p>
                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success">Approved</span></p>
                            <hr>
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle"></i> Verify customer ID proof before billing.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
