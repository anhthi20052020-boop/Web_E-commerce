<?php
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function estimateReadingTime($content) {
    $wordCount = str_word_count(strip_tags($content));
    $minutes = floor($wordCount / 200);
    return $minutes < 1 ? 1 : $minutes;
}

function getLikesCount($postId) {
    
    // Hoặc nếu dùng database
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM post_likes WHERE post_id = :post_id");
    $stmt->execute([':post_id' => $postId]);
    return $stmt->fetchColumn();
}



/**
 * Format date for display
 * @param string $date
 * @param string $format
 * @return string
 */
function format_date($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

/**
 * Get post excerpt
 * @param string $content
 * @param int $length
 * @return string
 */
function get_excerpt($content, $length = 100) {
    $content = strip_tags($content);
    if (strlen($content) > $length) {
        $content = substr($content, 0, $length) . '...';
    }
    return $content;
}

/**
 * Check if user is logged in (placeholder for authentication)
 * @return bool
 */
function is_logged_in() {
    // Placeholder: Implement actual session-based authentication
    return isset($_SESSION['user_id']);
}

/**
 * Get post by ID
 * @param PDO $pdo
 * @param int $post_id
 * @return array|null
 */
function get_post_by_id($pdo, $post_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute([':id' => $post_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching post: " . $e->getMessage());
        return null;
    }
}

/**
 * Get comments by post ID
 * @param PDO $pdo
 * @param int $post_id
 * @return array
 */
function get_comments_by_post($pdo, $post_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = :post_id ORDER BY created_at DESC");
        $stmt->execute([':post_id' => $post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching comments: " . $e->getMessage());
        return [];
    }
}

/**
 * Log errors to a file
 * @param string $message
 */
function log_error($message) {
    error_log(date('Y-m-d H:i:s') . " - $message\n", 3, '../logs/error.log');
}

// Hàm cập nhật giỏ hàng
function updateUserCart($userId, $cartData) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET cart = ? WHERE id = ?");
    $stmt->execute([json_encode($cartData), $userId]);
}

// Các hàm helper khác có thể thêm vào đây
?>