<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bikes");
if ($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Fetch all bikes with current stock
$sql = "SELECT * FROM bikes ORDER BY stock ASC"; // low stock first
$result = $conn->query($sql);
$bikes = [];
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $bikes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bike Stock View</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.low-stock {background:#f8d7da;} /* red for low stock */
.medium-stock {background:#fff3cd;} /* yellow for medium */
.high-stock {background:#d4edda;} /* green for high */
</style>
</head>
<body>
<div class="container mt-4">
    <h2>Bike Stock Overview</h2>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Bike ID</th>
                <th>Bike Name</th>
                <th>Model</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if(count($bikes) > 0): ?>
            <?php foreach($bikes as $bike): 
                $class = $bike['stock'] <= 2 ? 'low-stock' : ($bike['stock'] <=5 ? 'medium-stock' : 'high-stock');
            ?>
            <tr class="<?= $class ?>">
                <td><?= $bike['id'] ?></td>
                <td><?= htmlspecialchars($bike['name']) ?></td>
                <td><?= htmlspecialchars($bike['model']) ?></td>
                <td><?= $bike['stock'] ?></td>
                <td>
                    <?php if($bike['stock'] <= 2): ?>
                        <button class="btn btn-warning btn-sm">Restock</button>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">No bikes found</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
