<?php
require_once __DIR__ . '/../config/config.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCSRFToken($_POST['csrf_token'])) {
    setFlashMessage('error', 'Invalid request.');
    header('Location: ../watchlist/');
    exit();
}

$conn = db();
if (!$conn) {
    setFlashMessage('error', 'Service temporarily unavailable.');
    header('Location: ../watchlist/');
    exit();
}

$user_id = $_SESSION['user_id'];
$note_id = intval($_POST['note_id']);
$company_id = intval($_POST['company_id']);

$stmt = $conn->prepare("DELETE FROM stock_notes WHERE note_id = ? AND user_id = ?");

if ($stmt->execute([$note_id, $user_id])) {
    logActivity($user_id, 'Note Deleted', 'Deleted stock note');
    setFlashMessage('success', 'Note deleted successfully.');
} else {
    setFlashMessage('error', 'Failed to delete note.');
}
header('Location: index.php?stock=' . $company_id);
exit();
?>
