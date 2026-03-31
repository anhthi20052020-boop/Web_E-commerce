<?php

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

// Get raw POST data
$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;

$post_id = isset($data['post_id']) ? intval($data['post_id']) : 0;
$username = isset($data['username']) ? sanitizeInput($data['username']) : '';
$message = isset($data['message']) ? sanitizeInput($data['message']) : '';

if ($post_id <= 0) {
    http_response_code(400);
    ob_end_clean();
    echo json_encode(['success' => false, 'error' => 'ID bài viết không hợp lệ']);
    exit;
}

if (empty($username) || empty($message)) {
    http_response_code(400);
    ob_end_clean();
    echo json_encode(['success' => false, 'error' => 'Tên người dùng và nội dung bình luận là bắt buộc']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO comments (post_id, username, message) VALUES (?, ?, ?)");
    $stmt->execute([$post_id, $username, $message]);
    
    $commentId = $pdo->lastInsertId();
    $getCommentStmt = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
    $getCommentStmt->execute([$commentId]);
    $comment = $getCommentStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$comment) {
        throw new Exception("Không thể truy xuất bình luận sau khi chèn");
    }
    
    ob_end_clean();
    echo json_encode([
        'success' => true,
        'comment' => [
            'username' => htmlspecialchars($comment['username']),
            'created_at' => date('d/m/Y H:i', strtotime($comment['created_at'])),
            'message' => htmlspecialchars($comment['message'])
        ]
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