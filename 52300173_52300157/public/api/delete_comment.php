<?php
require_once '../../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$comment_id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;

if ($comment_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid comment ID']);
    exit;
}

try {
    // Kiểm tra xem comment có tồn tại không
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
    $stmt->execute([$comment_id]);
    $comment = $stmt->fetch();

    if (!$comment) {
        echo json_encode(['success' => false, 'error' => 'Comment not found']);
        exit;
    }

    // Thực hiện xóa
    $deleteStmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $deleteStmt->execute([$comment_id]);

    echo json_encode(['success' => true, 'comment_id' => $comment_id]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}