<?php
require_once 'includes/db.php';

// Clear existing bikes to avoid duplicates for this demo
$conn->query("SET FOREIGN_KEY_CHECKS = 0");
$conn->query("TRUNCATE TABLE bikes");
$conn->query("TRUNCATE TABLE bike_images");
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

echo "Tables cleared. Seeding data...\n";

// Ensure Brands Exist
$brands = ['Yamaha', 'Royal Enfield', 'Honda', 'KTM', 'TVS', 'Bajaj', 'Kawasaki', 'Hero', 'Suzuki', 'Ducati'];
$brand_ids = [];
foreach ($brands as $b) {
    $conn->query("INSERT IGNORE INTO brands (brand_name) VALUES ('$b')");
    // Get ID
    $res = $conn->query("SELECT brand_id FROM brands WHERE brand_name='$b'");
    $brand_ids[$b] = $res->fetch_assoc()['brand_id'];
}

// Ensure Categories Exist
$cats = ['Sports', 'Cruiser', 'Commuter', 'Scooter', 'Off-road', 'Superbike', 'Cafe Racer'];
$cat_ids = [];
foreach ($cats as $c) {
    $conn->query("INSERT IGNORE INTO categories (category_name) VALUES ('$c')");
    $res = $conn->query("SELECT category_id FROM categories WHERE category_name='$c'");
    $cat_ids[$c] = $res->fetch_assoc()['category_id'];
}

// 40 Bike Data
$bike_data = [
    ['YZF R15 V4', 'Yamaha', 'Sports', 182000, '155', '45 kmpl'],
    ['MT-15 V2', 'Yamaha', 'Sports', 168000, '155', '48 kmpl'],
    ['Classic 350', 'Royal Enfield', 'Cruiser', 220000, '349', '35 kmpl'],
    ['Himalayan 450', 'Royal Enfield', 'Off-road', 285000, '452', '30 kmpl'],
    ['Hunter 350', 'Royal Enfield', 'Cruiser', 169000, '349', '36 kmpl'],
    ['Duke 390', 'KTM', 'Sports', 311000, '373', '29 kmpl'],
    ['RC 200', 'KTM', 'Sports', 218000, '199', '35 kmpl'],
    ['Apache RTR 160 4V', 'TVS', 'Sports', 124000, '160', '45 kmpl'],
    ['Jupiter 125', 'TVS', 'Scooter', 86000, '125', '50 kmpl'],
    ['Activa 6G', 'Honda', 'Scooter', 76000, '110', '50 kmpl'],
    ['CB350 RS', 'Honda', 'Cruiser', 215000, '348', '35 kmpl'],
    ['Pulsar NS200', 'Bajaj', 'Sports', 142000, '200', '36 kmpl'],
    ['Dominar 400', 'Bajaj', 'Cruiser', 230000, '373', '29 kmpl'],
    ['Ninja 300', 'Kawasaki', 'Sports', 343000, '296', '26 kmpl'],
    ['Splendor Plus', 'Hero', 'Commuter', 75000, '97', '65 kmpl'],
    ['Xpulse 200 4V', 'Hero', 'Off-road', 146000, '200', '40 kmpl'],
    ['Hayabusa', 'Suzuki', 'Superbike', 1690000, '1340', '11 kmpl'],
    ['Gixxer SF 250', 'Suzuki', 'Sports', 192000, '249', '38 kmpl'],
    ['Panigale V4', 'Ducati', 'Superbike', 2700000, '1103', '10 kmpl'],
    ['Continental GT 650', 'Royal Enfield', 'Cafe Racer', 345000, '648', '25 kmpl'],
    ['FZs V3', 'Yamaha', 'Commuter', 121000, '149', '46 kmpl'],
    ['Aerox 155', 'Yamaha', 'Scooter', 147000, '155', '40 kmpl'],
    ['Dio 125', 'Honda', 'Scooter', 83000, '125', '48 kmpl'],
    ['Hornet 2.0', 'Honda', 'Sports', 139000, '184', '45 kmpl'],
    ['Raider 125', 'TVS', 'Commuter', 95000, '125', '57 kmpl'],
    ['Ronin', 'TVS', 'Cruiser', 149000, '225', '42 kmpl'],
    ['Avenger 220 Cruise', 'Bajaj', 'Cruiser', 143000, '220', '40 kmpl'],
    ['Platina 110', 'Bajaj', 'Commuter', 68000, '115', '70 kmpl'],
    ['Xtreme 160R', 'Hero', 'Sports', 121000, '163', '48 kmpl'],
    ['Karizma XMR', 'Hero', 'Sports', 180000, '210', '35 kmpl'],
    ['V-Strom SX', 'Suzuki', 'Off-road', 212000, '250', '36 kmpl'],
    ['Burgman Street', 'Suzuki', 'Scooter', 94000, '125', '48 kmpl'],
    ['Z900', 'Kawasaki', 'Superbike', 920000, '948', '17 kmpl'],
    ['ZX-10R', 'Kawasaki', 'Superbike', 1660000, '998', '15 kmpl'],
    ['Monster', 'Ducati', 'Sports', 1295000, '937', '18 kmpl'],
    ['Scrambler Icon', 'Ducati', 'Cafe Racer', 939000, '803', '20 kmpl'],
    ['Adventure 390', 'KTM', 'Off-road', 360000, '373', '28 kmpl'],
    ['Duke 200', 'KTM', 'Sports', 196000, '200', '35 kmpl'],
    ['Bullet 350', 'Royal Enfield', 'Cruiser', 173000, '349', '37 kmpl'],
    ['Chetak', 'Bajaj', 'Scooter', 120000, 'Electric', '90 km/charge']
];

