<?php
require_once '../config/db.php';
session_start();

// Kiem tra dang nhap
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: store.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $paymentMethod = $_POST['payment_method'];
    $notes = $_POST['notes'] ?? '';
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    if (empty($cart)) {
        if (isset($_COOKIE['storeProduct'])) {
            $cart = json_decode($_COOKIE['storeProduct'], true);
        } else {
            $_SESSION['checkout_error'] = 'Your cart is empty';
            header('Location: store.php');
            exit;
        }
    }

    $totalAmount = 0;
    foreach ($cart as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }
    
    try {
        $pdo->beginTransaction();
        $orderStmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, payment_method, shipping_address, notes) 
                                   VALUES (?, ?, 'pending', ?, ?, ?)");
        $orderStmt->execute([
            $_SESSION['user_id'],
            $totalAmount,
            $paymentMethod,
            $address,
            $notes
        ]);
        $orderId = $pdo->lastInsertId();
        foreach ($cart as $item) {
            $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) 
                                      VALUES (?, ?, ?, ?, ?)");
            $itemStmt->execute([
                $orderId,
                $item['id'],
                $item['name'],
                $item['price'],
                $item['quantity']
            ]);

            // Giảm số lượng sản phẩm trong kho
            $updateStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $updateStmt->execute([
                $item['quantity'],
                $item['id']
            ]);
            
            // Kiểm tra nếu số lượng xuống dưới 0 thì rollback
            $checkStmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
            $checkStmt->execute([$item['id']]);
            $currentStock = $checkStmt->fetchColumn();
            
            if ($currentStock < 0) {
                throw new Exception("Not enough stock for product ID: " . $item['id']);
            }
        }
        

        unset($_SESSION['cart']);
        setcookie('storeProduct', '', time() - 3600, '/');
    
        $updateCartStmt = $pdo->prepare("UPDATE users SET cart = NULL WHERE id = ?");
        $updateCartStmt->execute([$_SESSION['user_id']]);
        
      
        $pdo->commit();
        header("Location: order_success.php?order_id=$orderId");
        exit;
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['checkout_error'] = 'Checkout failed: ' . $e->getMessage();
        header('Location: checkout.php');
        exit;
    }
}


$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cartItems)) {
    if (isset($_COOKIE['storeProduct'])) {
        $cartItems = json_decode($_COOKIE['storeProduct'], true);
        $_SESSION['cart'] = $cartItems;
    } else {
        $_SESSION['checkout_error'] = 'Your cart is empty';
        header('Location: store.php');
        exit;
    }
}

