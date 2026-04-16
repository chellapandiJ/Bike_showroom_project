<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['admin']);

$bike = null;
$action = "Add Bike";
$images = [];

// Handle Edit Fetch
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $bike = $conn->query("SELECT * FROM bikes WHERE bike_id = $id")->fetch_assoc();
    $action = "Edit Bike";
    $images = $conn->query("SELECT * FROM bike_images WHERE bike_id = $id");
}

// Handle Form Submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($conn, $_POST['name']);
    $brand_id = $_POST['brand_id'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $engine = sanitize($conn, $_POST['engine_cc']);
    $mileage = sanitize($conn, $_POST['mileage']);
    $fuel = sanitize($conn, $_POST['fuel_type']);
    $brakes = sanitize($conn, $_POST['brakes']);
    $description = sanitize($conn, $_POST['description']);
    $stock = $_POST['stock'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    if (isset($_POST['bike_id']) && !empty($_POST['bike_id'])) {
        // Update
        $id = $_POST['bike_id'];
        $sql = "UPDATE bikes SET name=?, brand_id=?, category_id=?, price=?, engine_cc=?, mileage=?, fuel_type=?, brakes=?, description=?, stock=?, is_featured=? WHERE bike_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siidsssssiii", $name, $brand_id, $category_id, $price, $engine, $mileage, $fuel, $brakes, $description, $stock, $is_featured, $id);
        $stmt->execute();
        $bike_id = $id;
        setFlashMessage('success', 'Bike updated successfully.');
    } else {
        // Insert
        $sql = "INSERT INTO bikes (name, brand_id, category_id, price, engine_cc, mileage, fuel_type, brakes, description, stock, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siidsssssii", $name, $brand_id, $category_id, $price, $engine, $mileage, $fuel, $brakes, $description, $stock, $is_featured);
        $stmt->execute();
        $bike_id = $conn->insert_id;
        setFlashMessage('success', 'Bike added successfully.');
    }

    // Handle Image Upload
    if (!empty($_FILES['images']['name'][0])) {
        $upload_dir = "../uploads/bikes/";
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $filename = time() . "_" . basename($_FILES['images']['name'][$key]);
            $target = $upload_dir . $filename;
            if (move_uploaded_file($tmp_name, $target)) {
                $conn->query("INSERT INTO bike_images (bike_id, image_path, is_primary) VALUES ($bike_id, '$filename', 0)");
            }
        }
        // Set first image as primary if none exists
        $conn->query("UPDATE bike_images SET is_primary=1 WHERE bike_id=$bike_id AND is_primary=0 LIMIT 1");
    }

    redirect('manage_bikes.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $action; ?> - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold mb-4"><?php echo $action; ?></h2>
            
            <form method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
                <input type="hidden" name="bike_id" value="<?php echo $bike['bike_id'] ?? ''; ?>">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bike Name</label>
                        <input type="text" name="name" class="form-control" required value="<?php echo $bike['name'] ?? ''; ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" class="form-select" required>
                            <?php 
                            $brands = $conn->query("SELECT * FROM brands");
                            while($b = $brands->fetch_assoc()): ?>
                                <option value="<?php echo $b['brand_id']; ?>" <?php echo ($bike && $bike['brand_id'] == $b['brand_id']) ? 'selected' : ''; ?>>
                                    <?php echo $b['brand_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <?php 
                            $cats = $conn->query("SELECT * FROM categories");
                            while($c = $cats->fetch_assoc()): ?>
                                <option value="<?php echo $c['category_id']; ?>" <?php echo ($bike && $bike['category_id'] == $c['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo $c['category_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" required value="<?php echo $bike['price'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Engine CC</label>
                        <input type="text" name="engine_cc" class="form-control" required value="<?php echo $bike['engine_cc'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Mileage</label>
                        <input type="text" name="mileage" class="form-control" required value="<?php echo $bike['mileage'] ?? ''; ?>">
                    </div>
                </div>

                <div class="row">
                     <div class="col-md-4 mb-3">
                        <label class="form-label">Fuel Type</label>
                        <select name="fuel_type" class="form-select">
                            <option value="Petrol" <?php echo ($bike && $bike['fuel_type'] == 'Petrol') ? 'selected' : ''; ?>>Petrol</option>
                            <option value="Electric" <?php echo ($bike && $bike['fuel_type'] == 'Electric') ? 'selected' : ''; ?>>Electric</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Brakes</label>
                        <input type="text" name="brakes" class="form-control" placeholder="e.g. ABS Disc" value="<?php echo $bike['brakes'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock" class="form-control" required value="<?php echo $bike['stock'] ?? '1'; ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?php echo $bike['description'] ?? ''; ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Images</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    <small class="text-muted">You can select multiple images.</small>
                    <?php if(!empty($images) && $images->num_rows > 0): ?>
                        <div class="d-flex mt-2 gap-2">
                        <?php while($img = $images->fetch_assoc()): ?>
                            <img src="../uploads/bikes/<?php echo $img['image_path']; ?>" width="80" class="rounded border">
                        <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="feat" name="is_featured" <?php echo ($bike && $bike['is_featured']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="feat">Mark as Featured (Show on Home Page)</label>
                </div>

                <button type="submit" class="btn btn-success px-4"><?php echo $action; ?></button>
                <a href="manage_bikes.php" class="btn btn-secondary">Cancel</a>
            </form>
        </main>
    </div>
</div>

</body>
</html>
