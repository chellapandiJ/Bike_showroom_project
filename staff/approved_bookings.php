<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['staff']);

$staff_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Process Bookings - Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold mb-4">Approved Bookings (Ready for Billing)</h2>
            
            <?php if(function_exists('displayFlashMessage')) displayFlashMessage(); ?>

            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Approved Bookings</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Details</th>
                                    <th>Bike Details</th>
                                    <th>Approved Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT b.*, bik.name as bike_name, bik.price, bik.stock 
                                        FROM bookings b 
                                        JOIN bikes bik ON b.bike_id = bik.bike_id 
                                        WHERE b.status = 'Approved' 
                                        ORDER BY b.created_at ASC";
                                $res = $conn->query($sql);
                                
                                if($res->num_rows > 0):
                                    while($row = $res->fetch_assoc()):
                                ?>
                                <tr>
                                    <td>#<?php echo $row['booking_id']; ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($row['customer_name']); ?></div>
                                        <small><i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['phone_number']); ?></small><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['address']); ?></small>
                                    </td>
                                    <td>
                                        <span class="fw-bold"><?php echo htmlspecialchars($row['bike_name']); ?></span><br>
                                        <small>Price: <?php echo formatCurrency($row['price']); ?></small>
                                        <?php if($row['stock'] < 5): ?>
                                            <div class="text-danger small fw-bold"><i class="fas fa-exclamation-triangle"></i> Low Stock: <?php echo $row['stock']; ?></div>
                                        <?php else: ?>
                                            <div class="text-success small"><i class="fas fa-check"></i> Stock: <?php echo $row['stock']; ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <?php if($row['stock'] > 0): ?>
                                            <a href="billing.php?booking_id=<?php echo $row['booking_id']; ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-file-invoice-dollar"></i> Confirm & Bill
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled>Out of Stock</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                    <tr><td colspan="5" class="text-center text-muted py-3">No approved bookings.</td></tr>
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
