<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_input = sanitize($conn, $_POST['email']); // Can be email or username
    $password = $_POST['password'];

    // Check for email OR username
    $sql = "SELECT * FROM users WHERE (email = ? OR username = ?) AND status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_input, $user_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            setFlashMessage('success', 'Welcome back, ' . $user['name'] . '!');

            if ($user['role'] == 'admin') {
                redirect('admin/dashboard.php');
            } elseif ($user['role'] == 'staff') {
                redirect('staff/dashboard.php');
            } else {
                redirect('index.php');
            }
        } else {
            $error = 'Invalid password.';
        }
    } else {
        $error = 'User not found or account inactive.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - WheelMasters</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
    <div class="auth-container">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">WheelMasters</h2>
            <p class="text-muted">Sign in to your account</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="text-end mb-3">
                <a href="forgot_password.php" class="text-muted small text-decoration-none">Forgot Password?</a>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="register.php" class="text-decoration-none">Create new account</a>
        </div>
        <div class="text-center mt-2">
            <a href="index.php" class="text-decoration-none text-muted small">Back to Home</a>
        </div>
    </div>
</div>

</body>
</html>
