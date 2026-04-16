<?php
session_start();

$conn = new mysqli("localhost", "root", "", "bikes");
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM admin WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    // ✅ Plain password check (college project)
    if ($password === $row['password']) {

        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_username'] = $row['username'];

        header("Location: admindashboard.php");
        exit();
    } else {
        header("Location: admin.php?error=wrongpassword");
        exit();
    }
} else {
    header("Location: admin.php?error=usernotfound");
    exit();
}

$stmt->close();
$conn->close();
?>
