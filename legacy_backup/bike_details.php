<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if(!isset($_GET['id'])){
    echo "<script>window.location='bikes.php';</script>";
}

$id = $_GET['id'];
$bike = $conn->query("SELECT * FROM bikes WHERE bike_id=$id")->fetch_assoc();

// Handle Booking (Simplified)
if(isset($_POST['book_now'])){
    if(!isset($_SESSION['user_id'])){
        echo "<script>alert('Please Login to Book!'); window.location='login.php';</script>";
    } else {
        $user_id = $_SESSION['user_id'];
        $amount = $bike['price']; // Or booking amount
        $address = $_POST['address'];
        $payment = $_POST['payment_mode'];
        
        $sql = "INSERT INTO orders (user_id, bike_id, booking_amount, payment_mode, address) VALUES ('$user_id', '$id', '$amount', '$payment', '$address')";
        if($conn->query($sql)){
            echo "<script>alert('Booking Successful!'); window.location='profile.php';</script>";
        } else {
            echo "<script>alert('Error booking!');</script>";
        }
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo $bike['image']; ?>" class="img-fluid border border-warning" alt="Bike">
        </div>
        <div class="col-md-6">
            <h1 class="text-warning"><?php echo $bike['name']; ?></h1>
            <h3 class="mb-3">$<?php echo number_format($bike['price']); ?></h3>
            
            <table class="table table-dark mt-4">
                <tr><th>Engine</th><td><?php echo $bike['engine_cc']; ?></td></tr>
                <tr><th>Mileage</th><td><?php echo $bike['mileage']; ?></td></tr>
                <tr><th>Fuel Type</th><td><?php echo $bike['fuel_type']; ?></td></tr>
                <tr><th>Stock</th><td><?php echo $bike['stock'] > 0 ? 'Available' : 'Out of Stock'; ?></td></tr>
            </table>

            <button class="btn btn-venum btn-lg mt-3" data-bs-toggle="modal" data-bs-target="#bookingModal">Book Now</button>
            <button class="btn btn-outline-light btn-lg mt-3 ms-2">Book Test Ride</button>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Booking</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label>Delivery Address</label>
                        <textarea name="address" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Payment Mode</label>
                        <select name="payment_mode" class="form-select">
                            <option value="card">Card</option>
                            <option value="upi">UPI</option>
                            <option value="cash">Cash on Delivery</option>
                        </select>
                    </div>
                    <button type="submit" name="book_now" class="btn btn-venum w-100">Confirm Booking ($<?php echo $bike['price']; ?>)</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
