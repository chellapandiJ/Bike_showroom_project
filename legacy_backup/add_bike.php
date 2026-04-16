<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bikes");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

if (isset($_POST['add_bike'])) {
    $bike_name   = $_POST['bike_name'];
    $price       = $_POST['price'];
    $color       = $_POST['color'];
    $engine_cc   = $_POST['engine_cc'];
    $mileage     = $_POST['mileage'];
    $speed       = $_POST['speed'];
    $test_drive  = $_POST['test_drive']; // yes/no
    $stock       = $_POST['stock'];

    // Set default status to Pending
    $status = "Pending";

    // Handle image upload
    $image_name = "";
    $upload_dir = "uploads/";

    // Create uploads folder if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
        // Replace spaces in filename with underscores
        $original_name = str_replace(" ", "_", $_FILES['image']['name']);
        $image_name = time() . "_" . $original_name;

        // Move uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
            $error = "Failed to upload image!";
        }
    }

    // Insert into database if no upload error
    if (!$error) {
        if ($bike_name && $price && $color && $engine_cc && $mileage && $speed && $stock) {
            $sql = "INSERT INTO bikes 
                (bike_name, price, color, engine_cc, mileage, speed, test_drive, stock, image, status)
                VALUES 
                ('$bike_name', '$price', '$color', '$engine_cc', '$mileage', '$speed', '$test_drive', '$stock', '$image_name', '$status')";

            if ($conn->query($sql)) {
                $success = "Bike added successfully! Waiting for admin approval.";
            } else {
                $error = "Error adding bike: " . $conn->error;
            }
        } else {
            $error = "Please fill all required fields!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New Bike</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4">
        <h4 class="mb-3">Add New Bike</h4>

        <?php if ($success) { ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php } ?>
        <?php if ($error) { ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php } ?>

        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Bike Name</label>
                <input type="text" name="bike_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Price</label>
                <input type="number" name="price" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label>Color</label>
                <input type="text" name="color" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Engine CC</label>
                <input type="text" name="engine_cc" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Mileage</label>
                <input type="text" name="mileage" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Top Speed</label>
                <input type="text" name="speed" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Test Drive Available?</label>
                <select name="test_drive" class="form-control" required>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Bike Image</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button name="add_bike" class="btn btn-primary">Add Bike</button>
            <a href="staffdashboard.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

</body>
</html>
