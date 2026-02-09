<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];
$content_id = intval($_POST['content_id']);

$stmt = $conn->prepare("INSERT IGNORE INTO user_progress (user_id, content_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $content_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
?>
