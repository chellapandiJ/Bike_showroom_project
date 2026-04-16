<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';

// Handle Add Bike
if(isset($_POST['add_bike'])){
    $name = $_POST['name'];
    $price = $_POST['price'];
    $cc = $_POST['engine_cc'];
    $category_id = $_POST['category_id']; // Assuming categories exist, simplified for now
    
    // Image Upload
    $target_dir = "../uploads/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    $image_path = "uploads/" . basename($_FILES["image"]["name"]);

    $sql = "INSERT INTO bikes (name, price, engine_cc, category_id, image) VALUES ('$name', '$price', '$cc', '$category_id', '$image_path')";
    if($conn->query($sql)){
        $msg = "Bike Added Successfully";
    } else {
        $error = "Error adding bike: " . $conn->error;
    }
}

// Handle Delete
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM bikes WHERE bike_id=$id");
    header("Location: manage_bikes.php");
}

$bikes = $conn->query("SELECT * FROM bikes");
?>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>BIKE MANAGEMENT</h2>
        <button class="btn btn-venum" data-bs-toggle="modal" data-bs-target="#addBikeModal">
            <i class="fas fa-plus"></i> Add Bike
        </button>
    </div>

    <div class="row">
        <?php while($row = $bikes->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="../<?php echo $row['image']; ?>" class="card-img-top" alt="Bike">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['name']; ?></h5>
                    <p class="card-text text-warning fw-bold">$<?php echo number_format($row['price']); ?></p>
                    <p class="small text-muted"><?php echo $row['engine_cc']; ?> CC</p>
                    <a href="manage_bikes.php?delete=<?php echo $row['bike_id']; ?>" 
                       class="btn btn-danger-venum w-100" 
                       onclick="return confirm('Delete this bike?')">Delete Bike</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Add Bike Modal -->
<div class="modal fade" id="addBikeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white border-warning">
            <div class="modal-header border-warning">
                <h5 class="modal-title">Add New Bike</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Bike Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Price</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Engine CC</label>
                        <input type="text" name="engine_cc" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                    <!-- Simplified Category for now (use input or static select) -->
                     <div class="mb-3">
                        <label>Category ID (1: Sport, 2: Scooter)</label>
                        <input type="number" name="category_id" class="form-control" value="1">
                    </div>
                    <button type="submit" name="add_bike" class="btn btn-venum w-100">Add Bike</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
