<?php
session_start();
$_SESSION['admin_username'] = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
<style>
body{
    background:#eef1f5;
    font-family:Segoe UI;
}
.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    background:linear-gradient(180deg,#000428,#004e92);
    color:#fff;
    padding:20px;
}
.sidebar h4{
    text-align:center;
    margin-bottom:30px;
}
.sidebar a{
    display:block;
    color:#fff;
    padding:12px;
    text-decoration:none;
    border-radius:6px;
    margin-bottom:8px;
}
.sidebar a:hover{
    background:#ff9800;
    color:#000;
}
.content{
    margin-left:260px;
    padding:30px;
}
.card{
    border:none;
    box-shadow:0 4px 15px rgba(0,0,0,0.15);
}
</style>
</head>

<body>

<div class="sidebar">
    <h4><i class="fa fa-crown"></i> Admin Panel</h4>

    <a href="#"><i class="fa fa-home"></i> Dashboard</a>
    <a href="#"><i class="fa fa-user-plus"></i> Staff Management</a>
    <a href="approve_bikes.php"><i class="fa fa-check"></i> Approve Bikes</a>
    <a href="#"><i class="fa fa-motorcycle"></i> Published Bikes</a>
    <a href="#"><i class="fa fa-users"></i> Customer Orders</a>
    <a href="#"><i class="fa fa-file-invoice"></i> Billing Reports</a>
    <a href="#"><i class="fa fa-chart-line"></i> Reports</a>
    <a href="#"><i class="fa fa-user"></i> Profile</a>
    <a href="#"><i class="fa fa-sign-out-alt"></i> Logout</a>
</div>

<div class="content">
    <h3>Welcome, <?= $_SESSION['admin_username'] ?></h3>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <i class="fa fa-box fa-2x text-danger"></i>
                <h6 class="mt-2">Low Stock</h6>
                <h3>2</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 text-center">
                <i class="fa fa-rupee-sign fa-2x text-success"></i>
                <h6 class="mt-2">Monthly Sales</h6>
                <h3>₹8.4L</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 text-center">
                <i class="fa fa-users fa-2x text-primary"></i>
                <h6 class="mt-2">Staff Count</h6>
                <h3>5</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 text-center">
                <i class="fa fa-shopping-cart fa-2x text-warning"></i>
                <h6 class="mt-2">Customer Orders</h6>
                <h3>21</h3>
            </div>
        </div>
    </div>
</div>

</body>
</html>
