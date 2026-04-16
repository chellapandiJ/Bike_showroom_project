<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($conn, $_POST['email']);
    
    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // In a real application, you would generate a token and email it.
        // For this demo, we'll just show a success message.
        $message = "If an account exists for this email, we have sent password reset instructions.";
    } else {
        // Security best practice: Don't reveal if email exists or not, 
        // but for usability in this specific showroom context, we might be vague.
        // Or we can just show the same message.
        $message = "If an account exists for this email, we have sent password reset instructions.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - WheelMasters</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light login-body">

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card shadow-lg animate-fade-up">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark">Forgot Password?</h3>
                        <p class="text-muted small">Enter your email to receive reset instructions.</p>
                    </div>

                    <?php if ($message): ?>
                        <div class="alert alert-success small"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold">Send Reset Link</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="login.php" class="text-decoration-none small text-muted">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
