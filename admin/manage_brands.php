<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['admin']);

// Handle Add/Delete Brand
if (isset($_POST['add_brand'])) {
    $name = sanitize($conn, $_POST['brand_name']);
    $conn->query("INSERT INTO brands (brand_name) VALUES ('$name')");
    setFlashMessage('success', 'Brand added.');
    redirect('manage_brands.php');
}
if (isset($_GET['del_brand'])) {
    $id = intval($_GET['del_brand']);
    $conn->query("DELETE FROM brands WHERE brand_id=$id"); // Add constraint check ideally
    setFlashMessage('success', 'Brand deleted.');
    redirect('manage_brands.php');
}

// Handle Add/Delete Category
if (isset($_POST['add_cat'])) {
    $name = sanitize($conn, $_POST['cat_name']);
    $conn->query("INSERT INTO categories (category_name) VALUES ('$name')");
    setFlashMessage('success', 'Category added.');
    redirect('manage_brands.php');
}
if (isset($_GET['del_cat'])) {
    $id = intval($_GET['del_cat']);
    $conn->query("DELETE FROM categories WHERE category_id=$id");
    setFlashMessage('success', 'Category deleted.');
    redirect('manage_brands.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Brands & Categories - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold mb-4">Brands & Categories</h2>
            
            <?php if(function_exists('displayFlashMessage')) displayFlashMessage(); ?>

            <div class="row">
                <!-- Brands Column -->
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                            <span>Manage Brands</span>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#brandModal"><i class="fas fa-plus"></i> Add</button>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <?php
                                $brands = $conn->query("SELECT * FROM brands");
                                while($b = $brands->fetch_assoc()):
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo htmlspecialchars($b['brand_name']); ?>
                                    <a href="manage_brands.php?del_brand=<?php echo $b['brand_id']; ?>" class="text-danger" onclick="return confirm('Delete this brand?');"><i class="fas fa-trash"></i></a>
                                </li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Categories Column -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                         <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                            <span>Manage Categories</span>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#catModal"><i class="fas fa-plus"></i> Add</button>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <?php
                                $cats = $conn->query("SELECT * FROM categories");
                                while($c = $cats->fetch_assoc()):
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo htmlspecialchars($c['category_name']); ?>
                                    <a href="manage_brands.php?del_cat=<?php echo $c['category_id']; ?>" class="text-danger" onclick="return confirm('Delete this category?');"><i class="fas fa-trash"></i></a>
                                </li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Add Brand Modal -->
<div class="modal fade" id="brandModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form method="POST" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Add Brand</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="text" name="brand_name" class="form-control" placeholder="Brand Name" required>
                <input type="hidden" name="add_brand" value="1">
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></div>
        </form>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="catModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form method="POST" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Add Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="text" name="cat_name" class="form-control" placeholder="Category Name" required>
                <input type="hidden" name="add_cat" value="1">
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
