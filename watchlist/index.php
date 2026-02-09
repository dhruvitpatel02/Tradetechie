<?php
$page_title = 'My Watchlist';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../api/StockAPI.php';

requireLogin();

$api = new StockAPI();
$user_id = $_SESSION['user_id'];

$watchlist = [];
$conn = db();

if ($conn) {
    $stmt = $conn->prepare("
        SELECT w.*, s.symbol, s.company_name, s.last_price, s.change_percent, s.sector, s.last_updated
        FROM user_watchlist w
        JOIN stock_companies s ON w.company_id = s.company_id
        WHERE w.user_id = ?
        ORDER BY w.added_at DESC
    ");
    $stmt->execute([$user_id]);
    $watchlist = $stmt->fetchAll();
}
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-star-fill text-warning"></i> My Watchlist</h2>
            <p class="text-muted">Track your favorite stocks</p>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="bi bi-plus-circle"></i> Add Stock
            </button>
        </div>
    </div>
    
    <?php if (empty($watchlist)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Your watchlist is empty. Click "Add Stock" to start tracking stocks.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($watchlist as $stock): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100" data-symbol="<?php echo $stock['symbol']; ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="mb-0"><?php echo htmlspecialchars($stock['symbol']); ?></h5>
                                    <small class="text-muted"><?php echo htmlspecialchars($stock['company_name']); ?></small>
                                </div>
                                <form action="remove.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="watchlist_id" value="<?php echo $stock['watchlist_id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            
                            <div class="mb-3">
                                <h3 class="mb-0 price-value">₹<?php echo number_format($stock['last_price'], 2); ?></h3>
                                <span class="badge bg-<?php echo $stock['change_percent'] >= 0 ? 'success' : 'danger'; ?> change-percent">
                                    <?php echo $stock['change_percent'] >= 0 ? '+' : ''; ?>
                                    <?php echo number_format($stock['change_percent'], 2); ?>%
                                </span>
                            </div>
                            
                            <p class="text-muted mb-2">
                                <small><i class="bi bi-building"></i> <?php echo htmlspecialchars($stock['sector']); ?></small>
                            </p>
                            
                            <div class="d-flex gap-2">
                                <a href="detail.php?symbol=<?php echo $stock['symbol']; ?>" class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="bi bi-graph-up"></i> Details
                                </a>
                                <a href="../notes/?stock=<?php echo $stock['company_id']; ?>" class="btn btn-sm btn-outline-secondary flex-fill">
                                    <i class="bi bi-journal-text"></i> Notes
                                </a>
                            </div>
                        </div>
                        <div class="card-footer text-muted">
                            <small>Updated: <?php echo date('h:i A', strtotime($stock['last_updated'])); ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Search Stock Modal -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Stock to Watchlist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="stockSearch" placeholder="Search by symbol or company name...">
                </div>
                <div id="searchResults"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Stock search functionality
const searchInput = document.getElementById('stockSearch');
const searchResults = document.getElementById('searchResults');

searchInput.addEventListener('input', function() {
    const query = this.value.trim();
    
    if (query.length < 2) {
        searchResults.innerHTML = '';
        return;
    }
    
    fetch(`search.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                searchResults.innerHTML = '<p class="text-muted">No stocks found</p>';
                return;
            }
            
            let html = '<div class="list-group">';
            data.forEach(stock => {
                html += `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${stock.symbol}</strong> - ${stock.company_name}
                            <br><small class="text-muted">${stock.sector}</small>
                        </div>
                        <form action="add.php" method="POST" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="company_id" value="${stock.company_id}">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus"></i> Add
                            </button>
                        </form>
                    </div>
                `;
            });
            html += '</div>';
            searchResults.innerHTML = html;
        });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
