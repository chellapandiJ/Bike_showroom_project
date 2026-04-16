<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['admin']);

// 1. TOP SUMMARY CARDS
// Today's Sales
$today = date('Y-m-d');
$today_q = $conn->query("SELECT COUNT(*) as count, SUM(total_amount) as revenue FROM orders WHERE DATE(order_date) = '$today' AND order_status != 'Cancelled'");
$today_data = $today_q->fetch_assoc();

// Monthly Sales
$current_month = date('Y-m');
$month_q = $conn->query("SELECT COUNT(*) as count, SUM(total_amount) as revenue FROM orders WHERE DATE_FORMAT(order_date, '%Y-%m') = '$current_month' AND order_status != 'Cancelled'");
$month_data = $month_q->fetch_assoc();

// 2. SALES CHART DATA (Bar)
$days = isset($_GET['range']) ? intval($_GET['range']) : 7;
$daily_sales = [];
$labels = [];
for ($i = $days - 1; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $res = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE DATE(order_date) = '$date' AND order_status != 'Cancelled'");
    $row = $res->fetch_assoc();
    $daily_sales[] = $row['total'] ?? 0;
    
    // Label Format
    if ($days > 30) {
        $labels[] = date('d M', strtotime($date));
    } else {
        $labels[] = date('D d', strtotime($date));
    }
}

// 3. BRAND PERFORMANCE DATA (Doughnut)
$brand_q = $conn->query("SELECT br.brand_name, COUNT(o.order_id) as count 
                         FROM orders o 
                         JOIN bikes b ON o.bike_id = b.bike_id 
                         JOIN brands br ON b.brand_id = br.brand_id 
                         WHERE o.order_status != 'Cancelled' 
                         GROUP BY br.brand_name");
$brand_labels = [];
$brand_counts = [];
while($b = $brand_q->fetch_assoc()) {
    $brand_labels[] = $b['brand_name'];
    $brand_counts[] = $b['count'];
}

// 4. STAFF PERFORMANCE
$staff_perf = $conn->query("SELECT u.name, COUNT(o.order_id) as orders, SUM(o.total_amount) as revenue 
                            FROM users u 
                            LEFT JOIN orders o ON u.user_id = o.staff_id AND o.order_status != 'Cancelled' 
                            WHERE u.role = 'staff' 
                            GROUP BY u.user_id 
                            ORDER BY orders DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Fix layout jitter */
        body { overflow-x: hidden; }
        .chart-container { position: relative; height: 350px; width: 100%; }
        /* Smooth dashboard cards */
        .stat-card { transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-3px); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 fw-bold">Analytics & Reports</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                </div>
            </div>
            
            <!-- Summary Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card shadow-sm border-start border-4 border-primary h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase small">Today's Revenue</h6>
                            <h3 class="fw-bold text-primary mb-0"><?php echo formatCurrency($today_data['revenue'] ?? 0); ?></h3>
                            <small class="text-success fw-bold"><?php echo $today_data['count']; ?> Orders</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card shadow-sm border-start border-4 border-success h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase small">This Month's Revenue</h6>
                            <h3 class="fw-bold text-success mb-0"><?php echo formatCurrency($month_data['revenue'] ?? 0); ?></h3>
                            <small class="text-success fw-bold"><?php echo $month_data['count']; ?> Orders</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <form method="GET" class="mb-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <label class="col-form-label fw-bold">Chart Range:</label>
                    </div>
                    <div class="col-auto">
                        <select name="range" class="form-select" onchange="this.form.submit()">
                            <option value="7" <?php echo (isset($_GET['range']) && $_GET['range'] == '7') ? 'selected' : ''; ?>>Last 7 Days</option>
                            <option value="30" <?php echo (isset($_GET['range']) && $_GET['range'] == '30') ? 'selected' : ''; ?>>Last 30 Days</option>
                            <option value="90" <?php echo (isset($_GET['range']) && $_GET['range'] == '90') ? 'selected' : ''; ?>>Last 3 Months</option>
                        </select>
                    </div>
                </div>
            </form>

            <!-- Charts Row -->
            <div class="row mb-5">
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white fw-bold">Revenue Trend</div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white fw-bold">Sales by Brand</div>
                        <div class="card-body">
                            <div class="chart-container" style="height: 250px;">
                                <canvas id="brandChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Staff Performance -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white fw-bold">Staff Performance</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Staff Name</th>
                                            <th class="text-center">Orders</th>
                                            <th class="text-end">Revenue Generated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if($staff_perf->num_rows > 0):
                                            while($st = $staff_perf->fetch_assoc()): 
                                        ?>
                                        <tr>
                                            <td><i class="fas fa-user-tie text-muted me-2"></i> <?php echo htmlspecialchars($st['name']); ?></td>
                                            <td class="text-center"><span class="badge bg-secondary rounded-pill"><?php echo $st['orders']; ?></span></td>
                                            <td class="text-end text-success fw-bold"><?php echo formatCurrency($st['revenue']); ?></td>
                                        </tr>
                                        <?php endwhile; else: ?>
                                            <tr><td colspan="3" class="text-center text-muted">No staff activity data.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Breakdown Table -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white fw-bold">Monthly Breakdown</div>
                        <div class="card-body p-0">
                             <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Month</th>
                                        <th class="text-center">Orders</th>
                                        <th class="text-end">Total Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $monthly = $conn->query("SELECT DATE_FORMAT(order_date, '%Y-%m') as month, COUNT(*) as count, SUM(total_amount) as revenue 
                                                             FROM orders WHERE order_status != 'Cancelled' 
                                                             GROUP BY month ORDER BY month DESC LIMIT 6");
                                    if($monthly->num_rows > 0):
                                        while($m = $monthly->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?php echo date('F Y', strtotime($m['month'] . '-01')); ?></td>
                                        <td class="text-center"><?php echo $m['count']; ?></td>
                                        <td class="text-end"><?php echo formatCurrency($m['revenue']); ?></td>
                                    </tr>
                                    <?php endwhile; else: ?>
                                        <tr><td colspan="3" class="text-center text-muted">No sales data found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                             </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
// Sales Bar Chart
const ctxSales = document.getElementById('salesChart').getContext('2d');
new Chart(ctxSales, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Revenue (₹)',
            data: <?php echo json_encode($daily_sales); ?>,
            backgroundColor: 'rgba(59, 130, 246, 0.6)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { 
                beginAtZero: true,
                grid: { borderDash: [2, 2] } 
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Brand Doughnut Chart
const ctxBrand = document.getElementById('brandChart').getContext('2d');
new Chart(ctxBrand, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($brand_labels); ?>,
        datasets: [{
            data: <?php echo json_encode($brand_counts); ?>,
            backgroundColor: [
                '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'
            ],
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'right' }
        }
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
