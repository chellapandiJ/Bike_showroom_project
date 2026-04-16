<?php
// Key Helper Functions

// Sanitize Input
function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

// Redirect Helper
// Redirect Helper
function redirect($url) {
    if (!headers_sent()) {
        header("Location: " . $url);
    } else {
        echo "<script>window.location.href='$url';</script>";
    }
    exit();
}

// Check if User is Logged In
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check Role & Enforce Access
function checkRole($info = []) {
    if (!isset($_SESSION['user_id'])) {
        redirect('../login.php');
    }
    if (!in_array($_SESSION['role'], $info)) {
        echo "<script>alert('Access Denied!'); window.history.back();</script>";
        exit();
    }
}

// Flash Message Helper
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type, // success, error, warning
        'text' => $message
    ];
}

function displayFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        $alertType = ($msg['type'] == 'success') ? 'alert-success' : (($msg['type'] == 'error') ? 'alert-danger' : 'alert-warning');
        echo "<div class='alert $alertType alert-dismissible fade show' role='alert'>
                {$msg['text']}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
        unset($_SESSION['flash_message']);
    }
}

// Currency Formatter (Indian Rupee)
function formatCurrency($amount) {
    return '₹ ' . number_format($amount, 2);
}

// Log Staff Activity
function logActivity($conn, $staff_id, $activity, $details = '') {
    $stmt = $conn->prepare("INSERT INTO staff_activity_logs (staff_id, activity, details) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $staff_id, $activity, $details); // 'i' for integer
    $stmt->execute();
}
?>
