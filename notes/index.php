<?php
$page_title = 'Stock Notes';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../api/StockAPI.php';

requireLogin();

$user_id = $_SESSION['user_id'];
$company_id = intval($_GET['stock'] ?? 0);

if ($company_id === 0) {
    setFlashMessage('error', 'Invalid stock.');
    header('Location: ../watchlist/');
    exit();
}

// Get stock details
$api = new StockAPI();
$stmt = $conn->prepare("SELECT * FROM stock_companies WHERE company_id = ?");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$stock = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$stock) {
    setFlashMessage('error', 'Stock not found.');
    header('Location: ../watchlist/');
    exit();
}

// Get user's notes for this stock
$stmt = $conn->prepare("
    SELECT * FROM stock_notes 
    WHERE user_id = ? AND company_id = ? 
    ORDER BY updated_at DESC
");
$stmt->bind_param("ii", $user_id, $company_id);
$stmt->execute();
$notes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="../watchlist/">Watchlist</a></li>
            <li class="breadcrumb-item active">Notes</li>
        </ol>
    </nav>
    
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-journal-text"></i> Notes for <?php echo htmlspecialchars($stock['symbol']); ?></h2>
            <p class="text-muted"><?php echo htmlspecialchars($stock['company_name']); ?></p>
        </div>
        <div class="col-md-4 text-end">
            <a href="add.php?stock=<?php echo $company_id; ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Note
            </a>
        </div>
    </div>
    
    <?php if (empty($notes)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No notes yet. Click "Add Note" to create your first note for this stock.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($notes as $note): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($note['note_title']); ?></h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($note['note_content'])); ?></p>
                            <?php if (!empty($note['tags'])): ?>
                                <div class="mb-2">
                                    <?php foreach (explode(',', $note['tags']) as $tag): ?>
                                        <span class="tag"><?php echo trim(htmlspecialchars($tag)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Updated: <?php echo formatDateTime($note['updated_at']); ?>
                                </small>
                                <div>
                                    <a href="edit.php?id=<?php echo $note['note_id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="delete.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                                        <input type="hidden" name="company_id" value="<?php echo $company_id; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-delete">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="../watchlist/detail.php?symbol=<?php echo $stock['symbol']; ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Stock Details
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
