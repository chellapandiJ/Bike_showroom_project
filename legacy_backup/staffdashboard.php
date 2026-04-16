<?php
session_start();
$_SESSION['staff_username'] = $_SESSION['staff_username'] ?? 'Staff';
?>
<!DOCTYPE html>
<html>
<head>
<title>Staff Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
<style>
body{
    background:#f4f6f9;
    font-family:Segoe UI;
}
.sidebar{
    width:230px;
    height:100vh;
    position:fixed;
    background:linear-gradient(180deg,#1f3c88,#00008B);
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
    background:#ffdd00;
    color:#000;
}
.content{
    margin-left:250px;
    padding:30px;
}
.card{
    border:none;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}
</style>
</head>

<body>

<div class="sidebar">
    <h4><i class="fa fa-user"></i> Staff Panel</h4>

    <a href="#"><i class="fa fa-home"></i> Dashboard</a>
    <a href="add_bike.php"><i class="fa fa-plus"></i> Add New Bike</a>
    <a href="staffbooked.php"><i class="fa fa-motorcycle"></i> My Bikes</a>
    <a href="staff_orders_today.php"><i class="fa fa-calendar-day"></i> Today Orders</a>
    <a href="staffpending.php"><i class="fa fa-clock"></i> Pending Orders</a>
    <a href="stackview.php"><i class="fa fa-box"></i> Stock View</a>
    <a href="billing.php"><i class="fa fa-file-invoice"></i> Billing</a>
    <a href="#"><i class="fa fa-user"></i> Profile</a>
    <a href="#"><i class="fa fa-sign-out-alt"></i> Logout</a>
</div>

<div class="content">
    <h3>Welcome, <?= $_SESSION['staff_username'] ?></h3>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <i class="fa fa-calendar-day fa-2x text-success"></i>
                <h6 class="mt-2">Today Orders</h6>
                <h3>6</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 text-center">
                <i class="fa fa-clock fa-2x text-warning"></i>
                <h6 class="mt-2">Pending Orders</h6>
                <h3>3</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 text-center">
                <i class="fa fa-exclamation-triangle fa-2x text-danger"></i>
                <h6 class="mt-2">Low Stock</h6>
                <h3>1</h3>
            </div>
        </div>
    </div>
</div>

</body>
</html>
