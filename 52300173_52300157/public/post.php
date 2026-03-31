<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$search = isset($_GET['search']) ? $_GET['search'] : '';
try {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Không tìm thấy bài viết']);
        exit;

    }

    $user_ip = $_SERVER['REMOTE_ADDR'];
    $userLikedStmt = $pdo->prepare("SELECT id FROM likes WHERE post_id = ? AND user_ip = ?");
    $userLikedStmt->execute([$post_id, $user_ip]);
    $userLiked = $userLikedStmt->fetch() ? true : false;
    $commentStmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC");
    $commentStmt->execute([$post_id]);
    $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
    $likeStmt = $pdo->prepare("SELECT COUNT(*) as like_count FROM likes WHERE post_id = ?");
    $likeStmt->execute([$post_id]);
    $likeCount = $likeStmt->fetch(PDO::FETCH_ASSOC)['like_count'];

} catch (PDOException $e) {
    die("Lỗi CSDL: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Atait</title>
    <link rel="icon" type="image/x-icon" href="../uploads/logo.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/post.css">
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
        <div class="article-container">
            <main class="article-content">
                <h1 class="article-title"><?= htmlspecialchars($post['title']) ?></h1>
                
                <div class="article-meta">
                    <span class="author"><?= htmlspecialchars($post['author']) ?></span>
                    <span class="publish-date"><?= date('d/m/Y - H:i', strtotime($post['created_at'])) ?></span>
                    <span class="reading-time">10 phút đọc</span>
                </div>
                
                <img src="../uploads/<?= htmlspecialchars($post['image']) ?>" alt="Ảnh bài viết" class="article-image">
                
                <div class="article-text">
                    <?= $post['content'] ?>
                </div>

                <div class="action-section">
                    <button id="likeBtn" class="action-btn">
                        <i class="<?= $userLiked ? 'fas' : 'far' ?> fa-heart"></i>
                        <span id="likeCount" class="action-count"><?= $likeCount ?></span>
                    </button>
                    <div class="comment-info">
                      <i class="far fa-comment"></i>
                      <span id="totalComments"><?= count($comments) ?></span>
                    </div>
                </div>

                <h1 class="title">Thảo luận</h1>
                <form id="commentForm">
                    <input type="text" id="username" placeholder="Tên bạn" required />
                    <textarea id="message" placeholder="Nhập bình luận..." required></textarea>
                    <button type="submit" class="btnnn">Gửi bình luận</button>
                </form>

                <div id="comments">
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment" data-comment-id="<?= $comment['id'] ?>">
                            <div class="comment-main">
                                <div class="avatar"><?= strtoupper(substr(htmlspecialchars($comment['username']), 0, 1)) ?></div>
                                <div class="comment-body">
                                    <div class="comment-content">
                                        <span class="comment-user"><?= htmlspecialchars($comment['username']) ?></span>
                                        <span class="comment-message"><?= htmlspecialchars($comment['message']) ?></span>
                                    </div>
                                    <div class="comment-footer">
                                        <div class="left-footer">
                                            <span class="comment-time"><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></span>
                                        </div>
                                        <div class="comment-actions">
                                            <div class="comment-like">
                                                <button class="like-comment-btn">
                                                    <i class="far fa-heart"></i>
                                                </button>
                                            </div>
                                            <button class="delete-comment-btn" data-comment-id="<?= $comment['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </main>


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
    <script>
        $(document).ready(function() {
            $('#likeBtn').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'api/like.php',
                    method: 'POST',
                    data: { post_id: <?= $post_id ?> },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#likeCount').text(response.like_count);
                            $(this).find('i').toggleClass('far fas');
                        } else {
                            alert('Lỗi: ' + response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Có lỗi xảy ra khi like bài viết: ' + error);
                    }
                });
            });

            // Them binh luan
            $('#commentForm').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var message = $('#message').val();
                var post_id = <?= $post_id ?>;

                if (username && message) {
                    $.ajax({
                        url: 'api/comment.php',
                        method: 'POST',
                        data: { post_id: post_id, username: username, message: message },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#username').val('');
                                $('#message').val('');
                            } else {
                                alert('Lỗi: ' + response.error);
                            }
                        }
                    });
                }
            });

            // Xoa binh luan
            $(document).on('click', '.delete-comment-btn', function(e) {
                e.preventDefault();
                var commentId = $(this).data('comment-id');
                
                if (confirm('Bạn có chắc chắn muốn xóa bình luận này?')) {
                    $.ajax({
                        url: 'api/delete_comment.php',
                        method: 'POST',
                        data: { comment_id: commentId },
                        dataType: 'json',
                        success: function(response) {
                            if (!response.success) {
                                alert('Lỗi: ' + response.error);
                            }

                        }
                    });
                }
            });
        });

        // Ham kiem tra du lieu moi
        function checkForUpdates() {
            $.ajax({
                url: 'api/get_updates.php',
                method: 'GET',
                data: { 
                    post_id: <?= $post_id ?>,
                    current_likes: $('#likeCount').text(),
                    current_comments: $('#comments .comment').length
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Cap nhat like count
                        if (response.like_count != $('#likeCount').text()) {
                            $('#likeCount').text(response.like_count);
                            
                            var likeBtn = $('#likeBtn i');
                            if (response.user_liked) {
                                likeBtn.removeClass('far').addClass('fas');
                            } else {
                                likeBtn.removeClass('fas').addClass('far');
                            }
                        }
                        
                        if (response.comments_count != $('#comments .comment').length) {
                            fetchComments();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi khi kiểm tra cập nhật:', error);
                }
            });
        }

        // Ham tai lai ds binh luan
        function fetchComments() {
            $.ajax({
                url: 'api/get_comments.php',
                method: 'GET',
                data: { post_id: <?= $post_id ?> },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Xoa binh luan cu
                        $('#comments').empty();
                        
                        // Them binh luan moi
                        response.comments.forEach(function(comment) {
                            var commentHtml = `
                                <div class="comment" data-comment-id="${comment.id}">
                                    <div class="comment-main">
                                        <div class="avatar">${comment.username.charAt(0).toUpperCase()}</div>
                                        <div class="comment-body">
                                            <div class="comment-content">
                                                <span class="comment-user">${comment.username}</span>
                                                <span class="comment-message">${comment.message}</span>
                                            </div>
                                            <div class="comment-footer">
                                                <div class="left-footer">
                                                    <span class="comment-time">${comment.created_at}</span>
                                                </div>
                                                <div class="comment-actions">
                                                    <div class="comment-like">
                                                        <button class="like-comment-btn">
                                                            <i class="far fa-heart"></i>
                                                        </button>
                                                    </div>
                                                    <button class="delete-comment-btn" data-comment-id="${comment.id}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                            $('#comments').append(commentHtml);
                        });
                        
                        // Cap nhat tong so binh luan
                        $('#totalComments').text(response.comments.length);
                    }
                }
            });
        }

        // Kiem tra cap nhat sau moi 3 giay
        setInterval(checkForUpdates, 3000);


        $(document).ready(function() {
            checkForUpdates();
        });
    </script>
</body>
</html>