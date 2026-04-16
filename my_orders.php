<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">My Bookings & Orders</h2>

    <?php if(function_exists('displayFlashMessage')) displayFlashMessage(); ?>

    <!-- Bookings Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-bookmark me-2"></i>My Bookings</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Bike Name</th>
                                    <th>Booking Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $bk = $conn->query("SELECT b.*, bik.name as bike_name FROM bookings b JOIN bikes bik ON b.bike_id = bik.bike_id WHERE b.user_id = $user_id ORDER BY b.created_at DESC");
                                if ($bk->num_rows > 0):
                                    while($row = $bk->fetch_assoc()):
                                        $dt = strtotime($row['created_at']);
                                ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-primary"><?php echo htmlspecialchars($row['bike_name']); ?></td>
                                    <td><?php echo date('d M Y', $dt); ?></td>
                                    <td><?php echo date('h:i A', $dt); ?></td>
                                    <td>
                                        <?php 
                                        $status_color = 'warning';
                                        if($row['status'] == 'Approved') $status_color = 'success';
                                        if($row['status'] == 'Rejected') $status_color = 'danger';
                                        ?>
                                        <span class="badge bg-<?php echo $status_color; ?> px-3 py-2 rounded-pill"><?php echo $row['status']; ?></span>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                    <tr><td colspan="4" class="text-center py-4 text-muted">No bookings found. <a href="bikes.php">Book a bike now</a>.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Test Rides Section -->
     <h4 class="fw-bold mt-5 mb-3">My Test Ride Requests</h4>
     <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Bike</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tr = $conn->query("SELECT tr.*, b.name FROM test_rides tr JOIN bikes b ON tr.bike_id = b.bike_id WHERE tr.user_id = $user_id");
                if($tr->num_rows > 0):
                    while($r = $tr->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($r['name']); ?></td>
                    <td><?php echo $r['requested_date']; ?></td>
                    <td><?php echo $r['requested_time']; ?></td>
                    <td><span class="badge bg-secondary"><?php echo $r['status']; ?></span></td>
                </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="4" class="text-center">No test ride requests.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
     </div>
</div>

<?php require_once 'includes/footer.php'; ?>
