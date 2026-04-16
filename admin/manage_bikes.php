<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['admin']);

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Check for orders? Or just delete?
    // Foreign keys might verify constraints.
    // For now, let's delete strictly.
    $conn->query("DELETE FROM bike_images WHERE bike_id = $id");
    if ($conn->query("DELETE FROM bikes WHERE bike_id = $id")) {
        setFlashMessage('success', 'Bike deleted successfully.');
    } else {
        setFlashMessage('error', 'Cannot delete bike. It may be linked to orders.');
    }
    redirect('manage_bikes.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bikes - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Manage Bikes</h2>
                <a href="add_bike.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Bike</a>
            </div>

            <?php if(function_exists('displayFlashMessage')) displayFlashMessage(); ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT b.*, br.brand_name, c.category_name, 
                                        (SELECT image_path FROM bike_images WHERE bike_id = b.bike_id LIMIT 1) as image_path
                                        FROM bikes b 
                                        LEFT JOIN brands br ON b.brand_id = br.brand_id
                                        LEFT JOIN categories c ON b.category_id = c.category_id
                                        ORDER BY b.bike_id DESC";
                                $res = $conn->query($sql);
                                while($row = $res->fetch_assoc()):
                                    $img_path = $row['image_path'];
                                    if (filter_var($img_path, FILTER_VALIDATE_URL)) {
                                        $image = $img_path;
                                    } else {
                                        $image = $img_path ? "../uploads/bikes/" . htmlspecialchars($img_path) : "../assets/images/placeholder.png";
                                    }
                                ?>
                                <tr>
                                    <td><?php echo $row['bike_id']; ?></td>
                                    <td><img src="<?php echo $image; ?>" width="60" height="40" class="rounded object-fit-cover" onerror="this.src='../assets/images/placeholder.png'"></td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['brand_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                    <td><?php echo formatCurrency($row['price']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['stock'] > 0 ? 'success' : 'danger'; ?>">
                                            <?php echo $row['stock']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="add_bike.php?edit=<?php echo $row['bike_id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        <a href="manage_bikes.php?delete=<?php echo $row['bike_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
