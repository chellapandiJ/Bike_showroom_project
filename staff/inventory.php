<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['staff']);

if (isset($_POST['update_stock'])) {
    $id = intval($_POST['bike_id']);
    $stock = intval($_POST['stock']);
    $conn->query("UPDATE bikes SET stock=$stock WHERE bike_id=$id");
    
    // Log
    $user_id = $_SESSION['user_id'];
    logActivity($conn, $user_id, 'Stock Update', "Updated stock for Bike ID $id to $stock");
    
    setFlashMessage('success', 'Stock updated via Quick Edit.');
    redirect('inventory.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory - Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold mb-4">Inventory Status</h2>
            <p class="text-muted">View only.</p>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Bike Model</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock Available</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT b.*, c.category_name FROM bikes b JOIN categories c ON b.category_id = c.category_id ORDER BY b.stock ASC");
                            while($row = $res->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                <td><?php echo formatCurrency($row['price']); ?></td>
                                <td>
                                    <form method="POST" class="d-flex align-items-center">
                                        <input type="hidden" name="bike_id" value="<?php echo $row['bike_id']; ?>">
                                        <input type="hidden" name="update_stock" value="1">
                                        <input type="number" name="stock" class="form-control form-control-sm me-2" style="width: 80px;" value="<?php echo $row['stock']; ?>" min="0">
                                        <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-save"></i></button>
                                    </form>
                                </td>
                                <td>
                                    <?php if($row['stock'] == 0): ?>
                                        <span class="badge bg-danger">Out of Stock</span>
                                    <?php elseif($row['stock'] < 5): ?>
                                        <span class="badge bg-warning text-dark">Low Stock</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">In Stock</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
