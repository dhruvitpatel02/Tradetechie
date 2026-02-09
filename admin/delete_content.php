<?php
/**
 * Delete Content Handler
 * Handles deletion of educational content
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Require admin access
requireAdmin();

// Get content ID
$content_id = intval($_GET['id'] ?? 0);

if ($content_id === 0) {
    setFlashMessage('error', 'Invalid content ID.');
    header('Location: content_manage.php');
    exit();
}

// Get content details for logging
$content = getContentById($content_id);

if (!$content) {
    setFlashMessage('error', 'Content not found.');
    header('Location: content_manage.php');
    exit();
}

// Delete content
$stmt = $conn->prepare("DELETE FROM educational_content WHERE content_id = ?");
$stmt->bind_param("i", $content_id);

if ($stmt->execute()) {
    logActivity($_SESSION['user_id'], 'Content Deleted', 'Deleted content: ' . $content['title']);
    setFlashMessage('success', 'Content deleted successfully!');
} else {
    setFlashMessage('error', 'Failed to delete content.');
}

$stmt->close();
header('Location: content_manage.php');
exit();
?>
