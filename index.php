<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<header class="hero-section text-white text-center py-5 mb-5" style="background: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(10, 88, 202, 0.8)), url('assets/images/banner.jpg') no-repeat center center/cover;">
    <div class="container">
        <h1 class="display-3 fw-bold hero-title" data-aos="fade-up">Ride Your Dream</h1>
        <p class="lead mb-4" data-aos="fade-up" data-aos-delay="200">Discover the best premium bikes with unbeatable offers.</p>
        <a href="bikes.php" class="btn btn-primary btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg" data-aos="zoom-in" data-aos-delay="400">Explore Now</a>
    </div>
</header>

<!-- Featured Bikes Section -->
<section class="container mb-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="fw-bold">Featured Bikes</h2>
        <p class="text-muted">Handpicked machines for the true enthusiast</p>
    </div>

    <div class="row g-4">
        <?php
        // Fetch featured bikes
        $sql = "SELECT b.*, br.brand_name, c.category_name, 
                (SELECT image_path FROM bike_images WHERE bike_id = b.bike_id AND is_primary = 1 LIMIT 1) as primary_image
                FROM bikes b 
                LEFT JOIN brands br ON b.brand_id = br.brand_id
                LEFT JOIN categories c ON b.category_id = c.category_id
                WHERE b.is_featured = 1 AND b.status = 'available'
                LIMIT 3";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Image Handling
                $img_path = $row['primary_image'];
                $image = ($img_path && strpos($img_path, 'http') === 0) ? $img_path : 
                         (($img_path && file_exists(__DIR__ . "/uploads/bikes/" . $img_path)) ? "uploads/bikes/" . htmlspecialchars($img_path) : "https://placehold.co/600x400?text=No+Image");
                ?>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-sm bike-card">
                        <img src="<?php echo $image; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>" style="height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['brand_name'] ?? 'Brand'); ?></span>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($row['category_name'] ?? 'Category'); ?></span>
                            </div>
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="card-text text-muted small"><?php echo substr($row['description'], 0, 80) . '...'; ?></p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <h5 class="text-primary mb-0"><?php echo formatCurrency($row['price']); ?></h5>
                                <a href="bike_details.php?id=<?php echo $row['bike_id']; ?>" class="btn btn-outline-dark btn-sm stretched-link">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<div class='col-12 text-center text-muted'>No featured bikes available.</div>";
        }
        ?>
    </div>
</section>


<!-- Why Choose Us -->
<section class="bg-light py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-5">Why Choose WheelMasters?</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <i class="fas fa-tag fa-3x text-primary mb-3"></i>
                <h4>Best Prices</h4>
                <p class="text-muted">We offer the most competitive prices and exclusive EMI options.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                <h4>Verified Condition</h4>
                <p class="text-muted">Every bike passes a rigorous 50-point quality check.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                <h4>24/7 Support</h4>
                <p class="text-muted">Our expert team is always here to assist with your purchase.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
