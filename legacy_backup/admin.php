<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login - Bike Showroom</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{
    margin:0;
    font-family: Arial, sans-serif;
    height:100vh;
    background: linear-gradient(135deg,#1f3c88,#4a90e2);
    display:flex;
    justify-content:center;
    align-items:center;
}
.login-card{
    background:#fff;
    border-radius:12px;
    padding:40px 30px;
    width:350px;
    box-shadow:0 8px 30px rgba(0,0,0,0.25);
}
.login-card h2{
    text-align:center;
    font-weight:700;
    color:#1f3c88;
    margin-bottom:30px;
}
.form-control{
    border-radius:8px;
    margin-bottom:20px;
}
.btn-login{
    width:100%;
    background: linear-gradient(90deg,#ff9800,#ffc107);
    border:none;
    color:#fff;
    font-weight:600;
}
</style>
</head>
<body>

<div class="login-card">
    <h2>Admin Login</h2>

    <!-- ✅ method fixed -->
    <form action="adminconnection.php" method="POST">
        <!-- ✅ name added -->
        <input type="text" name="username" class="form-control" placeholder="Enter admin username" required>
        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
        <button type="submit" class="btn btn-login">Login</button>
    </form>

</div>
</body>
</html>
