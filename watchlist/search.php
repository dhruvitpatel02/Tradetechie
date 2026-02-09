<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../api/StockAPI.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit();
}

$api = new StockAPI();
$results = $api->searchStocks($query);

echo json_encode($results);
?>
