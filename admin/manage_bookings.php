<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['admin']);

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);
    $status = $_POST['status'];

    $sql = "UPDATE bookings SET status = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $booking_id);
    if ($stmt->execute()) {
        setFlashMessage('success', 'Booking status updated successfully.');
    } else {
        setFlashMessage('error', 'Update failed.');
    }
    redirect('manage_bookings.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold mb-4">Manage Booking Requests</h2>
            
            <?php if(function_exists('displayFlashMessage')) displayFlashMessage(); ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Details</th>
                                    <th>Bike</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT bk.*, b.name as bike_name, b.price 
                                        FROM bookings bk 
                                        JOIN bikes b ON bk.bike_id = b.bike_id 
                                        ORDER BY bk.created_at DESC";
                                $res = $conn->query($sql);
                                while($row = $res->fetch_assoc()):
                                ?>
                                <tr>
                                    <td>#<?php echo $row['booking_id']; ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($row['customer_name']); ?></div>
                                        <small class="text-muted"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['phone_number']); ?></small><br>
                                        <small class="text-muted"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['address']); ?></small>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['bike_name']); ?>
                                        <br><small class="text-muted"><?php echo formatCurrency($row['price']); ?></small>
                                    </td>
                                    <td><?php echo date('d M Y h:i A', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <?php 
                                        $cls = 'warning';
                                        if($row['status'] == 'Approved') $cls = 'success';
                                        if($row['status'] == 'Rejected') $cls = 'danger';
                                        ?>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-<?php echo $cls; ?> dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <?php echo $row['status']; ?>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form method="POST">
                                                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                                        <input type="hidden" name="status" value="Approved">
                                                        <button type="submit" class="dropdown-item">Approve</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST">
                                                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                                        <input type="hidden" name="status" value="Rejected">
                                                        <button type="submit" class="dropdown-item">Reject</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST">
                                                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                                        <input type="hidden" name="status" value="Pending">
                                                        <button type="submit" class="dropdown-item">Pending</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
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
