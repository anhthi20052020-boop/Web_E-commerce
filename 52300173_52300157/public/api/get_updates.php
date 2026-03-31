<?php
require_once '../../config/db.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
$current_likes = isset($_GET['current_likes']) ? intval($_GET['current_likes']) : 0;
$current_comments = isset($_GET['current_comments']) ? intval($_GET['current_comments']) : 0;

if ($post_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID bài viết không hợp lệ']);
    exit;
}

try {
    // Lấy thông tin like
    $user_ip = $_SERVER['REMOTE_ADDR'];
    
    // Kiểm tra trạng thái like của user hiện tại
    $userLikedStmt = $pdo->prepare("SELECT id FROM likes WHERE post_id = ? AND user_ip = ?");
    $userLikedStmt->execute([$post_id, $user_ip]);
    $user_liked = $userLikedStmt->fetch() ? true : false;
    
    // Lấy tổng số like
    $likeStmt = $pdo->prepare("SELECT COUNT(*) as like_count FROM likes WHERE post_id = ?");
    $likeStmt->execute([$post_id]);
    $like_count = $likeStmt->fetch(PDO::FETCH_ASSOC)['like_count'];
    
    // Lấy tổng số bình luận
    $commentStmt = $pdo->prepare("SELECT COUNT(*) as comment_count FROM comments WHERE post_id = ?");
    $commentStmt->execute([$post_id]);
    $comments_count = $commentStmt->fetch(PDO::FETCH_ASSOC)['comment_count'];
    
    echo json_encode([
        'success' => true,
        'like_count' => $like_count,
        'user_liked' => $user_liked,
        'comments_count' => $comments_count,
        'needs_refresh' => ($like_count != $current_likes || $comments_count != $current_comments)
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()]);
}