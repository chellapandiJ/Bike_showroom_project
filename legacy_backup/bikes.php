<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

$sql = "SELECT * FROM bikes";
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $sql .= " WHERE name LIKE '%$search%'";
}
$bikes = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">ALL BIKES & SCOOTERS</h2>
    
    <!-- Search Filter -->
    <div class="row mb-5 justify-content-center">
        <div class="col-md-6">
            <form action="" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by name...">
                <button class="btn btn-venum">Search</button>
            </form>
        </div>
    </div>

    <div class="row">
        <?php if($bikes->num_rows > 0): ?>
            <?php while($bike = $bikes->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                     <img src="<?php echo $bike['image'] ? $bike['image'] : 'https://via.placeholder.com/300'; ?>" class="card-img-top" alt="Bike">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $bike['name']; ?></h5>
                        <p class="card-text text-muted"><?php echo $bike['engine_cc']; ?> CC | <?php echo $bike['fuel_type']; ?></p>
                        <h4 class="text-warning">$<?php echo number_format($bike['price']); ?></h4>
                        <a href="bike_details.php?id=<?php echo $bike['bike_id']; ?>" class="btn btn-venum w-100 mt-2">View Details</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p>No bikes found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
