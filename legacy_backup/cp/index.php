<?php
/* DEMO DATA – DB replace pannalaam later */
$bikes = [
 ["id"=>1,"name"=>"Royal Enfield Classic 350","price"=>195000,"img"=>"https://i.imgur.com/XC1QK6Y.png"],
 ["id"=>2,"name"=>"Yamaha R15","price"=>180000,"img"=>"https://i.imgur.com/yX6Vn3Q.png"],
 ["id"=>3,"name"=>"KTM Duke 390","price"=>320000,"img"=>"https://i.imgur.com/Mh1pYQX.png"],
 ["id"=>4,"name"=>"Honda Shine","price"=>80000,"img"=>"https://i.imgur.com/1Pq9Z6Z.png"]
];

$orders = [
 ["bike"=>"Royal Enfield","name"=>"Arun","status"=>"New"],
 ["bike"=>"Yamaha R15","name"=>"Kumar","status"=>"Approved"]
];
?>
<!DOCTYPE html>
<html>
<head>
<title>Bike Showroom</title>
<meta name="viewport" content="width=device-width,initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

<style>
body{font-family:Poppins,Arial;background:#f1f3f6;margin:0}

/* NAVBAR */
.navbar{background:#1877f2}
.navbar a{color:#fff!important;font-weight:600}

/* HERO */
.hero{
 background:linear-gradient(120deg,#1877f2,#42a5f5);
 color:#fff;padding:70px 20px;text-align:center
}
.hero h1{font-weight:800}

/* BIKE CARD */
.bike-card{
 background:#fff;border-radius:18px;
 box-shadow:0 8px 25px rgba(0,0,0,.12);
 transition:.4s
}
.bike-card:hover{transform:translateY(-6px)}
.bike-card img{height:200px;object-fit:contain;padding:15px}

/* BUTTON */
.btn-main{
 background:#ff9f00;color:#000;font-weight:600;border:none
}

/* SECTION */
.section{padding:80px 20px}

/* DASHBOARD */
.dashboard-card{
 background:#fff;padding:25px;border-radius:18px;
 box-shadow:0 8px 25px rgba(0,0,0,.12)
}

/* FOOTER */
footer{background:#172337;color:#fff;padding:15px;text-align:center}
</style>
</head>

<body>

<!-- NAV -->
<nav class="navbar navbar-expand-lg px-4">
<a class="navbar-brand" href="#">BikeShowroom</a>
<div class="navbar-nav ms-auto">
<a class="nav-link" href="#home">Home</a>
<a class="nav-link" href="#bikes">Bikes</a>
<a class="nav-link" href="#staff">Staff</a>
<a class="nav-link" href="#admin">Admin</a>
</div>
</nav>

<!-- HOME -->
<div class="hero" id="home">
<h1>Find Your Dream Bike</h1>
<p>Instagram feel • Flipkart grid • Facebook layout</p>
</div>

<!-- BIKES -->
<div class="section container" id="bikes">
<h3 class="fw-bold mb-4">Available Bikes</h3>
<div class="row g-4">
<?php foreach($bikes as $b): ?>
<div class="col-md-3">
<div class="bike-card p-3">
<img src="<?= $b['img'] ?>" class="w-100">
<h6 class="fw-bold"><?= $b['name'] ?></h6>
<p class="text-success fw-bold">₹<?= number_format($b['price']) ?></p>
<button class="btn btn-main w-100" data-bs-toggle="modal" data-bs-target="#bookModal">Book Now</button>
</div>
</div>
<?php endforeach; ?>
</div>
</div>

<!-- STAFF DASHBOARD -->
<div class="section bg-light" id="staff">
<div class="container">
<h3 class="fw-bold mb-4">Staff Dashboard</h3>
<div class="dashboard-card">
<table class="table">
<tr><th>Bike</th><th>Customer</th><th>Status</th></tr>
<?php foreach($orders as $o): ?>
<tr>
<td><?= $o['bike'] ?></td>
<td><?= $o['name'] ?></td>
<td><span class="badge bg-primary"><?= $o['status'] ?></span></td>
</tr>
<?php endforeach; ?>
</table>
</div>
</div>
</div>

<!-- ADMIN DASHBOARD -->
<div class="section" id="admin">
<div class="container">
<h3 class="fw-bold mb-4">Admin Dashboard</h3>
<div class="row g-4">
<div class="col-md-4">
<div class="dashboard-card text-center">
<h5>Total Bikes</h5>
<h2><?= count($bikes) ?></h2>
</div>
</div>
<div class="col-md-4">
<div class="dashboard-card text-center">
<h5>Total Orders</h5>
<h2><?= count($orders) ?></h2>
</div>
</div>
<div class="col-md-4">
<div class="dashboard-card text-center">
<h5>Revenue (Demo)</h5>
<h2>₹5.6L</h2>
</div>
</div>
</div>
</div>
</div>

<!-- BOOK MODAL -->
<div class="modal fade" id="bookModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5>Book Bike</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<input class="form-control mb-2" placeholder="Name">
<input class="form-control mb-2" placeholder="Phone">
<textarea class="form-control" placeholder="Address"></textarea>
<button class="btn btn-main w-100 mt-2">Confirm Booking</button>
</div>
</div>
</div>
</div>

<footer>
© 2026 Bike Showroom | Instagram • Facebook • Flipkart Feel
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
