<?php ob_start(); ?>
<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

// Kiem tra dang nhap va quyen admin
if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_SESSION['user_logged_in'])) {
        session_destroy();
        header('Location: ../store.php');
        exit;
    } else {
        header('Location: login.php');
        exit;
    }
}
$section = $_GET['section'] ?? 'dashboard';
// Lay thong tin admin
$admin_id = $_SESSION['admin_id'];
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Xu ly dang xuat
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Lay thong ke
$products_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$orders_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$posts_count = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - ATAIT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 60px;
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(180deg, #4e73df 0%, #224abe 100%);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }

        .main-content {
            margin-left: 250px;
            min-height: calc(100vh - 60px);
            padding: 20px;
            transition: all 0.3s;
        }

        .topbar {
            height: 60px;
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            z-index: 999;
        }

        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            font-weight: 600;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .stat-card {
            border-left: 0.25rem solid #4e73df;
        }

        .stat-card .text-primary {
            color: #4e73df !important;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #858796;
        }

        .table th {
            font-weight: 600;
            background-color: #f8f9fc;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35em 0.65em;
        }

        .badge-primary {
            background-color: #4e73df;
        }

        .badge-success {
            background-color: #1cc88a;
        }

        .badge-info {
            background-color: #36b9cc;
        }

        .badge-warning {
            background-color: #f6c23e;
        }

        .badge-danger {
            background-color: #e74a3b;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #6e707e;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            color: #858796;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.35rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, 
                        border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .btn-primary {
            color: #fff;
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }

        .btn-secondary {
            color: #fff;
            background-color: #858796;
            border-color: #858796;
        }

        .btn-danger {
            color: #fff;
            background-color: #e74a3b;
            border-color: #e74a3b;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
        }


        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .topbar {
                left: 0;
            }
            
            .sidebar-toggled .sidebar {
                width: 250px;
            }
            
            .sidebar-toggled .main-content {
                margin-left: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <!-- Sidebar -->
<div class="sidebar">
    <div class="text-center py-4">
        <h4>ATAIT ADMIN</h4>
    </div>
    <nav class="mt-4">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($section === 'dashboard') ? 'active' : ''; ?>" href="admin.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($section === 'home') ? 'active' : ''; ?>" href="admin.php?section=home">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Home Page</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($section === 'posts') ? 'active' : ''; ?>" href="admin.php?section=posts">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Posts Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($section === 'products') ? 'active' : ''; ?>" href="admin.php?section=products">
                    <i class="fas fa-fw fa-box-open"></i>
                    <span>Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($section === 'orders') ? 'active' : ''; ?>" href="admin.php?section=orders">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($section === 'users') ? 'active' : ''; ?>" href="admin.php?section=users">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin.php?logout=1">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

    <!-- Topbar -->
    <nav class="navbar navbar-expand topbar mb-4 static-top shadow">
        <div class="container-fluid">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Nut back Dashboard -->
        <a href="admin.php" class="btn btn-circle btn-light mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        
        
        <div class="d-flex align-items-center ml-3">
            <span class="text-gray-800" style="font-weight: 800;">
                <?php 
                $pageTitles = [
                    'dashboard' => 'Dashboard',
                    'home' => 'Home Page',
                    'posts' => 'Posts Management',
                    'products' => 'Product Management',
                    'users' => 'User Management',
                    'orders' => 'Order Management',
                ];
                $currentSection = $_GET['section'] ?? 'dashboard';
                echo $pageTitles[$currentSection] ?? 'Dashboard';
                ?>
            </span>
        </div>
            
            <ul class="navbar-nav ml-auto">
                        <img class="img-profile rounded-circle" src="../uploads/logo.png" width="40">
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <?php
        $section = $_GET['section'] ?? 'dashboard';
        
        switch ($section) {
            case 'dashboard':
                include 'views/dashboard.php';
                break;
            case 'home':
                $action = $_GET['action'] ?? 'banners';
                if ($action === 'edit_about') {
                    include 'views/about/edit.php';
                } elseif ($action === 'edit_banner' && isset($_GET['id'])) {
                    include 'views/banners/edit.php';
                } else {
                    include 'views/banners/index.php';
                }
                break;
            case 'posts':
    $action = $_GET['action'] ?? 'list';
    if ($action === 'create') {
        include 'views/posts/create.php';
    } elseif ($action === 'edit' && isset($_GET['id'])) {
        include 'views/posts/edit.php';
    } else {
        include 'views/posts/index.php';
    }
    break;
            case 'products':
                $action = $_GET['action'] ?? 'list';
                if ($action === 'create') {
                    include 'views/products/create.php';
                } elseif ($action === 'edit' && isset($_GET['id'])) {
                    include 'views/products/edit.php';
                } else {
                    include 'views/products/index.php';
                }
                break;
            case 'orders':
                
                $action = $_GET['action'] ?? 'list';
                if ($action === 'view' && isset($_GET['id'])) {
                    include 'views/orders/view.php';
                } else {
                    include 'views/orders/index.php';
                }
                break;
            case 'users':
                $action = $_GET['action'] ?? 'list';
                if ($action === 'view' && isset($_GET['id'])) {
                    include 'views/users/view.php';
                } else {
                    include 'views/users/index.php';
                }
                break;
            default:
                include 'views/dashboard.php';
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.getElementById('sidebarToggleTop').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-toggled');
            document.querySelector('.sidebar').classList.toggle('toggled');
        });

        if (typeof CKEDITOR !== 'undefined') {
            document.querySelectorAll('textarea.editor').forEach(function(textarea) {
                CKEDITOR.replace(textarea);
            });
        }

        document.querySelectorAll('[data-confirm]').forEach(function(element) {
            element.addEventListener('click', function(e) {
                if (!confirm(this.getAttribute('data-confirm'))) {
                    e.preventDefault();
                }
            });
        });


        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });


        function initCharts() {
            // Sales Chart
            var ctx = document.getElementById('salesChart');
            if (ctx) {
                var salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Sales',
                            data: [12000, 19000, 15000, 22000, 25000, 28000, 32000, 29000, 26000, 30000, 35000, 40000],
                            backgroundColor: 'rgba(78, 115, 223, 0.05)',
                            borderColor: 'rgba(78, 115, 223, 1)',
                            pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgba(78, 115, 223, 1)'
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Top Products Chart
            var ctx2 = document.getElementById('topProductsChart');
            if (ctx2) {
                var topProductsChart = new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: JSON.parse(ctx2.dataset.labels || '[]'),
                        datasets: [{
                            data: JSON.parse(ctx2.dataset.data || '[]'),
                            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', initCharts);
    </script>
</body>
</html>
<?php ob_end_flush(); ?>
