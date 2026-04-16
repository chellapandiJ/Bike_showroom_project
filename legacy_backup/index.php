<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Fetch Latest Bikes
$latestBikes = $conn->query("SELECT * FROM bikes ORDER BY created_at DESC LIMIT 6");
?>

<!-- Banner Slider -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active" style="height: 600px;">
            <img src="assets/images/a.jpg" class="d-block w-100 h-100" style="object-fit: cover; filter: brightness(0.6);" alt="Banner 1">
            <div class="carousel-caption d-none d-md-block top-50 translate-middle-y">
                <h1 class="display-1 fw-bold text-warning animate-fade-up">RIDE THE LEGEND</h1>
                <p class="lead text-white animate-fade-up">Experience the thrill of the open road.</p>
                <a href="bikes.php" class="btn btn-venum animate-fade-up mt-3">Explore Models</a>
            </div>
        </div>
        <div class="carousel-item" style="height: 600px;">
             <img src="assets/images/b.jpg" class="d-block w-100 h-100" style="object-fit: cover; filter: brightness(0.6);" alt="Banner 2">
             <div class="carousel-caption d-none d-md-block top-50 translate-middle-y">
                <h1 class="display-1 fw-bold text-danger animate-fade-up">UNLEASH POWER</h1>
                <p class="lead text-white animate-fade-up">Top performance machines available now.</p>
                <a href="register.php" class="btn btn-venum animate-fade-up mt-3">Join Us</a>
            </div>
        </div>
        <div class="carousel-item" style="height: 600px;">
             <img src="assets/images/c.jpg" class="d-block w-100 h-100" style="object-fit: cover; filter: brightness(0.6);" alt="Banner 3">
             <div class="carousel-caption d-none d-md-block top-50 translate-middle-y">
                <h1 class="display-1 fw-bold text-info animate-fade-up">PREMIUM RIDES</h1>
                <p class="lead text-white animate-fade-up">Exclusive offers on top brands.</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Categories -->
<div class="container mt-5">
    <h2 class="text-center mb-5"><span class="text-warning">SHOP BY</span> CATEGORY</h2>
    <div class="row text-center">
        <div class="col-md-4">
            <div class="card bg-dark p-4 border-warning">
                <i class="fas fa-bolt fa-3x text-warning mb-3"></i>
                <h4>Electric</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark p-4 border-danger">
                <i class="fas fa-motorcycle fa-3x text-danger mb-3"></i>
                <h4>Sports</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark p-4 border-info">
                <i class="fas fa-shuttle-van fa-3x text-info mb-3"></i>
                <h4>Scooter</h4>
            </div>
        </div>
    </div>
</div>

<!-- Latest Bikes -->
<div class="container mt-5 mb-5">
    <h2 class="text-center mb-5">LATEST ARRIVALS</h2>
    <div class="row">
        <?php if($latestBikes->num_rows > 0): ?>
            <?php while($bike = $latestBikes->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?php echo $bike['image'] ? $bike['image'] : 'https://via.placeholder.com/300'; ?>" class="card-img-top" alt="Bike">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $bike['name']; ?></h5>
                        <h4 class="text-warning">$<?php echo number_format($bike['price']); ?></h4>
                        <a href="bike_details.php?id=<?php echo $bike['bike_id']; ?>" class="btn btn-outline-warning w-100 mt-2">View Details</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No bikes available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Offers Section -->
<div class="container-fluid bg-danger text-white py-5 mt-5">
    <div class="container text-center">
        <h2>🔥 SPECIAL EMI OFFERS AVAILABLE 🔥</h2>
        <p class="lead">Get your dream bike with 0% interest on select models.</p>
        <button class="btn btn-dark btn-lg mt-3">Check Offers</button>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
