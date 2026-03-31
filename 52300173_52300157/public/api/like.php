<?php
ini_set('log_errors', 1);
ini_set('error_log', '../error.log');
require_once '../../config/db.php';
require_once '../../includes/functions.php';

// Start output buffering
ob_start();

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    ob_end_clean();
    echo json_encode(['success' => false, 'error' => 'Phương thức yêu cầu không hợp lệ']);
    exit;
}

$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
if ($post_id <= 0) {
    http_response_code(400);
    ob_end_clean();
    echo json_encode(['success' => false, 'error' => 'ID bài viết không hợp lệ']);
    exit;
}

try {
    $user_ip = $_SERVER['REMOTE_ADDR'];
    
    // Check if IP has already liked this post
    $checkStmt = $pdo->prepare("SELECT id FROM likes WHERE post_id = ? AND user_ip = ?");
    $checkStmt->execute([$post_id, $user_ip]);
    $alreadyLiked = $checkStmt->fetch();

    if ($alreadyLiked) {
        // Unlike if already liked
        $deleteStmt = $pdo->prepare("DELETE FROM likes WHERE post_id = ? AND user_ip = ?");
        $deleteStmt->execute([$post_id, $user_ip]);
    } else {
        // Like if not already liked
        $insertStmt = $pdo->prepare("INSERT INTO likes (post_id, user_ip) VALUES (?, ?)");
        $insertStmt->execute([$post_id, $user_ip]);
    }
    
    // Get updated like count
    $likeStmt = $pdo->prepare("SELECT COUNT(*) as like_count FROM likes WHERE post_id = ?");
    $likeStmt->execute([$post_id]);
    $likeCount = $likeStmt->fetch(PDO::FETCH_ASSOC)['like_count'];
    
    ob_end_clean();
    echo json_encode([
        'success' => true, 
        'like_count' => $likeCount,
        'user_liked' => !$alreadyLiked
    ]);
    
} catch (PDOException $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()]);
} catch (Exception $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Lỗi: ' . $e->getMessage()]);
}

exit;
?>