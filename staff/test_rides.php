<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['staff']);

if (isset($_POST['update_tr'])) {
    $id = intval($_POST['ride_id']);
    $status = $_POST['status'];
    $conn->query("UPDATE test_rides SET status='$status' WHERE ride_id=$id");
    setFlashMessage('success', 'Test ride updated.');
    redirect('test_rides.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Rides - Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold mb-4">Test Ride Requests</h2>
            
            <?php if(function_exists('displayFlashMessage')) displayFlashMessage(); ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Bike</th>
                                <th>Requested Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT tr.*, u.name, u.phone, b.name as bike_name 
                                    FROM test_rides tr 
                                    JOIN users u ON tr.user_id = u.user_id 
                                    JOIN bikes b ON tr.bike_id = b.bike_id 
                                    ORDER BY tr.created_at DESC";
                            $res = $conn->query($sql);
                            while($row = $res->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $row['ride_id']; ?></td>
                                <td>
                                    <?php echo htmlspecialchars($row['name']); ?><br>
                                    <small><?php echo $row['phone']; ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['bike_name']); ?></td>
                                <td><?php echo date('d M, h:i A', strtotime($row['requested_date'].' '.$row['requested_time'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo ($row['status'] == 'Approved' ? 'success' : ($row['status'] == 'Pending' ? 'warning' : 'secondary')); ?>">
                                        <?php echo $row['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($row['status'] == 'Pending'): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="ride_id" value="<?php echo $row['ride_id']; ?>">
                                        <input type="hidden" name="status" value="Approved">
                                        <input type="hidden" name="update_tr" value="1">
                                        <button class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="ride_id" value="<?php echo $row['ride_id']; ?>">
                                        <input type="hidden" name="status" value="Cancelled">
                                        <input type="hidden" name="update_tr" value="1">
                                        <button class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                    <?php elseif($row['status'] == 'Approved'): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="ride_id" value="<?php echo $row['ride_id']; ?>">
                                        <input type="hidden" name="status" value="Completed">
                                        <input type="hidden" name="update_tr" value="1">
                                        <button class="btn btn-sm btn-primary">Mark Completed</button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
