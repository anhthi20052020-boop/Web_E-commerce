<?php
require_once '../../config/db.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if ($post_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID bài viết không hợp lệ']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT *, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') as created_at FROM comments WHERE post_id = ? ORDER BY created_at DESC");
    $stmt->execute([$post_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'comments' => $comments
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()]);
}