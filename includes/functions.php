<?php
/**
 * Common Functions
 * Reusable functions used across the application
 */

require_once __DIR__ . '/../config/config.php';

/**
 * Log user activity
 */
function logActivity($user_id, $action, $description = '') {
    global $conn;
    
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $action, $description, $ip_address]);
}

/**
 * Get user by ID
 */
function getUserById($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT user_id, full_name, email, phone, user_type, status, created_at FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

/**
 * Get all educational content
 */
function getAllContent($category = null, $status = 'published') {
    global $conn;
    
    if ($category) {
        $stmt = $conn->prepare("SELECT * FROM educational_content WHERE category = ? AND status = ? ORDER BY order_position ASC, created_at DESC");
        $stmt->execute([$category, $status]);
    } else {
        $stmt = $conn->prepare("SELECT * FROM educational_content WHERE status = ? ORDER BY order_position ASC, created_at DESC");
        $stmt->execute([$status]);
    }
    
    return $stmt->fetchAll();
}

/**
 * Get content by slug
 */
function getContentBySlug($slug) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM educational_content WHERE slug = ? AND status = 'published'");
    $stmt->execute([$slug]);
    $content = $stmt->fetch();
    
    if ($content) {
        $update_stmt = $conn->prepare("UPDATE educational_content SET views = views + 1 WHERE content_id = ?");
        $update_stmt->execute([$content['content_id']]);
    }
    
    return $content;
}

/**
 * Get content by ID
 */
function getContentById($content_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM educational_content WHERE content_id = ?");
    $stmt->execute([$content_id]);
    return $stmt->fetch();
}

/**
 * Count content by category
 */
function countContentByCategory() {
    global $conn;
    
    $result = $conn->query("SELECT category, COUNT(*) as count FROM educational_content WHERE status = 'published' GROUP BY category");
    $counts = [];
    
    foreach ($result->fetchAll() as $row) {
        $counts[$row['category']] = $row['count'];
    }
    
    return $counts;
}

/**
 * Get total users count
 */
function getTotalUsers() {
    global $conn;
    
    $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE user_type = 'user'");
    $row = $result->fetch();
    
    return $row['count'];
}

/**
 * Get recent activities
 */
function getRecentActivities($limit = 10) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT al.*, u.full_name, u.email FROM activity_log al LEFT JOIN users u ON al.user_id = u.user_id ORDER BY al.created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}
?>
