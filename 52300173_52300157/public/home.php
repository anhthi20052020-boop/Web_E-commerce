<?php
include("../config/db.php");

$hotSellerStmt = $pdo->query("SELECT * FROM products WHERE is_bestseller = 1 ORDER BY created_at DESC LIMIT 6");
$hotSellerProducts = $hotSellerStmt->fetchAll();

$latestPostsStmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 3");
$latestPosts = $latestPostsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATAIT</title>
    <link rel="icon" type="image/x-icon" href="../uploads/logo.png">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
</head>
<body>


    <header>
        <nav class="navbar section-content">
            <a href="#" class = "nav-logo">
                <h2 class = "logo-text">Atait</h2>
            </a>
            <ul class = "nav-menu">
                <button id="menu-close-button" class="fa fa-times"></button>

                <li class = "nav-item">
                    <a href="#" class = "nav-link">Home</a>
                
                    <a href="#about" class = "nav-link">About us</a>
                
                    <a href="blog.php" class = "nav-link">Blog</a>
                
                    <a href="store.php" class = "nav-link">Store</a>
                
                    <a href="contact.php" target="_blank" class = "nav-link">Contact</a>
                
                </li>
            </ul>
            <button id="menu-open-button" class="fa fa-bars"></button>

        </nav>
    </header>
    <!-- Hero Section -->
    <section class="hero-section">
        <?php
        $stmt = $pdo->query("SELECT * FROM banners");
        $banners = $stmt->fetchAll();
        foreach ($banners as $index => $banner):
        ?>
        <video class="video-slider <?= $index == 0 ? 'active' : '' ?>" src="<?= $banner['video_url'] ?>" autoplay muted loop></video>
        <?php endforeach; ?>

        <?php foreach ($banners as $index => $banner): ?>
        <div class="hero-details <?= $index == 0 ? 'active' : '' ?>">
            <h1><?= htmlspecialchars($banner['title']) ?><br><span><?= htmlspecialchars($banner['subtitle']) ?></span></h1>
            <p><?= htmlspecialchars($banner['description']) ?></p>
            <a href="product.php?id=<?= $banner['product_id'] ?>">View more</a>
        </div>
        <?php endforeach; ?>

        <div class="slider-navigation">
            <?php foreach ($banners as $index => $banner): ?>
            <div class="nav-btn <?= $index == 0 ? 'active' : '' ?>"></div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- About Us -->
    <section class="about-section" id="about">
        <div class="section-content">
            <?php
            $stmt = $pdo->query("SELECT * FROM about_us LIMIT 1");
            $about = $stmt->fetch();
            ?>
            <div class="about-image-wrapper">
                <img src="<?= $about['image_url'] ?>" alt="About" class="about-image">
            </div>
            <div class="about-details">
                <h2 class="section-title">About us</h2>
                <p class="text"><?= $about['description'] ?></p>
            </div>
        </div>
    </section>

    <!-- Hot Seller -->
    <section class="store-section">
        <h2 class="section-title">Hot seller</h2>
        <div class="section-content">
            <ul class="store-list">
                <?php foreach ($hotSellerProducts as $product): ?>
                <li class="store-item card">
                    <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="store-image">
                    <h3 class="name"><?= htmlspecialchars($product['name']) ?></h3>

                    <div class="price"><?= number_format($product['price'], 0, ',', '.') ?>đ</div>
                    <a href="store.php?product_id=<?= $product['id'] ?>" class="view-button">View Details</a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <!-- Feedback -->
    <section class="feedback-section">
        <h2 class="section-title">Feedback</h2>
        <div class="section-content">
            <div class="slider-container swiper">
                <div class="slider-wrapper">
                    <ul class="feedback-list swiper-wrapper">
                        <?php
                        $stmt = $pdo->query("SELECT * FROM feedbacks");
                        foreach ($stmt as $fb):
                        ?>
                        <li class="feedback swiper-slide">
                            <img src="<?= $fb['image_url'] ?>" alt="user" class="user-image">
                            <h2 class="name"><?= $fb['name'] ?></h2>
                            <i class="text-feedback">"<?= $fb['comment'] ?>"</i>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="swiper-pagination"></div>
                        <div class="swiper-slide-button swiper-button-prev"></div>
                        <div class="swiper-slide-button swiper-button-next"></div>
                </div>
            </div>
        </div>
    </section>

    <!--Forum section-->
    <section class="forum-section" id="forum">
        <h2 class="section-title">Blog</h2>
        <div class="section-content">
            <ul class="forum-list">
                <?php foreach ($latestPosts as $post): ?>
                <li class="forum-item">
                    <img src="../uploads/<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="forum-image">
                    <div class="overlay">
                        <h3 class="name"><?= htmlspecialchars($post['title']) ?></h3>
                        <p class="text"><?= htmlspecialchars(substr($post['content'], 0, 100)) ?>...</p>
                        <!-- Thay đổi link từ blog.php sang post.php -->
                        <a href="post.php?id=<?= $post['id'] ?>" class="forum-button">Read More</a>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

                <a id="btop-button"></a>


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

        <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/home.js"></script>
</body>
</html>