$stmt = $conn->prepare("INSERT INTO bikes (name, brand_id, category_id, price, engine_cc, mileage, fuel_type, stock, description, status, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'available', ?)");

foreach ($bike_data as $index => $bike) {
    $name = $bike[0];
    $bid = $brand_ids[$bike[1]];
    $cid = $cat_ids[$bike[2]];
    $price = $bike[3];
    $cc = $bike[4];
    $mil = $bike[5];
    $fuel = ($bike[2] == 'Scooter' && $cc == 'Electric') ? 'Electric' : 'Petrol';
    $stock = rand(2, 15);
    $desc = "The all-new $name from " . $bike[1] . " offers a perfect blend of style and performance. Best in class " . $bike[2] . " bike with $cc CC engine.";
    $feat = ($index < 6) ? 1 : 0; // First 6 featured

    $stmt->bind_param("siidsssiis", $name, $bid, $cid, $price, $cc, $mil, $fuel, $stock, $desc, $feat);
    $stmt->execute();
    $last_id = $conn->insert_id;
    
    // Use Online Images
    // Keywords tailored to category
    $keyword = strtolower($bike[2]);
    $random_sig = rand(1000, 9999);
    $online_image_url = "https://source.unsplash.com/800x600/?" . urlencode("motorcycle $keyword") . "&sig=$random_sig";
    // Since we can't save the actual file easily without curl, we will just store the URL in the DB 
    // OR use a specific service that returns an image we can hotlink comfortably in a local env demo.
    // Let's use a reliable placeholder service that is fast.
    $colors = ['red', 'blue', 'black', 'white', 'orange'];
    $color = $colors[array_rand($colors)];
    // Using a different robust service
    $img_filename_mock = "https://loremflickr.com/800/600/motorcycle," . $color . "/all?lock=" . $index;
    
    // We update the schema to support URL if it's not a file path? 
    // The current code expects 'uploads/bikes/...'. 
    // We will cheat slightly and store the full URL, and update the frontend to handle it.
    
    $img_sql = "INSERT INTO bike_images (bike_id, image_path, is_primary) VALUES ($last_id, '$img_filename_mock', 1)";
    $conn->query($img_sql);
}

echo "Successfully added " . count($bike_data) . " bikes with dummy images!\n";
?>
