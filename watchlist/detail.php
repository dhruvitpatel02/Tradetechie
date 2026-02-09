<?php
$page_title = 'Stock Details';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../api/StockAPI.php';

requireLogin();

$symbol = $_GET['symbol'] ?? '';
$api = new StockAPI();
$stock = $api->getStockQuote($symbol);

if (!$stock) {
    setFlashMessage('error', 'Stock not found.');
    header('Location: index.php');
    exit();
}

$historical = $api->getHistoricalData($symbol, 30);
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php">Watchlist</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($stock['symbol']); ?></li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-md-8">
            <h2><?php echo htmlspecialchars($stock['company_name']); ?></h2>
            <p class="text-muted"><?php echo $stock['symbol']; ?> | <?php echo $stock['exchange']; ?></p>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h1 class="mb-2">₹<?php echo number_format($stock['last_price'], 2); ?></h1>
                    <span class="badge bg-<?php echo $stock['change_percent'] >= 0 ? 'success' : 'danger'; ?> fs-6">
                        <?php echo $stock['change_percent'] >= 0 ? '+' : ''; ?>
                        <?php echo number_format($stock['change_percent'], 2); ?>%
                    </span>
                    <p class="text-muted mt-2 mb-0">
                        <small>Last updated: <?php echo date('d M Y, h:i A', strtotime($stock['last_updated'])); ?></small>
                    </p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Price Chart (30 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="priceChart" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">Company Info</div>
                <div class="card-body">
                    <p><strong>Sector:</strong><br><?php echo htmlspecialchars($stock['sector']); ?></p>
                    <p><strong>Exchange:</strong><br><?php echo $stock['exchange']; ?></p>
                    <p class="mb-0"><strong>Symbol:</strong><br><?php echo $stock['symbol']; ?></p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">Quick Actions</div>
                <div class="card-body d-grid gap-2">
                    <a href="../notes/?stock=<?php echo $stock['company_id']; ?>" class="btn btn-primary">
                        <i class="bi bi-journal-text"></i> View Notes
                    </a>
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Watchlist
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('priceChart').getContext('2d');
const chartData = <?php echo json_encode($historical); ?>;

new Chart(ctx, {
    type: 'line',
    data: {
        labels: chartData.map(d => d.date),
        datasets: [{
            label: 'Price (₹)',
            data: chartData.map(d => d.price),
            borderColor: 'rgb(13, 110, 253)',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        },
        scales: {
            y: {
                beginAtZero: false
            }
        }
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
