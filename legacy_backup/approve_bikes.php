<?php
session_start();
$conn = new mysqli("localhost","root","","bikes");
if($conn->connect_error){ die("Connection failed: ".$conn->connect_error); }

// Handle approve/decline
if(isset($_GET['action']) && isset($_GET['id'])){
    $id = intval($_GET['id']);
    if($_GET['action'] === 'approve'){
        $status = "Approved";
    } elseif($_GET['action'] === 'decline'){
        $status = "Declined";
    } else {
        $status = "Pending"; // fallback
    }
    $conn->query("UPDATE bikes SET status='$status' WHERE id=$id");
}

// Fetch all pending bikes
$result = $conn->query("SELECT * FROM bikes WHERE status='Pending'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Approve Bikes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card img{ height:200px; object-fit:cover; }
</style>
</head>
<body class="bg-light">
<div class="container mt-5">
<h2 class="mb-4">Pending Bikes Approval</h2>

<div class="row">
<?php while($bike = $result->fetch_assoc()){ ?>
<div class="col-md-4 mb-3">
    <div class="card">
        <img src="uploads/<?= htmlspecialchars($bike['image']) ?>" class="card-img-top">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($bike['bike_name']) ?></h5>
            <p class="card-text">
                Price: ₹<?= htmlspecialchars($bike['price']) ?><br>
                Color: <?= htmlspecialchars($bike['color']) ?><br>
                Engine: <?= htmlspecialchars($bike['engine_cc']) ?><br>
                Mileage: <?= htmlspecialchars($bike['mileage']) ?><br>
                Top Speed: <?= htmlspecialchars($bike['speed']) ?><br>
                Test Drive: <?= htmlspecialchars($bike['test_drive']) ?><br>
                Stock: <?= htmlspecialchars($bike['stock']) ?><br>
            </p>
            <a href="?action=approve&id=<?= $bike['id'] ?>" class="btn btn-success">Approve</a>
            <a href="?action=decline&id=<?= $bike['id'] ?>" class="btn btn-danger">Decline</a>
        </div>
    </div>
</div>
<?php } ?>
<?php if($result->num_rows === 0){ echo "<p class='text-center'>No pending bikes to approve.</p>"; } ?>
</div>
</div>
</body>
</html>
