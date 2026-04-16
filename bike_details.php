<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

if (!isset($_GET['id'])) {
    redirect('bikes.php');
}

$bike_id = intval($_GET['id']);

// Fetch bike details
$stmt = $conn->prepare("SELECT b.*, br.brand_name, c.category_name 
                        FROM bikes b 
                        LEFT JOIN brands br ON b.brand_id = br.brand_id 
                        LEFT JOIN categories c ON b.category_id = c.category_id 
                        WHERE b.bike_id = ?");
$stmt->bind_param("i", $bike_id);
$stmt->execute();
$bike = $stmt->get_result()->fetch_assoc();

if (!$bike) {
    echo "<div class='container py-5'><h3>Bike not found.</h3></div>";
    require_once 'includes/footer.php';
    exit();
}

// Fetch images
$img_stmt = $conn->prepare("SELECT * FROM bike_images WHERE bike_id = ?");
$img_stmt->bind_param("i", $bike_id);
$img_stmt->execute();
$images = $img_stmt->get_result();
$img_array = [];
while($img = $images->fetch_assoc()) {
    $img_array[] = $img['image_path'];
}
// Fallback if no images
if (empty($img_array)) {
    $img_array[] = "default_bike.jpg"; // Handle in UI
}

// Handle Booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
    
    $user_id = $_SESSION['user_id'];
    
    // Fetch user details for pre-filling
    $u_stmt = $conn->prepare("SELECT name, phone, address FROM users WHERE user_id = ?");
    $u_stmt->bind_param("i", $user_id);
    $u_stmt->execute();
    $u_res = $u_stmt->get_result();
    $u_data = $u_res->fetch_assoc();

    if ($_POST['action'] == 'book') {
        $name = sanitize($conn, $_POST['name']);
        $phone = sanitize($conn, $_POST['phone']);
        $address = sanitize($conn, $_POST['address']);
        
        $sql = "INSERT INTO bookings (user_id, bike_id, customer_name, phone_number, address) VALUES (?, ?, ?, ?, ?)";
        $bk_stmt = $conn->prepare($sql);
        $bk_stmt->bind_param("iisss", $user_id, $bike_id, $name, $phone, $address);
        
        if ($bk_stmt->execute()) {
            setFlashMessage('success', 'Booking request submitted successfully! Check My Bookings for status.');
            redirect('my_orders.php');
        } else {
            setFlashMessage('error', 'Booking failed: ' . $conn->error);
        }
    } elseif ($_POST['action'] == 'test_ride') {
        $date = $_POST['date'];
        $time = $_POST['time'];
        
        $sql = "INSERT INTO test_rides (user_id, bike_id, requested_date, requested_time) VALUES (?, ?, ?, ?)";
        $tr_stmt = $conn->prepare($sql);
        $tr_stmt->bind_param("iiss", $user_id, $bike_id, $date, $time);
        
        if ($tr_stmt->execute()) {
            setFlashMessage('success', 'Test ride requested successfully! We will contact you soon.');
        } else {
            setFlashMessage('error', 'Request failed: ' . $conn->error);
        }
    }
}
?>

