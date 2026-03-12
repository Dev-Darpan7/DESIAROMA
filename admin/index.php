<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location:admin_login.php");
    exit();
}

include '../config.php';

/* GET TOTAL REVENUE */
$rev_query = mysqli_query($conn,"SELECT SUM(total_amount) AS revenue FROM orders WHERE order_status='Completed'");
$rev_data = mysqli_fetch_assoc($rev_query);
$total_revenue = $rev_data['revenue'] ?? 0;

/* GET TOTAL ORDERS */
$orders_query = mysqli_query($conn,"SELECT COUNT(id) AS total_orders FROM orders");
$orders_data = mysqli_fetch_assoc($orders_query);
$total_orders = $orders_data['total_orders'] ?? 0;

/* GET TOTAL CUSTOMERS */
$users_query = mysqli_query($conn,"SELECT COUNT(id) AS total_users FROM users");
$users_data = mysqli_fetch_assoc($users_query);
$total_users = $users_data['total_users'] ?? 0;

/* --- DATA FOR CHARTS --- */

// 1. Order Status Pie Chart
$status_counts = ['Pending' => 0, 'Completed' => 0, 'Cancelled' => 0, 'Confirmed' => 0];
$status_q = mysqli_query($conn, "SELECT order_status, COUNT(*) as count FROM orders GROUP BY order_status");
while($row = mysqli_fetch_assoc($status_q)) {
    $status = trim($row['order_status']);
    if (array_key_exists($status, $status_counts)) {
        $status_counts[$status] = $row['count'];
    } else {
        $status_counts[$status] = $row['count'];
    }
}

// Ensure base keys exist for the chart to prevent JS/PHP syntax errors
$pending_count = $status_counts['Pending'] ?? 0;
// We'll group Completed and Confirmed for the success metric
$completed_count = ($status_counts['Completed'] ?? 0) + ($status_counts['Confirmed'] ?? 0); 
$cancelled_count = $status_counts['Cancelled'] ?? 0;


// 2. Daily Sales Bar Chart (Last 7 Days)
$sales_dates = [];
$sales_amounts = [];
// Generate last 7 days starting from today backwards
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $sales_dates[] = date('D', strtotime($date)); // e.g. Mon, Tue
    
    // Get sales for that specific day (inclusive of Confirmed)
    $day_sales_q = mysqli_query($conn, "SELECT SUM(total_amount) as daily_total FROM orders WHERE DATE(order_date) = '$date' AND (order_status='Completed' OR order_status='Confirmed')");
    $day_sales_res = mysqli_fetch_assoc($day_sales_q);
    $sales_amounts[] = $day_sales_res['daily_total'] ? (float)$day_sales_res['daily_total'] : 0.0;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - DESIAROMA</title>
    <link rel="stylesheet" href="admin_style.css">
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #111;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid rgba(255,215,0,0.3);
            text-align: center;
        }
        .stat-card h3 {
            color: #aaa;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .stat-card .value {
            color: #ffd000;
            font-size: 32px;
            font-weight: bold;
        }
        .charts-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }
        .chart-box {
            background: #111;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid rgba(255,215,0,0.3);
        }
        .chart-box h3 {
            color: #ffd000;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="admin-header">
    <div class="admin-logo">DESIAROMA | Admin Panel</div>
    <div>
        <a href="index.php">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="add_product.php">Add Product</a>
        <a href="admin_orders.php">Orders</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="admin-container" style="max-width:1200px; margin:auto; margin-top:80px; padding:40px 20px;">

    <!-- TOP KPI METRICS -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <h3>Total Revenue</h3>
            <div class="value">₹<?php echo number_format($total_revenue,2); ?></div>
            <p style="font-size:12px; color:#555; margin-top:5px;">From completed orders</p>
        </div>
        <div class="stat-card">
            <h3>Total Orders</h3>
            <div class="value"><?php echo $total_orders; ?></div>
            <p style="font-size:12px; color:#555; margin-top:5px;">All time</p>
        </div>
        <div class="stat-card">
            <h3>Registered Customers</h3>
            <div class="value"><?php echo $total_users; ?></div>
            <p style="font-size:12px; color:#555; margin-top:5px;">Active accounts</p>
        </div>
    </div>

    <!-- CHARTS SECTION -->
    <div class="charts-container">
        
        <!-- Bar Chart (Sales) -->
        <div class="chart-box">
            <h3>Weekly Revenue (Last 7 Days)</h3>
            <div style="position: relative; height:250px; width:100%;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Pie Chart (Order Status) -->
        <div class="chart-box">
            <h3>Order Status</h3>
            <div style="position: relative; height:250px; width:100%;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

    </div>

</div>

<!-- INITIALIZE CHARTS -->
<script>
    // Global Chart Settings for Dark Theme
    Chart.defaults.color = '#ccc';
    Chart.defaults.borderColor = 'rgba(255,255,255,0.1)';

    // 1. Sales Bar Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($sales_dates); ?>,
            datasets: [{
                label: 'Revenue (₹)',
                data: <?php echo json_encode($sales_amounts); ?>,
                backgroundColor: 'rgba(255, 208, 0, 0.8)',
                borderColor: '#ffd000',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // 2. Order Status Doughnut Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Completed', 'Cancelled'],
            datasets: [{
                data: [
                    <?php echo $status_counts['Pending']; ?>, 
                    <?php echo $status_counts['Completed']; ?>, 
                    <?php echo $status_counts['Cancelled']; ?>
                ],
                backgroundColor: [
                    '#ff9800', // Warning/Orange
                    '#00ff88', // Success/Green
                    '#ff3b3b'  // Error/Red
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>

</body>
</html>