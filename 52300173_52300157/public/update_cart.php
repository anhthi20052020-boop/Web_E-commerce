<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['userId']) && isset($input['cartData'])) {
        try {
            $userId = $input['userId'];
            $cartData = $input['cartData'];
            
            // Cap nhat vao database
            $stmt = $pdo->prepare("UPDATE users SET cart = ? WHERE id = ?");
            $stmt->execute([json_encode($cartData), $userId]);
            
            // Cap nhat vao session
            $_SESSION['cart'] = $cartData;
            
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>