<?php
require_once __DIR__ . '/../config/config.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCSRFToken($_POST['csrf_token'])) {
    setFlashMessage('error', 'Invalid request.');
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$company_id = intval($_POST['company_id']);

// Check if already in watchlist
$stmt = $conn->prepare("SELECT watchlist_id FROM user_watchlist WHERE user_id = ? AND company_id = ?");
$stmt->execute([$user_id, $company_id]);

if ($stmt->rowCount() > 0) {
    setFlashMessage('info', 'Stock already in your watchlist.');
} else {
    $stmt = $conn->prepare("INSERT INTO user_watchlist (user_id, company_id) VALUES (?, ?)");
    
    if ($stmt->execute([$user_id, $company_id])) {
        logActivity($user_id, 'Watchlist Add', 'Added stock to watchlist');
        setFlashMessage('success', 'Stock added to watchlist!');
    } else {
        setFlashMessage('error', 'Failed to add stock.');
    }
}
header('Location: index.php');
exit();
?>
