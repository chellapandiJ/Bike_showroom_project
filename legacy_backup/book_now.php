<?php
$conn = new mysqli("localhost","root","","bikes");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch bike details
$bikeResult = $conn->query("SELECT * FROM bikes WHERE id='$id' AND status='Approved'");
if($bikeResult->num_rows==0){
    die("Bike not found or not available.");
}
$bike = $bikeResult->fetch_assoc();

$message = '';
if(isset($_POST['book'])){
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);

    $conn->query("INSERT INTO orders (bike_id, customer_name, phone, email, address)
                  VALUES ('$id','$name','$phone','$email','$address')");
    $message = "Booking placed successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Bike - <?= htmlspecialchars($bike['bike_name']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f1f3f6;font-family:Arial,sans-serif;padding:30px;}
.card{box-shadow:0 4px 15px rgba(0,0,0,0.15);}
input, textarea{margin-bottom:10px;}
.alert{margin-top:15px;}
</style>
</head>
<body>

<div class="container">
    <h2>Book Your Bike</h2>
    <div class="card p-3 mb-4">
        <img src="uploads/<?= $bike['image'] ?>" class="img-fluid mb-3" style="height:250px;object-fit:cover;">
        <h4><?= htmlspecialchars($bike['bike_name']) ?></h4>
        <p><strong>Price:</strong> ₹<?= number_format($bike['price']) ?></p>
        <p><strong>Color:</strong> <?= htmlspecialchars($bike['color']) ?></p>
        <p><strong>Engine:</strong> <?= htmlspecialchars($bike['engine_cc']) ?> cc</p>
        <p><strong>Mileage:</strong> <?= htmlspecialchars($bike['mileage']) ?> km/l</p>
        <p><strong>Top Speed:</strong> <?= htmlspecialchars($bike['speed']) ?> km/h</p>
        <p><strong>Test Drive:</strong> <?= htmlspecialchars($bike['test_drive']) ?></p>
        <p><strong>Stock:</strong> <?= htmlspecialchars($bike['stock']) ?></p>
    </div>

    <?php if($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
        <input type="text" name="phone" class="form-control" placeholder="Mobile Number" required>
        <input type="email" name="email" class="form-control" placeholder="Email (optional)">
        <textarea name="address" class="form-control" placeholder="Address" rows="3" required></textarea>
        <button type="submit" name="book" class="btn btn-success mt-2">Book Now</button>
    </form>
</div>

</body>
</html>
