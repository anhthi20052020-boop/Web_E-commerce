<?php
require_once '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    try {
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $checkStmt->execute([$username, $email]);
        
        if ($checkStmt->rowCount() > 0) {
            $_SESSION['register_error'] = 'Username or email already exists';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $createdAt = date('Y-m-d H:i:s');
            
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashedPassword, $createdAt]);
            
            $userId = $pdo->lastInsertId();
            
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            
            $_SESSION['register_success'] = 'Registration successful!';
        }
    } catch (PDOException $e) {
        $_SESSION['register_error'] = 'Registration failed: ' . $e->getMessage();
    }
    
    header('Location: product.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['login_success'] = 'Admin login successful!';
            header('Location: ../admin/admin.php');
            exit;
        }
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            if (!empty($user['cart'])) {
                $_SESSION['cart'] = json_decode($user['cart'], true);
                echo '<script>localStorage.setItem("storeProduct", \''.$user['cart'].'\');</script>';
            }
            
            $_SESSION['login_success'] = 'Login successful!';
        } else {
            $_SESSION['login_error'] = 'Invalid username or password';
        }
    } catch (PDOException $e) {
        $_SESSION['login_error'] = 'Login failed: ' . $e->getMessage();
    }
    
    header('Location: product.php');
    exit;
}

if (isset($_GET['logout'])) {
    if (isset($_SESSION['user_logged_in'])) {
        $cartData = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        echo '<script>
            const storeProduct = localStorage.getItem("storeProduct");
            if (storeProduct) {
                fetch("update_cart.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        userId: '.$_SESSION['user_id'].',
                        cartData: JSON.parse(storeProduct)
                    })
                });
            }
        </script>';
    }
    
    session_destroy();
    echo '<script>
        localStorage.removeItem("user_logged_in");
        localStorage.removeItem("user_id");
        localStorage.removeItem("username");
        localStorage.removeItem("storeProduct");
        window.location.href = "product.php";
    </script>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if ($newPassword !== $confirmPassword) {
        $_SESSION['change_pass_error'] = 'New passwords do not match';
        header('Location: product.php');
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($currentPassword, $user['password'])) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updateStmt->execute([$hashedPassword, $_SESSION['user_id']]);
            $_SESSION['change_pass_success'] = 'Password changed successfully!';
        } else {
            $_SESSION['change_pass_error'] = 'Current password is incorrect';
        }
    } catch (PDOException $e) {
        $_SESSION['change_pass_error'] = 'Password change failed: ' . $e->getMessage();
    }
    
    header('Location: product.php');
    exit;
}

$productId = $_GET['id'] ?? 0;
$product = [];
$relatedProducts = [];

