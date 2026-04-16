<?php
session_start();
include 'includes/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Since we might use plain text for initial seeding or hashed, let's check both or verify logic
        // For security, using password_verify. Assuming user registers with hash.
        // If password in DB is not hashed (legacy), this might fail. We'll assume new system.
        // For the 'admin' seed in SQL, if it was plain text, we should check that too for dev.
        
        $verified = false;
        if (password_verify($password, $row['password'])) {
            $verified = true;
        } elseif ($password === $row['password']) {
            // Fallback for plain text admin (remove in production)
            $verified = true;
        }

        if ($verified) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } elseif ($row['role'] == 'staff') {
                header("Location: staff/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="auth-container">
    <div class="auth-box animate-fade-up">
        <h2 class="text-center mb-4 text-warning">LOGIN</h2>
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="Enter your email">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Enter your password">
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-venum">Login Now</button>
            </div>
            <div class="text-center mt-3">
                <a href="register.php" class="text-muted">Don't have an account? Register</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
