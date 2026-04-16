<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';

// Stats for Reports
$dailySales = $conn->query("SELECT DATE(order_date) as date, COUNT(*) as count, SUM(booking_amount) as total FROM orders GROUP BY DATE(order_date) ORDER BY date DESC LIMIT 7");
?>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">SALES REPORTS</h2>
    
    <div class="card bg-dark text-white p-4">
        <h4>Daily Sales (Last 7 Days)</h4>
        <table class="table table-dark mt-3">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Orders</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $dailySales->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['count']; ?></td>
                    <td>$<?php echo number_format($row['total']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-center">
        <button class="btn btn-venum" onclick="window.print()">Print Report</button>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