if ($productId) {
    try {
        $stmt = $pdo->prepare("SELECT *, 
            CASE 
                WHEN images IS NULL OR images = '' THEN JSON_ARRAY(image)
                ELSE images
            END AS images
            FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();

        $images = json_decode($product['images'], true);
        if (empty($images) || json_last_error() !== JSON_ERROR_NONE) {
            $images = [$product['image']]; 
        }

        
        $stmt->execute([$productId]);
        $product = $stmt->fetch();

        if ($product) {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? LIMIT 6");
            $stmt->execute([$product['category'], $productId]);
            $relatedProducts = $stmt->fetchAll();
        }
    } catch (PDOException $e) {

    }
}

if (!$product) {
    header('Location: store.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Atait</title>
    <link rel="icon" type="image/x-icon" href="../uploads/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="assets/css/product.css">
</head>
<body data-user-logged-in="<?= isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] ? 'true' : 'false' ?>" 
      data-user-id="<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '' ?>">
    <header>
        <div class="overlay"></div>
        <nav class="navbar section-content">
            <a href="home.php" class="nav-logo">
                <h2 class="logo-text">Atait</h2>
            </a>
            <div class="search-box" id="searchBox">
                <form action="filter.php" method="get">
                    <input type="text" name="search" id="srch" placeholder="Search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <ul class="nav-menu">
                <button id="menu-close-button" class="fa fa-times"></button>

                <div class="mobile-search-box">
                    <form action="filter.php" method="get">
                        <input type="text" name="search" id="mobile-srch" placeholder="Search">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>

                <div class="mobile-tabs">
                    <button class="mobile-tab active" data-tab="home-tab">Home</button>
                    <button class="mobile-tab" data-tab="catolog-tab">Catalog</button>
                </div>
                <div id="home-tab" class="tab-content active">
                    <li class="nav-item">
                        <a href="home.php" class="nav-link">Home</a>
                        <a href="home.php" class="nav-link">About us</a>
                        <a href="blog.php" class="nav-link">Blog</a>
                        <a href="store.php" class="nav-link">Store</a>
                        <a href="contact.php" target="_blank" class="nav-link">Contact</a>
                    </li>
                </div>
                <li class="nav-item">
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']): ?>
                        <div class="user-dropdown">
                            <button class="user-dropbtn">
                                <span>Admin: <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
                                <i class="fas fa-caret-down"></i>
                            </button>
                            <div class="user-dropdown-content">
                                <a href="../admin/admin.php">Admin Panel</a>
                                <a href="product.php?logout=1">Logout</a>
                            </div>
                        </div>
                    <?php elseif (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']): ?>
                        <div class="user-dropdown">
                            <button class="user-dropbtn">
                                <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                                <i class="fas fa-caret-down"></i>
                            </button>
                            <div class="user-dropdown-content">
                                <a href="#" onclick="openChangePassForm()">Change Password</a>
                                <a href="product.php?logout=1">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <button onclick="openLoginForm()" class="btnLogin-popup">Login</button>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['login_success'])): ?>
                    <script>
                        localStorage.setItem('user_logged_in', 'true');
                        localStorage.setItem('user_id', '<?= $_SESSION['user_id'] ?>');
                        localStorage.setItem('username', '<?= $_SESSION['username'] ?>');
                    </script>
                    <?php unset($_SESSION['login_success']); ?>
                <?php endif; ?>
                </li>

                <div id="catolog-tab" class="tab-content">
                    <li class="nav-item">
                        <a href="filter.php?category=Smartphones" class="nav-link">
                            <img src="../uploads/smartphone.png" alt="Smartphones" class="menu-icon">
                            <span>Smartphones</span>
                        </a>
                        <a href="filter.php?category=Laptops" class="nav-link">
                            <img src="../uploads/laptop.png" alt="Laptops" class="menu-icon">
                            <span>Laptops</span>
                        </a>
                        <a href="filter.php?category=PCs" class="nav-link">
                            <img src="../uploads/computer.png" alt="PCs" class="menu-icon">
                            <span>PCs</span>
                        </a>
                        <a href="filter.php?category=Tablets" class="nav-link">
                            <img src="../uploads/tablet.png" alt="Tablets" class="menu-icon">
                            <span>Tablets</span>
                        </a>
                        <a href="filter.php?category=Accessories" class="nav-link">
                            <img src="../uploads/music.png" alt="Accessories" class="menu-icon">
                            <span>Accessories</span>
                        </a>
                        <a href="filter.php?category=Smartwatch" class="nav-link">
                            <img src="../uploads/smartwatch (1).png" alt="Smartwatch" class="menu-icon">
                            <span>Smartwatch</span>
                        </a>
                        <a href="filter.php?category=Wristwatch" class="nav-link">
                            <img src="../uploads/watch.png" alt="Wristwatch" class="menu-icon">
                            <span>Wristwatch</span> 
                        </a>
                        <a href="filter.php?category=Cameras" class="nav-link">
                            <img src="../uploads/camera.png" alt="Cameras" class="menu-icon">
                            <span>Cameras</span>
                        </a>
                        <a href="filter.php?category=Sim" class="nav-link">
                            <img src="../uploads/sim.png" alt="Sim" class="menu-icon">
                            <span>Sim</span>
                        </a>
                    </li>
                </div>
            </ul>

            <div class="cartbox">
                <span class="quantity">0</span>
                <i class="uil uil-shopping-cart"></i>
            </div>
            <button id="menu-open-button" class="fa fa-bars"></button>
        </nav>

        <nav class="navbar-bottom">
            <div class="section-content">
                <ul>
                    <li>
                        <a href="filter.php?category=Smartphones">
                            <img src="../uploads/smartphone.png" alt="Smartphones" class="nav-icon">
                            <span>Smartphones</span>
                        </a>
                    </li>
                    <li>
                        <a href="filter.php?category=Laptops">
                            <img src="../uploads/laptop.png" alt="Laptops" class="nav-icon">
                            <span>Laptops</span>
                        </a>
                    </li>
                    <li>
                        <a href="filter.php?category=PCs">
                            <img src="../uploads/computer.png" alt="PCs" class="nav-icon">
                            <span>PCs</span>
                        </a>
                    </li>
                    <li>
                        <a href="filter.php?category=Tablets">
                            <img src="../uploads/tablet.png" alt="Tablets" class="nav-icon">
                            <span>Tablets</span>
                        </a>
                    </li>
                    <li>
                        <a href="filter.php?category=Accessories">
                            <img src="../uploads/music.png" alt="Accessories" class="nav-icon">
                            <span>Accessories</span>                      
                        </a>
                    </li>
                    <li>
                        <a href="filter.php?category=Smartwatch">
                            <img src="../uploads/smartwatch (1).png" alt="Smartwatch" class="nav-icon">
                            <span>Smartwatch</span>                        
                        </a>
                    </li>
                    <li>
                        <a href="filter.php?category=Wristwatch">
                            <img src="../uploads/watch.png" alt="Wristwatch" class="nav-icon">
                            <span>Wristwatch</span>                        
                        </a>
                    </li>
                    <li>
                        <a href="filter.php?category=Cameras">
                            <img src="../uploads/camera.png" alt="Cameras" class="nav-icon">
                            <span>Cameras</span>                        
                        </a>
                    </li>
                    <li>
                        <a href="filter.php?category=Sim">
                            <img src="../uploads/sim.png" alt="Sim" class="nav-icon">
                            <span>Sim</span>                        
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Login Form -->
    <div id="overlei" class="overlei">
        <div id="id01" class="wrapper">
            <div class="form-box login">
                <form class="animate" action="product.php" method="post">
                    <h2>Login</h2>
                    <div class="btclose">
                        <span onclick="closeLoginForm()" class="close" title="Close">&times;</span>
                    </div>
                    
                    <?php if (isset($_SESSION['login_error'])): ?>
                        <div class="alert alert-error">
                            <?= $_SESSION['login_error'] ?>
                            <?php unset($_SESSION['login_error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="input-box">
                        <span class="icon"><ion-icon name="person"></ion-icon></span>
                        <input type="text" name="username" required>
                        <label>Username</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <div class="remember-forgot">
                        <label><input type="checkbox" name="remember">Remember me</label>
                        <a href="#" onclick="openSetPassForm(), closeLoginForm()">Forgot Password? </a>
                    </div>
                    <button type="submit" name="login" class="btn">Login</button>
                    <div class="login-register">
                        <p>Don't have an account? <a href="#" class="register-link" onclick="openRegisterForm(), closeLoginForm()">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Register Form -->
    <div id="overlei2" class="overlei">
        <div id="id02" class="wrapper2">
            <div class="form-box register">
                <form class="animate" action="product.php" method="post">
                    <h2>Register</h2>
                    <div class="btclose">
                        <span onclick="closeRegisterForm()" class="close" title="Close">&times;</span>
                    </div>
                    
                    <?php if (isset($_SESSION['register_error'])): ?>
                        <div class="alert alert-error">
                            <?= $_SESSION['register_error'] ?>
                            <?php unset($_SESSION['register_error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="input-box">
                        <span class="icon"><ion-icon name="mail-open"></ion-icon></span>
                        <input type="email" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="person"></ion-icon></span>
                        <input type="text" name="username" required>
                        <label>Username</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <div class="remember-forgot">
                        <label><input type="checkbox" required>I agree to the terms & conditions</label>
                    </div>
                    <button type="submit" name="register" class="btn">Register</button>
                    <div class="login-register">
                        <p>Already have an account? <a href="#" class="register-link" onclick="openLoginForm(), closeRegisterForm()">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Forgot Password Form -->
    <div id="overlei3" class="overlei">
        <div id="id03" class="wrapper3">
            <div class="form-box register">
                <form class="animate" action="action_page.php" method="post">
                    <h2>Set Password</h2>
                    <div class="btclose">
                        <span onclick="closeSetPassForm()" class="close" title="Close">&times;</span>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="mail-open"></ion-icon></span>
                        <input type="email" require>
                        <label>Email</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="shield-checkmark-outline"></ion-icon></span>
                        <input type="username" require>
                        <label>Verification code</label>
                    </div>
                    <div class="remember-forgot">
                        <label><input type="checkbox">I agree to the terms & conditions</label>
                    </div>
                    <button type="submit" class="btn">Login</button>
                    <button type="submit" class="btnn">Send code</button>
                    <div class="login-register">
                        <p>Didn't receive verification code? <a href="contact.php" class="register-link" onclick="closeRegisterForm()">Support</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <main>
        <div class="product-container">
            <div class="product-images">
                <div class="thumbnail-column">
                    <?php foreach ($images as $index => $image): ?>
                        <img src="<?= $image ?>" 
                            alt="<?= htmlspecialchars($product['name']) ?> - Image <?= $index+1 ?>"
                            class="thumbnail" 
                            onclick="changeMainImage(this)"
                            data-index="<?= $index ?>">
                    <?php endforeach; ?>
                </div>
                <div class="main-image-container">
                    <?php if ($product['is_hot']): ?>
                        <span class="hot-tag">HOT</span>
                    <?php endif; ?>
                    <img src="<?= $images[0] ?>" 
                        alt="<?= htmlspecialchars($product['name']) ?>" 
                        class="main-image" 
                        id="mainImage">
                </div>
            </div>
            
            <div class="product-details">
                <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
                <div class="stock-status"><?= $product['stock'] ?> in stock</div>
                <div class="current-price"><?= number_format($product['price'], 0, ',', '.') ?>₫</div>
                <?php if ($product['original_price'] > $product['price']): ?>
                    <span class="original-price"><?= number_format($product['original_price'], 0, ',', '.') ?>đ</span>
                <?php endif; ?>
                
                <div class="action-buttons">
                    <div class="quantity-controls">
                        <input type="number" id="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
                    </div>
                    <button class="btnnt btn-cart" onclick="addToCart(<?= $product['id'] ?>)">ADD TO CART</button>
                    <button class="btnnt btn-buy" onclick="buyNow(<?= $product['id'] ?>)">BUY NOW</button>
                </div>
                
                <div class="promotion">
                    <div class="promotion-header">Promotion</div>
                    <div class="promotion-item">
                        <span class="promotion-item-number">1</span>
                        Free shipping for orders over 25,000,000₫
                    </div>
                    <div class="promotion-item">
                        <span class="promotion-item-number">2</span>
                        Free Google One AI Premium 6 months (Gemini Advanced + 2TB storage)
                    </div>
                    <div class="promotion-item">
                        <span class="promotion-item-number">3</span>
                        Free accessories worth 500,000₫
                    </div>
                </div>
                
                <div class="checkout-banner">Secure payment guaranteed</div>
                
                <div class="payment-methods">
                    <div class="payment-method">
                        <img src="https://developers.momo.vn/v3/vi/assets/images/icon-52bd5808cecdb1970e1aeec3c31a3ee1.png" alt="momo">
                        <img src="https://cdn4.iconfinder.com/data/icons/payment-method/160/payment_method_card_visa-1024.png" alt="visa">
                        <img src="https://www.logo.wine/a/logo/Apple_Pay/Apple_Pay-Logo.wine.svg" alt="applepay">
                        <img src="https://cdn-icons-png.flaticon.com/512/196/196566.png" alt="paypal">
                        <img src="https://www.mastercard.com/content/dam/public/mastercardcom/vn/vi/logos/mastercard-og-image.png" alt="msc">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="product-description">
            <h2 class="description-heading">PRODUCT INFORMATION</h2>
            <p class="description-text">
                <?= $product['description'] ?>
            </p>

        </div>
        
        <!-- Related Products -->
        <section class="related-products">
            <div class="section-content">
                <h2 class="pro-title">RELATED PRODUCTS</h2>
                <div class="slider-container swiper">
                    <div class="slider-wrapper swiper-wrapper">
                        <?php foreach ($relatedProducts as $related): ?>
                            <div class="swiper-slide">
                                <div class="product-card"  data-id="<?= $related['id'] ?>">
                                    <?php if ($related['original_price'] > $related['price']): ?>
                                        <div class="product-tags">
                                            <span class="tags discount-tags">-<?= round(($related['original_price'] - $related['price']) / $related['original_price'] * 100) ?>%</span>
                                            <?php if ($related['is_hot']): ?>
                                                <span class="tags hot-tags">HOT</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="product-image">
                                        <img src="<?= $related['image'] ?>" alt="<?= htmlspecialchars($related['name']) ?>">
                                    </div>
                                    <div class="product-info-container">
                                        <div class="product-info">
                                            <h3 class="product-title"><?= htmlspecialchars($related['name']) ?></h3>
                                            <p class="product-category">⭐️</p>
                                            <div class="product-price">
                                                <span class="current-price"><?= number_format($related['price'], 0, ',', '.') ?>đ</span>
                                                <?php if ($related['original_price'] > $related['price']): ?>
                                                    <span class="original-price"><?= number_format($related['original_price'], 0, ',', '.') ?>đ</span>
                                                <?php endif; ?>
                                            </div>
                            <button class="add-to-cart" onclick="addToCart(<?= $related['id'] ?>, true)">ADD TO CART <span class="cart-icon"><i class="uil uil-shopping-cart"></i></span></button>          </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </section>
        
    <!-- Back-to-top button -->
    <a id="btop-button"></a>
    <!-- Cart Container -->
    <div class="cartcontainer">
        <div class="cart-header">
            <h3>Your Cart</h3>
            <button class="close-cart-btn"><i class="fas fa-times"></i></button>
        </div>
        <ul>
            <!-- Cart items will be loaded here -->
        </ul>
        <div class="subtotal">
            <span>Total: 0đ</span>
        </div>
        <button class="checkout-btn" onclick="proceedToCheckout()">Check out</button>
    </div>

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
                <h3>Information</h3>
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

    <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/Draggable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/InertiaPlugin.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/product.js"></script>

    <script>

</script>
</body>
</html>