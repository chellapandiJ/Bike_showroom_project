<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['admin']);

// Handle Status Update / Staff Assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $staff_id = !empty($_POST['staff_id']) ? intval($_POST['staff_id']) : NULL;

    $sql = "UPDATE orders SET order_status = ?, staff_id = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $status, $staff_id, $order_id);
    if ($stmt->execute()) {
        setFlashMessage('success', 'Order updated successfully.');
    } else {
        setFlashMessage('error', 'Update failed.');
    }
    redirect('manage_orders.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold mb-4">Manage Orders</h2>
            
            <?php if(function_exists('displayFlashMessage')) displayFlashMessage(); ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Bike</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Assigned Staff</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Pre-fetch Staff List to avoid Query-in-Loop (N+1 Problem)
                                $staff_list_data = [];
                                $staff_res = $conn->query("SELECT user_id, name FROM users WHERE role = 'staff' AND status='active'");
                                while($s = $staff_res->fetch_assoc()) {
                                    $staff_list_data[] = $s;
                                }

                                $sql = "SELECT o.*, u.name as customer_name, b.name as bike_name, s.name as staff_name 
                                        FROM orders o 
                                        JOIN users u ON o.user_id = u.user_id 
                                        JOIN bikes b ON o.bike_id = b.bike_id 
                                        LEFT JOIN users s ON o.staff_id = s.user_id 
                                        ORDER BY o.order_date DESC";
                                $res = $conn->query($sql);
                                while($row = $res->fetch_assoc()):
                                ?>
                                <tr>
                                    <td>#<?php echo $row['order_id']; ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($row['customer_name']); ?></div>
                                        <small class="text-muted"><?php echo $row['payment_mode']; ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['bike_name']); ?></td>
                                    <td><?php echo formatCurrency($row['booking_amount']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['order_date'])); ?></td>
                                    <td>
                                        <form method="POST" class="row g-2 align-items-center">
                                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                            <div class="col-8">
                                                <select name="status" class="form-select form-select-sm">
                                                    <option value="Booked" <?php echo $row['order_status']=='Booked'?'selected':''; ?>>Booked</option>
                                                    <option value="Confirmed" <?php echo $row['order_status']=='Confirmed'?'selected':''; ?>>Confirmed</option>
                                                    <option value="Ready for Delivery" <?php echo $row['order_status']=='Ready for Delivery'?'selected':''; ?>>Ready</option>
                                                    <option value="Delivered" <?php echo $row['order_status']=='Delivered'?'selected':''; ?>>Delivered</option>
                                                    <option value="Cancelled" <?php echo $row['order_status']=='Cancelled'?'selected':''; ?>>Cancelled</option>
                                                </select>
                                                <select name="staff_id" class="form-select form-select-sm mt-1">
                                                    <option value="">Assign Staff</option>
                                                    <?php foreach($staff_list_data as $s): ?>
                                                        <option value="<?php echo $s['user_id']; ?>" <?php echo $row['staff_id']==$s['user_id']?'selected':''; ?>>
                                                            <?php echo htmlspecialchars($s['name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <button type="submit" class="btn btn-sm btn-primary w-100">Update</button>
                                            </div>
                                        </form>
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