<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="bikes.php">Bikes</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($bike['name']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Image Gallery -->
        <div class="col-lg-6 mb-4">
            <div id="bikeCarousel" class="carousel slide shadow rounded overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($img_array as $index => $img): 
                         $src = 'https://placehold.co/800x600?text=No+Image';
                         if (strpos($img, 'http') === 0) {
                             $src = $img;
                         } elseif ($img != 'default_bike.jpg' && file_exists(__DIR__ . "/uploads/bikes/" . $img)) {
                             $src = "uploads/bikes/" . htmlspecialchars($img);
                         }
                    ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo $src; ?>" class="d-block w-100" alt="Bike Image" style="height: 400px; object-fit: cover;">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($img_array) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#bikeCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#bikeCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Details -->
        <div class="col-lg-6">
            <h1 class="fw-bold"><?php echo htmlspecialchars($bike['name']); ?></h1>
            <div class="mb-3">
                <span class="badge bg-secondary"><?php echo htmlspecialchars($bike['brand_name']); ?></span>
                <span class="badge bg-info text-dark"><?php echo htmlspecialchars($bike['category_name']); ?></span>
                <?php if ($bike['is_featured']) echo '<span class="badge bg-warning text-dark">Featured</span>'; ?>
            </div>
            
            <h2 class="text-primary fw-bold mb-4"><?php echo formatCurrency($bike['price']); ?> <small class="text-muted fs-6">Ex-showroom</small></h2>
            
            <p class="text-muted"><?php echo nl2br(htmlspecialchars($bike['description'])); ?></p>
            
            <!-- Specs Grid -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4">
                    <div class="border rounded p-2 text-center bg-light">
                        <small class="text-muted d-block">Engine</small>
                        <strong><?php echo htmlspecialchars($bike['engine_cc']); ?> CC</strong>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="border rounded p-2 text-center bg-light">
                        <small class="text-muted d-block">Mileage</small>
                        <strong><?php echo htmlspecialchars($bike['mileage']); ?></strong>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="border rounded p-2 text-center bg-light">
                        <small class="text-muted d-block">Fuel</small>
                        <strong><?php echo htmlspecialchars($bike['fuel_type']); ?></strong>
                    </div>
                </div>
                 <div class="col-6 col-md-4">
                    <div class="border rounded p-2 text-center bg-light">
                        <small class="text-muted d-block">Brakes</small>
                        <strong><?php echo htmlspecialchars($bike['brakes']); ?></strong>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2 d-md-block">
                <?php if ($bike['stock'] > 0): ?>
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#bookModal">
                        <i class="fas fa-shopping-cart"></i> Book Now
                    </button>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg" disabled>Out of Stock</button>
                <?php endif; ?>
                
                <button class="btn btn-outline-dark btn-lg" data-bs-toggle="modal" data-bs-target="#testRideModal">
                    <i class="fas fa-motorcycle"></i> Request Test Ride
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Book <?php echo htmlspecialchars($bike['name']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php 
                    // Display Bike Image
                    $mod_img = isset($img_array[0]) ? $img_array[0] : 'default_bike.jpg';
                    $mod_src = 'https://placehold.co/800x600?text=No+Image';
                    
                    if (strpos($mod_img, 'http') === 0) {
                        $mod_src = $mod_img;
                    } elseif ($mod_img != 'default_bike.jpg') {
                         if (file_exists(__DIR__ . "/uploads/bikes/" . $mod_img)) {
                             $mod_src = "uploads/bikes/" . htmlspecialchars($mod_img);
                         }
                    }
                    ?>
                    <img src="<?php echo $mod_src; ?>" class="img-fluid rounded mb-3" style="width: 100%; height: 200px; object-fit: cover;">

                    <?php if(!isLoggedIn()): ?>
                        <div class="alert alert-warning">Please <a href="login.php">Login</a> to continue.</div>
                    <?php else: ?>
                        <?php
                        // Fetch user details for form pre-fill
                        $u_id = $_SESSION['user_id'];
                        $us = $conn->query("SELECT name, phone, address FROM users WHERE user_id = $u_id")->fetch_assoc();
                        ?>
                        <input type="hidden" name="action" value="book">
                        <div class="alert alert-info py-2 small">
                            <i class="fas fa-info-circle"></i> Please review your details to confirm the booking request.
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($us['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($us['phone']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" required rows="2"><?php echo htmlspecialchars($us['address']); ?></textarea>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <?php if(isLoggedIn()): ?>
                        <button type="submit" class="btn btn-primary fw-bold">Confirm Booking</button>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Login Now</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePaymentDetails() {
    let mode = document.getElementById('paymentMode').value;
    document.getElementById('upiDetails').classList.add('d-none');
    document.getElementById('cardDetails').classList.add('d-none');
    
    if (mode === 'upi') {
        document.getElementById('upiDetails').classList.remove('d-none');
    } else if (mode === 'card' || mode === 'netbanking') {
        document.getElementById('cardDetails').classList.remove('d-none');
    }
}
</script>

<!-- Test Ride Modal -->
<div class="modal fade" id="testRideModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
             <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Request Test Ride</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if(!isLoggedIn()): ?>
                        <div class="alert alert-warning">Please <a href="login.php">Login</a> to continue.</div>
                    <?php else: ?>
                        <input type="hidden" name="action" value="test_ride">
                        <div class="mb-3">
                            <label class="form-label">Preferred Date</label>
                            <input type="date" name="date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preferred Time</label>
                            <input type="time" name="time" class="form-control" required>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                     <?php if(isLoggedIn()): ?>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                     <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Login Now</a>
                     <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
