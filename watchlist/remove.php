<?php
require_once __DIR__ . '/../config/config.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCSRFToken($_POST['csrf_token'])) {
    setFlashMessage('error', 'Invalid request.');
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$watchlist_id = intval($_POST['watchlist_id']);

$stmt = $conn->prepare("DELETE FROM user_watchlist WHERE watchlist_id = ? AND user_id = ?");
$stmt->bind_param("ii", $watchlist_id, $user_id);

if ($stmt->execute()) {
    logActivity($user_id, 'Watchlist Remove', 'Removed stock from watchlist');
    setFlashMessage('success', 'Stock removed from watchlist.');
} else {
    setFlashMessage('error', 'Failed to remove stock.');
}

$stmt->close();
header('Location: index.php');
exit();
?>
