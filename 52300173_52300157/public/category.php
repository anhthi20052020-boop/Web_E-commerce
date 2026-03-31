<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';
$validCategories = ['hot-news', 'tips-and-trends'];

if (!in_array($category, $validCategories)) {
    header("Location: blog.php");
    exit();
}

// Lay du lieu tu database
try {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE category = :category ORDER BY created_at DESC");
    $stmt->execute([':category' => $category]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $sectionTitle = ($category === 'hot-news') ? 'Hot News' : 'Tips and Trends';

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ucfirst(str_replace('-', ' ', $category)) ?> - Atait</title>
    <link rel="icon" type="image/x-icon" href="../uploads/logo.png">
    <link rel="stylesheet" href="assets/css/blog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar section-content">
            <a href="home.php" class="nav-logo">
                <h2 class="logo-text">Atait</h2>
            </a>
            <div class="search-box" id="searchBox">
                <form action="blog.php" method="GET">
                    <input type="text" name="search" id="srch" placeholder="Search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <ul class="nav-menu">
                <button id="menu-close-button" class="fa fa-times"></button>
                <li class="nav-item">
                    <a href="home.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="home.php" class="nav-link">About us</a>
                </li>
                <li class="nav-item">
                    <a href="blog.php" class="nav-link">Blog</a>
                </li>
                <li class="nav-item">
                    <a href="store.php" class="nav-link">Store</a>
                </li>
                <li class="nav-item">
                    <a href="contact.php" class="nav-link">Contact</a>
                </li>
            </ul>
            <button id="menu-open-button" class="fa fa-bars"></button>
        </nav>
    </header>

    <main>
        <h2 class="section-title"><?= $sectionTitle ?></h2>
        <section class="container mb-5">
            <div class="row">
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-4 col-lg-3 col-sm-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <img src="../uploads/<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                                <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                                <p class="card-text">BY <?= htmlspecialchars($post['author']) ?> • <?= date('F j, Y', strtotime($post['created_at'])) ?></p>
                                <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <!--Back-to-top button-->
        <a id="btop-button"></a>

        <!--Footer-->
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
    </main>

</body>
</html>