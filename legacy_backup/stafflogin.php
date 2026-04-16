<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Staff Login - Bike Showroom</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{
    margin:0;
    font-family: Arial, sans-serif;
    height:100vh;
    background: linear-gradient(135deg,#4caf50,#81c784); /* Different green gradient for staff */
    display:flex;
    justify-content:center;
    align-items:center;
}

/* ===== NAVBAR ===== */
.navbar-custom{
    position:absolute;
    top:0;
    width:100%;
    padding:12px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    background: rgba(255,255,255,0.9);
    box-shadow:0 4px 15px rgba(0,0,0,0.2);
    z-index:1000;
}

.navbar-custom .logo{
    font-weight:700;
    font-size:22px;
    color:#1b5e20; /* Dark green */
}
.navbar-custom .menu a{
    margin-left:20px;
    text-decoration:none;
    font-weight:500;
    color:#1b5e20;
    transition:0.3s;
}
.navbar-custom .menu a:hover{
    color:#cddc39; /* light green accent */
    text-decoration:underline;
}

/* ===== LOGIN CARD ===== */
.login-card{
    background: #fff;
    border-radius:12px;
    padding:40px 30px;
    width:350px;
    box-shadow:0 8px 30px rgba(0,0,0,0.25);
    transition:0.3s;
}
.login-card:hover{
    transform:translateY(-5px);
    box-shadow:0 12px 40px rgba(0,0,0,0.35);
}

.login-card h2{
    text-align:center;
    font-weight:700;
    color:#1b5e20;
    margin-bottom:30px;
}

.form-control{
    border-radius:8px;
    padding:10px 12px;
    margin-bottom:20px;
    border:1px solid #ccc;
    transition:0.3s;
}
.form-control:focus{
    border-color:#81c784;
    box-shadow:0 0 8px rgba(129,199,132,0.3);
    outline:none;
}

.btn-login{
    width:100%;
    padding:10px;
    background: linear-gradient(90deg,#66bb6a,#aed581); /* green gradient button */
    border:none;
    border-radius:8px;
    color:#fff;
    font-weight:600;
    font-size:16px;
    transition:0.3s;
}
.btn-login:hover{
    background: linear-gradient(90deg,#81c784,#c5e1a5);
    color:#fff;
}

.text-center a{
    text-decoration:none;
    color:#1b5e20;
    font-size:14px;
}
.text-center a:hover{
    text-decoration:underline;
    color:#558b2f;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar-custom">
    <div class="logo">Bike Showroom</div>
    
</div>

<!-- LOGIN CARD -->
<div class="login-card">
    <h2>Staff Login</h2>
    <form action="staffconnection.php" method="POST">
        <input type="text" class="form-control" placeholder="Enter  username" name= username  required>
        <input type="password" class="form-control" placeholder="Enter password" name= password required>
        <button type="submit" class="btn btn-login">Login</button>
    </form>
    <div class="text-center mt-3">
        <a href="#">Forgot Password?</a>
    </div>
</div>

</body>
</html>
