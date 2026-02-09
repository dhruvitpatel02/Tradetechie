<?php
$page_title = 'Add Note';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

$user_id = $_SESSION['user_id'];
$company_id = intval($_GET['stock'] ?? 0);

if ($company_id === 0) {
    setFlashMessage('error', 'Invalid stock.');
    header('Location: ../watchlist/');
    exit();
}

// Get stock details
$stmt = $conn->prepare("SELECT * FROM stock_companies WHERE company_id = ?");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$stock = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('error', 'Invalid request.');
        header('Location: add.php?stock=' . $company_id);
        exit();
    }
    
    $title = sanitize($_POST['note_title']);
    $content = sanitize($_POST['note_content']);
    $tags = sanitize($_POST['tags'] ?? '');
    
    if (empty($title) || empty($content)) {
        setFlashMessage('error', 'Title and content are required.');
    } else {
        $stmt = $conn->prepare("INSERT INTO stock_notes (user_id, company_id, note_title, note_content, tags) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $user_id, $company_id, $title, $content, $tags);
        
        if ($stmt->execute()) {
            logActivity($user_id, 'Note Created', 'Created note for ' . $stock['symbol']);
            setFlashMessage('success', 'Note created successfully!');
            header('Location: index.php?stock=' . $company_id);
            exit();
        } else {
            setFlashMessage('error', 'Failed to create note.');
        }
        $stmt->close();
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Add Note for <?php echo htmlspecialchars($stock['symbol']); ?></h4>
                    <small class="text-muted"><?php echo htmlspecialchars($stock['company_name']); ?></small>
                </div>
                <div class="card-body">
                    <form action="" method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="mb-3">
                            <label for="note_title" class="form-label">Note Title *</label>
                            <input type="text" class="form-control" id="note_title" name="note_title" required maxlength="255">
                            <div class="invalid-feedback">Please enter a title.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="note_content" class="form-label">Note Content *</label>
                            <textarea class="form-control" id="note_content" name="note_content" rows="8" required data-note-id="new"></textarea>
                            <div class="invalid-feedback">Please enter note content.</div>
                            <small class="text-muted">Write your analysis, observations, or reminders about this stock. Auto-saves every 2 seconds.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags (Optional)</label>
                            <input type="text" class="form-control" id="tags" name="tags" placeholder="strategy, long-term, risk">
                            <small class="text-muted">Separate tags with commas</small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Save Note
                            </button>
                            <a href="index.php?stock=<?php echo $company_id; ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
