<?php
$page_title = 'Edit Note';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

$user_id = $_SESSION['user_id'];
$note_id = intval($_GET['id'] ?? 0);

// Get note
$stmt = $conn->prepare("SELECT n.*, s.symbol, s.company_name FROM stock_notes n JOIN stock_companies s ON n.company_id = s.company_id WHERE n.note_id = ? AND n.user_id = ?");
$stmt->bind_param("ii", $note_id, $user_id);
$stmt->execute();
$note = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$note) {
    setFlashMessage('error', 'Note not found.');
    header('Location: ../watchlist/');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('error', 'Invalid request.');
        header('Location: edit.php?id=' . $note_id);
        exit();
    }
    
    $title = sanitize($_POST['note_title']);
    $content = sanitize($_POST['note_content']);
    
    if (empty($title) || empty($content)) {
        setFlashMessage('error', 'Title and content are required.');
    } else {
        $stmt = $conn->prepare("UPDATE stock_notes SET note_title = ?, note_content = ? WHERE note_id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $title, $content, $note_id, $user_id);
        
        if ($stmt->execute()) {
            logActivity($user_id, 'Note Updated', 'Updated note for ' . $note['symbol']);
            setFlashMessage('success', 'Note updated successfully!');
            header('Location: index.php?stock=' . $note['company_id']);
            exit();
        } else {
            setFlashMessage('error', 'Failed to update note.');
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
                    <h4 class="mb-0">Edit Note for <?php echo htmlspecialchars($note['symbol']); ?></h4>
                    <small class="text-muted"><?php echo htmlspecialchars($note['company_name']); ?></small>
                </div>
                <div class="card-body">
                    <form action="" method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="mb-3">
                            <label for="note_title" class="form-label">Note Title *</label>
                            <input type="text" class="form-control" id="note_title" name="note_title" value="<?php echo htmlspecialchars($note['note_title']); ?>" required maxlength="255">
                            <div class="invalid-feedback">Please enter a title.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="note_content" class="form-label">Note Content *</label>
                            <textarea class="form-control" id="note_content" name="note_content" rows="8" required><?php echo htmlspecialchars($note['note_content']); ?></textarea>
                            <div class="invalid-feedback">Please enter note content.</div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Note
                            </button>
                            <a href="index.php?stock=<?php echo $note['company_id']; ?>" class="btn btn-outline-secondary">
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