$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shippingFee = 30000; 
$total = $subtotal + $shippingFee;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Atait</title>
    <link rel="icon" type="image/x-icon" href="../uploads/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/store.css">
    <style>
        .checkout-container {
            display: flex;
            gap: 30px;
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .checkout-section {
            flex: 1;
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .checkout-section h2 {
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input, 
        .form-group textarea, 
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .payment-methods {
            margin-top: 20px;
        }
        
        .payment-method {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .payment-method:hover {
            border-color: #4CAF50;
        }
        
        .payment-method input {
            margin-right: 15px;
        }
        
        .payment-method-content {
            display: flex;
            align-items: center;
        }
        
        .payment-method-content i {
            font-size: 24px;
            margin-right: 15px;
            color: #555;
        }
        
        .order-summary {
            margin-top: 30px;
        }
        
        .order-summary-item {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .order-summary-item.total {
            font-weight: bold;
            font-size: 18px;
        }
        
        .checkout-btn {
            width: 100%;
            padding: 15px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.3s;
        }
        
        .checkout-btn:hover {
            background: #45a049;
        }
        
        .cart-items {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        
        .cart-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .cart-item-img {
            width: 80px;
            height: 80px;
            margin-right: 15px;
        }
        
        .cart-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .cart-item-details {
            flex: 1;
        }
        
        .cart-item-name {
            margin: 0 0 5px 0;
            font-size: 16px;
        }
        
        .cart-item-price {
            color: #4CAF50;
            font-weight: 500;
        }
        
        .cart-item-quantity {
            color: #777;
        }
        
        @media (max-width: 768px) {
            .checkout-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <main class="checkout-page">
        <div class="checkout-container">
            <div class="checkout-section">
                <h2>Shipping Information</h2>
                
                <?php if (isset($_SESSION['checkout_error'])): ?>
                    <div class="alert alert-error">
                        <?= $_SESSION['checkout_error'] ?>
                        <?php unset($_SESSION['checkout_error']); ?>
                    </div>
                <?php endif; ?>
                
                <form action="checkout.php" method="post">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" pattern="[0-9]{10,11}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Shipping Address</label>
                        <textarea id="address" name="address" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Order Notes (Optional)</label>
                        <textarea id="notes" name="notes" rows="2" placeholder="Notes about your order, e.g. special delivery instructions"></textarea>
                    </div>
                    
                    <h2>Payment Method</h2>
                    <div class="payment-methods">
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <div class="payment-method-content">
                                <i class="fas fa-money-bill-wave"></i>
                                <div>
                                    <h4>Cash on Delivery</h4>
                                    <p>Pay when you receive the order</p>
                                </div>
                            </div>
                        </label>
                        
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="bank_transfer">
                            <div class="payment-method-content">
                                <i class="fas fa-university"></i>
                                <div>
                                    <h4>Bank Transfer</h4>
                                    <p>Transfer money directly to our bank account</p>
                                </div>
                            </div>
                        </label>
                        
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="credit_card">
                            <div class="payment-method-content">
                                <i class="far fa-credit-card"></i>
                                <div>
                                    <h4>Credit/Debit Card</h4>
                                    <p>Pay with Visa, MasterCard, or JCB</p>
                                </div>
                            </div>
                        </label>
                        
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="momo">
                            <div class="payment-method-content">
                                <i class="fas fa-mobile-alt"></i>
                                <div>
                                    <h4>MoMo eWallet</h4>
                                    <p>Fast and secure payment with MoMo</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    <a href="order_success.php"><button type="submit" name="checkout" class="checkout-btn">Complete Order</button></a>
                </form>
            </div>
            
            <div class="checkout-section">
                <h2>Your Order</h2>
                
                <div class="cart-items">
                    <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <div class="cart-item-img">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        </div>
                        <div class="cart-item-details">
                            <h4 class="cart-item-name"><?= htmlspecialchars($item['name']) ?></h4>
                            <div class="cart-item-price">
                                <?= number_format($item['price'], 0, ',', '.') ?>đ
                            </div>
                            <div class="cart-item-quantity">
                                Quantity: <?= $item['quantity'] ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-summary">
                    <h2>Order Summary</h2>
                    
                    <div class="order-summary-item">
                        <span>Subtotal</span>
                        <span><?= number_format($subtotal, 0, ',', '.') ?>đ</span>
                    </div>
                    
                    <div class="order-summary-item">
                        <span>Shipping</span>
                        <span><?= number_format($shippingFee, 0, ',', '.') ?>đ</span>
                    </div>
                    
                    <div class="order-summary-item total">
                        <span>Total</span>
                        <span><?= number_format($total, 0, ',', '.') ?>đ</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
     <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <div class="social-icons">
                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                </div>
                <p>ATAIT - Delivering the Best Information Technology Solutions for You.</p>
            </div>
            <div class="footer-column">
                <h3>Customer Service</h3>
                <ul>
                    <li><a href="#">Ordering Guide</a></li>
                    <li><a href="#">Payment & Delivery</a></li>
                    <li><a href="#">Warranty & Return Policy</a></li>
                    <li><a href="#">Installment Purchase Policy</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Information </h3>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Terms of Use</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Contact</h3>
                <ul class="contact-info">
                    <li><i class="fa-solid fa-phone"></i> Hotline: 19001234 - 0989 123 456</li>
                    <li><i class="fa-solid fa-headset"></i> Feedback: 0906 123 456</li>
                    <li><i class="fa-solid fa-envelope"></i> Email: atait@gmail.com</li>
                    <li><i class="fa-solid fa-location-dot"></i> Address: 410 Le Hong Phong Street, Ward 1, District 10, Ho Chi Minh City</li>
                </ul>
            </div>
        </div>
        <div class="bottom-footer">
            Copyright 2025 © ATAIT | ATAIT Co., Ltd. - MST: 0316874491
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isLoggedIn = <?= isset($_SESSION['user_logged_in']) ? 'true' : 'false' ?>;
            if (!isLoggedIn) {
                alert('Please login to proceed to checkout');
                window.location.href = 'store.php';
            }
            
            const cartCookie = document.cookie.split('; ').find(row => row.startsWith('storeProduct='));
            if (cartCookie && <?= empty($cartItems) ? 'true' : 'false' ?>) {
                const cartData = JSON.parse(decodeURIComponent(cartCookie.split('=')[1]));
            }
        });
    </script>
</body>
</html>