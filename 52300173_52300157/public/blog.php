<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

// Xu ly tim kiem
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Lay du lieu tu database
try {
    // Lay bai viet cho carousel
    $carouselStmt = $pdo->prepare("SELECT * FROM posts WHERE category = 'hot-news' ORDER BY created_at DESC LIMIT 3");
    $carouselStmt->execute();
    $carouselPosts = $carouselStmt->fetchAll(PDO::FETCH_ASSOC);

    $sideStmt = $pdo->prepare("SELECT * FROM posts WHERE category = 'hot-news' ORDER BY created_at DESC LIMIT 3 OFFSET 3");
    $sideStmt->execute();
    $sidePosts = $sideStmt->fetchAll(PDO::FETCH_ASSOC);

    $hotNewsStmt = $pdo->prepare("SELECT * FROM posts WHERE category = 'hot-news' ORDER BY created_at DESC LIMIT 8");
    $hotNewsStmt->execute();
    $hotNewsPosts = $hotNewsStmt->fetchAll(PDO::FETCH_ASSOC);

    $tipsStmt = $pdo->prepare("SELECT * FROM posts WHERE category = 'tips-and-trends' ORDER BY created_at DESC LIMIT 8");
    $tipsStmt->execute();
    $tipsPosts = $tipsStmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($search)) {
        $searchStmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE :search OR content LIKE :search ORDER BY created_at DESC");
        $searchStmt->execute([':search' => "%$search%"]);
        $searchResults = $searchStmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Atait</title>
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
                    <input type="text" name="search" id="srch" placeholder="Search" value="<?= htmlspecialchars($search) ?>">
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
                    <a href="#" class="nav-link">Blog</a>
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
        <?php if (!empty($search)): ?>
            <section class="container mb-5">
                <h2 class="section-title">Search Results for "<?= htmlspecialchars($search) ?>"</h2>
                <div class="row">
                    <?php foreach ($searchResults as $post): ?>
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
        <?php else: ?>
            <!-- Banner -->
            <div class="banner-container">
                <div class="news-banner">
                    <div class="main-news">
                        <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <?php foreach ($carouselPosts as $index => $post): ?>
                                    <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="<?= $index ?>" <?= $index === 0 ? 'class="active"' : '' ?>></button>
                                <?php endforeach; ?>
                            </div>
                        
                            <div class="carousel-inner">
                                <?php foreach ($carouselPosts as $index => $post): ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                        <a href="post.php?id=<?= $post['id'] ?>" class="carousel-link">
                                            <div class="overlay">
                                                <h3 class="title"><?= htmlspecialchars($post['title']) ?></h3>
                                                <p class="date"><?= date('F j, Y', strtotime($post['created_at'])) ?></p>
                                            </div>
                                            <img src="../uploads/<?= htmlspecialchars($post['image']) ?>" class="d-block w-100" alt="<?= htmlspecialchars($post['title']) ?>">
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        
                            <!-- Controls -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="side-news">
                        <?php foreach ($sidePosts as $post): ?>
                            <a href="post.php?id=<?= $post['id'] ?>" class="news-item-link">
                                <div class="news-item">
                                    <img src="../uploads/<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                                    <div class="news-content">
                                        <h3 class="title"><?= htmlspecialchars($post['title']) ?></h3>
                                        <p class="date"><?= date('F j, Y', strtotime($post['created_at'])) ?></p>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Hot News -->
            <h2 class="section-title">Hot News</h2>
            <section class="container mb-5">
                <div class="row">
                    <?php foreach ($hotNewsPosts as $post): ?>
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

                <div class="show-all">
                    <a href="category.php?category=hot-news" class="show-all-btn"><i class="fas fa-chevron-down"></i></a>
                </div>
            </section>

            <!-- Tips and Trends -->
            <h2 class="section-title">Tips and Trends</h2>
            <section class="container mb-5">
                <div class="row">
                    <?php foreach ($tipsPosts as $post): ?>
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

                <div class="show-all">
                    <a href="category.php?category=tips-and-trends" class="show-all-btn"><i class="fas fa-chevron-down"></i></a>
                </div>
            </section>
        <?php endif; ?>
        
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/blog.js"></script>
</body>
</html>