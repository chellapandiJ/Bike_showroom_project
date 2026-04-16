<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

// Build Query with Filters
$where_clauses = ["status = 'available'"];
$params = [];
$types = "";

// Filter by Brand
if (isset($_GET['brand']) && !empty($_GET['brand'])) {
    $where_clauses[] = "b.brand_id = ?";
    $params[] = $_GET['brand'];
    $types .= "i";
}

// Filter by Category
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $where_clauses[] = "b.category_id = ?";
    $params[] = $_GET['category'];
    $types .= "i";
}

// Filter by Search
if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search = "%" . $_GET['q'] . "%";
    $where_clauses[] = "(b.name LIKE ? OR br.brand_name LIKE ?)";
    $params[] = $search;
    $params[] = $search;
    $types .= "ss";
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

$sql = "SELECT b.*, br.brand_name, c.category_name,
        (SELECT image_path FROM bike_images WHERE bike_id = b.bike_id AND is_primary = 1 LIMIT 1) as primary_image
        FROM bikes b
        LEFT JOIN brands br ON b.brand_id = br.brand_id
        LEFT JOIN categories c ON b.category_id = c.category_id
        $where_sql
        ORDER BY b.created_at DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0 filter-card sticky-top" style="top: 100px; z-index: 90;">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-filter me-2 text-primary"></i> Filters</span>
                    <a href="bikes.php" class="text-decoration-none small">Clear</a>
                </div>
                <div class="card-body">
                    <form action="bikes.php" method="GET">
                        <!-- Search -->
                        <div class="mb-4">
                             <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="q" class="form-control border-start-0 bg-light" placeholder="Search bikes..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                             </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Brand</label>
                            <select name="brand" class="form-select">
                                <option value="">All Brands</option>
                                <?php
                                $brands = $conn->query("SELECT * FROM brands ORDER BY brand_name");
                                while($brand = $brands->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $brand['brand_id']; ?>" <?php echo (isset($_GET['brand']) && $_GET['brand'] == $brand['brand_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($brand['brand_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Category Filter -->
                        <div class="mb-4">
                             <label class="form-label small fw-bold text-uppercase text-muted">Category</label>
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                <?php
                                $cats = $conn->query("SELECT * FROM categories ORDER BY category_name");
                                while($cat = $cats->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $cat['category_id']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['category_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['category_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="d-grid">
                             <button type="submit" class="btn btn-primary fw-bold">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bike Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">Our Collection</h3>
                <span class="text-muted small"><?php echo $result->num_rows; ?> bikes found</span>
            </div>
            
            <div class="row g-4">
                <?php if($result->num_rows > 0): ?>
                    <?php 
                    $delay = 0;
                    while($row = $result->fetch_assoc()): 
                        // Handle Image Path (Local vs URL)
                        $img_path = $row['primary_image'];
                        if (filter_var($img_path, FILTER_VALIDATE_URL)) {
                             $image = $img_path;
                        } else {
                             $image = $img_path ? "uploads/bikes/" . htmlspecialchars($img_path) : "assets/images/placeholder.png";
                        }
                    ?>
                        <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                            <div class="card h-100 border-0 shadow-sm bike-card">
                                <div class="position-relative overflow-hidden">
                                    <img src="<?php echo $image; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>" onerror="this.src='assets/images/placeholder.png'">
                                    <?php if($row['stock'] <= 0): ?>
                                        <div class="position-absolute top-0 end-0 m-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.7);">
                                             <span class="badge bg-danger fs-6 shadow">Out of Stock</span>
                                        </div>
                                    <?php elseif($row['stock'] < 5): ?>
                                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3 shadow">Low Stock</span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2 small text-muted">
                                        <span><i class="fas fa-tag text-primary"></i> <?php echo htmlspecialchars($row['brand_name']); ?></span>
                                        <span><i class="fas fa-gas-pump text-success"></i> <?php echo htmlspecialchars($row['mileage']); ?></span>
                                    </div>
                                    <h5 class="card-title fw-bold text-truncate"><?php echo htmlspecialchars($row['name']); ?></h5>
                                    <h5 class="text-primary fw-bold mb-3"><?php echo formatCurrency($row['price']); ?></h5>
                                    <div class="d-grid">
                                        <a href="bike_details.php?id=<?php echo $row['bike_id']; ?>" class="btn btn-outline-primary btn-sm rounded-pill">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                        $delay += 50;
                        if($delay > 300) $delay = 0; // Reset delay for infinite scroll feel
                    endwhile; 
                    ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="py-5 bg-white rounded shadow-sm">
                             <i class="far fa-frown fa-4x text-muted mb-3"></i>
                             <h4 class="text-muted">No bikes found matching your criteria.</h4>
                             <a href="bikes.php" class="btn btn-primary mt-3">Reset Filters</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
