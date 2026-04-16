<?php
session_start();

$conn = new mysqli("localhost", "root", "", "bikes");
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM staff WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    // ✅ Plain password check
    if ($password === $row['password']) {

        $_SESSION['staff_id'] = $row['id'];
        $_SESSION['staff_username'] = $row['username'];

        header("Location: staffdashboard.php");
        exit();
    } else {
        header("Location: stafflogin.php?error=wrongpassword");
        exit();
    }
} else {
    header("Location: stafflogin.php?error=usernotfound");
    exit();
}

$stmt->close();
$conn->close();
?>
