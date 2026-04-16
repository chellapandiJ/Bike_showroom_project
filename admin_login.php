<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    if ($_SESSION['role'] == 'admin') redirect('admin/dashboard.php');
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Specific check for default admin if not in DB yet (fallback) or standard check
    $sql = "SELECT * FROM users WHERE username = ? AND role = 'admin' AND status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            setFlashMessage('success', 'Welcome Administrator!');
            redirect('admin/dashboard.php');
        } else {
            $error = 'Invalid Credentials.';
        }
    } else {
        $error = 'Access Denied: Admin privileges required.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - WheelMasters</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="login-body" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card border-0 shadow-lg animate-fade-up">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-shield fa-3x text-primary mb-3"></i>
                        <h2 class="fw-bold text-dark">Admin Console</h2>
                        <p class="text-muted small">Management Access Only</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger py-2"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" name="username" class="form-control" id="floatingInput" placeholder="admin" required>
                            <label for="floatingInput">Admin Username</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                            <label for="floatingPassword">Password</label>
                        </div>
                        <div class="text-end mb-4">
                            <a href="forgot_password.php" class="text-secondary small text-decoration-none">Forgot Password?</a>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold py-2">Authenticate</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="index.php" class="text-muted small text-decoration-none"><i class="fas fa-arrow-left"></i> Back to Website</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
