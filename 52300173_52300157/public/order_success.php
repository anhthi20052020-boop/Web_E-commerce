<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_logged_in'])) {
    header('Location: store.php');
    exit;
}

$orderId = $_GET['order_id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: store.php');
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - Atait</title>
    <link rel="icon" type="image/x-icon" href="../uploads/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/store.css">
</head>
<style>

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
    }

    /* Main container */
    .order-success {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .success-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 40px;
        max-width: 600px;
        width: 100%;
        text-align: center;
        animation: fadeIn 0.5s ease-in-out;
    }

    /* Icon */
    .fa-check-circle {
        font-size: 5rem;
        color: #28a745;
        margin-bottom: 20px;
        animation: bounce 1s;
    }

    /* Headings */
    h1 {
        color: #28a745;
        margin-bottom: 15px;
        font-size: 2rem;
    }

    h2 {
        color: #495057;
        margin: 25px 0 15px;
        font-size: 1.5rem;
        text-align: left;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    /* Order summary */
    .order-summary {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin: 25px 0;
        text-align: left;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding: 8px 0;
        border-bottom: 1px dashed #ddd;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-item span:first-child {
        font-weight: 600;
        color: #495057;
    }

    .summary-item span:last-child {
        color: #212529;
    }

    /* Button */
    .btn-continue {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 12px 25px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-top: 20px;
        border: none;
        cursor: pointer;
    }

    .btn-continue:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-20px);
        }
        60% {
            transform: translateY(-10px);
        }
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .success-container {
            padding: 30px 20px;
        }
        
        h1 {
            font-size: 1.8rem;
        }
        
        h2 {
            font-size: 1.3rem;
        }
        
        .fa-check-circle {
            font-size: 4rem;
        }
    }
</style>
<body>
    
    
    <main class="order-success">
        <div class="success-container">
            <i class="fas fa-check-circle"></i>
            <h1>Thank You for Your Order!</h1>
            <p>Your order #<?= $orderId ?> has been placed successfully.</p>
            
            <div class="order-summary">
                <h2>Order Summary</h2>
                <div class="summary-item">
                    <span>Order Number:</span>
                    <span>#<?= $orderId ?></span>
                </div>
                <div class="summary-item">
                    <span>Date:</span>
                    <span><?= date('F j, Y', strtotime($order['created_at'])) ?></span>
                </div>
                <div class="summary-item">
                    <span>Total:</span>
                    <span><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</span>
                </div>
                <div class="summary-item">
                    <span>Payment Method:</span>
                    <span>
                        <?= ucfirst(str_replace('_', ' ', $order['payment_method'])) ?>
                    </span>
                </div>
            </div>
            
            <a href="store.php" class="btn-continue">Continue Shopping</a>
        </div>
    </main>
    
</body>
</html>