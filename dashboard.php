<?php 
require_once 'includes/header.php'; 

// =======================
// DASHBOARD STATISTICS
// =======================
$sql = "SELECT * FROM product WHERE status = 1";
$query = $connect->query($sql);
$countProduct = $query->num_rows;

$orderSql = "SELECT * FROM orders WHERE order_status = 1";
$orderQuery = $connect->query($orderSql);
$countOrder = $orderQuery->num_rows;

$totalRevenue = 0;
while ($orderResult = $orderQuery->fetch_assoc()) {
    $totalRevenue += $orderResult['paid'];
}

$lowStockSql = "SELECT * FROM product WHERE quantity <= 3 AND status = 1";
$lowStockQuery = $connect->query($lowStockSql);
$countLowStock = $lowStockQuery->num_rows;

$userwisesql = "
    SELECT users.username, SUM(orders.grand_total) AS totalorder
    FROM orders
    INNER JOIN users ON orders.user_id = users.user_id
    WHERE orders.order_status = 1
    GROUP BY orders.user_id
";
$userwiseQuery = $connect->query($userwisesql);
?>

<!-- =======================
     DASHBOARD STYLES
======================= -->
<style>
:root {
    --primary: #6f42c1;
    --success: #28a745;
    --danger: #dc3545;
    --info: #17a2b8;
    --warning: #fd7e14;
    --dark: #343a40;
}

body {
    background: #f4f6f9;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

/* Header */
.dashboard-header {
    margin-bottom: 30px;
}

.dashboard-header h2 {
    font-weight: 600;
    margin-bottom: 5px;
}

.dashboard-header small {
    color: #6c757d;
}

/* Cards */
.dashboard-card {
    background: #fff;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.dashboard-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 45px rgba(0,0,0,0.12);
}

.dashboard-card h4 {
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 10px;
}

.dashboard-card h2 {
    font-size: 34px;
    font-weight: 700;
    margin: 0;
}

.dashboard-card i {
    position: absolute;
    right: 20px;
    bottom: 20px;
    font-size: 48px;
    opacity: 0.15;
}

/* Card borders */
.card-success { border-left: 6px solid var(--success); }
.card-danger  { border-left: 6px solid var(--danger); }
.card-info    { border-left: 6px solid var(--info); }
.card-warning { border-left: 6px solid var(--warning); }

/* Date Card */
.date-card {
    background: linear-gradient(135deg, var(--primary), #8e44ad);
    color: #fff;
    text-align: center;
}

.date-card h1 {
    font-size: 56px;
    margin: 0;
}

.date-card p {
    margin: 0;
    font-size: 16px;
    opacity: 0.9;
}

/* Table */
.table thead {
    background: var(--dark);
    color: #fff;
}

.table td, .table th {
    vertical-align: middle;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-card h2 {
        font-size: 26px;
    }
}
</style>

<!-- =======================
     DASHBOARD CONTENT
======================= -->
<div class="container-fluid">

    <!-- Header -->
    <div class="dashboard-header">
        <h2>Dashboard</h2>
        <small>Quick overview of system activity</small>
    </div>

    <div class="row g-4">

        <?php if(isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
        <div class="col-xl-3 col-md-6">
            <a href="product.php" class="text-decoration-none text-dark">
                <div class="dashboard-card card-success">
                    <h4>Total Products</h4>
                    <h2><?php echo $countProduct; ?></h2>
                    <i class="glyphicon glyphicon-th-large"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6">
            <a href="product.php" class="text-decoration-none text-dark">
                <div class="dashboard-card card-danger">
                    <h4>Low Stock Items</h4>
                    <h2><?php echo $countLowStock; ?></h2>
                    <i class="glyphicon glyphicon-warning-sign"></i>
                </div>
            </a>
        </div>
        <?php } ?>

        <div class="col-xl-3 col-md-6">
            <a href="orders.php?o=manord" class="text-decoration-none text-dark">
                <div class="dashboard-card card-info">
                    <h4>Total Orders</h4>
                    <h2><?php echo $countOrder; ?></h2>
                    <i class="glyphicon glyphicon-shopping-cart"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-warning">
                <h4>Total Revenue</h4>
                <h2>₹ <?php echo $totalRevenue ?: 0; ?></h2>
                <i class="glyphicon glyphicon-stats"></i>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="dashboard-card date-card">
                <h1><?php echo date('d'); ?></h1>
                <p><?php echo date('l, F Y'); ?></p>
            </div>
        </div>

        <?php if(isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
        <div class="col-xl-8 col-md-12">
            <div class="dashboard-card">
                <h4>User Wise Orders</h4>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Total Orders (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($row = $userwiseQuery->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['totalorder']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php } ?>

    </div>
</div>

<!-- =======================
     SCRIPTS
======================= -->
<script>
$(function () {
    $('#navDashboard').addClass('active');
});
</script>

<?php 
$connect->close();
require_once 'includes/footer.php'; 
?>
