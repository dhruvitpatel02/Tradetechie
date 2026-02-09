<?php
$page_title = 'Edit Content';
require_once __DIR__ . '/../includes/header.php';

// Require admin access
requireAdmin();

// Get content ID
$content_id = intval($_GET['id'] ?? 0);

if ($content_id === 0) {
    header('Location: content_manage.php');
    exit();
}

// Get content
$content = getContentById($content_id);

if (!$content) {
    setFlashMessage('error', 'Content not found.');
    header('Location: content_manage.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('error', 'Invalid request.');
        header('Location: edit_content.php?id=' . $content_id);
        exit();
    }
    
    // Sanitize inputs
    $title = sanitize($_POST['title']);
    $category = sanitize($_POST['category']);
    $content_text = $_POST['content']; // Don't sanitize HTML content
    $meta_description = sanitize($_POST['meta_description']);
    $order_position = intval($_POST['order_position']);
    $status = sanitize($_POST['status']);
    
    // Generate slug if title changed
    $slug = $content['slug'];
    if ($title !== $content['title']) {
        $slug = createSlug($title);
        
        // Check if slug already exists
        $stmt = $conn->prepare("SELECT content_id FROM educational_content WHERE slug = ? AND content_id != ?");
        $stmt->bind_param("si", $slug, $content_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $slug = $slug . '-' . time();
        }
        $stmt->close();
    }
    
    // Update content
    $stmt = $conn->prepare("UPDATE educational_content SET title = ?, slug = ?, category = ?, content = ?, meta_description = ?, order_position = ?, status = ? WHERE content_id = ?");
    $stmt->bind_param("sssssisi", $title, $slug, $category, $content_text, $meta_description, $order_position, $status, $content_id);
    
    if ($stmt->execute()) {
        logActivity($_SESSION['user_id'], 'Content Updated', 'Updated content: ' . $title);
        setFlashMessage('success', 'Content updated successfully!');
        header('Location: content_manage.php');
    } else {
        setFlashMessage('error', 'Failed to update content.');
        header('Location: edit_content.php?id=' . $content_id);
    }
    
    $stmt->close();
    exit();
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 admin-sidebar">
            <nav class="nav flex-column">
                <a class="nav-link" href="index.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link active" href="content_manage.php">
                    <i class="bi bi-file-text"></i> Manage Content
                </a>
                <a class="nav-link" href="add_content.php">
                    <i class="bi bi-plus-circle"></i> Add Content
                </a>
                <hr class="bg-secondary">
                <a class="nav-link" href="<?php echo SITE_URL; ?>dashboard.php">
                    <i class="bi bi-arrow-left"></i> Back to Site
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <h2 class="mb-4"><i class="bi bi-pencil"></i> Edit Content</h2>
            
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title *</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($content['title']); ?>" required>
                                    <div class="invalid-feedback">Please enter a title.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content * (HTML Allowed)</label>
                                    <textarea class="form-control" id="content" name="content" rows="15" required><?php echo htmlspecialchars($content['content']); ?></textarea>
                                    <small class="text-muted">You can use HTML tags for formatting.</small>
                                    <div class="invalid-feedback">Please enter content.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2" maxlength="255"><?php echo htmlspecialchars($content['meta_description']); ?></textarea>
                                    <small class="text-muted">Brief description for SEO (max 255 characters)</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="basics" <?php echo $content['category'] === 'basics' ? 'selected' : ''; ?>>Basics</option>
                                        <option value="fundamental" <?php echo $content['category'] === 'fundamental' ? 'selected' : ''; ?>>Fundamental Analysis</option>
                                        <option value="technical" <?php echo $content['category'] === 'technical' ? 'selected' : ''; ?>>Technical Analysis</option>
                                        <option value="advanced" <?php echo $content['category'] === 'advanced' ? 'selected' : ''; ?>>Advanced Trading</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a category.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="published" <?php echo $content['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                                        <option value="draft" <?php echo $content['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="order_position" class="form-label">Order Position</label>
                                    <input type="number" class="form-control" id="order_position" name="order_position" value="<?php echo $content['order_position']; ?>" min="0">
                                    <small class="text-muted">Lower numbers appear first</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Info</label>
                                    <p class="small text-muted mb-1">Views: <?php echo $content['views']; ?></p>
                                    <p class="small text-muted mb-1">Created: <?php echo formatDate($content['created_at']); ?></p>
                                    <p class="small text-muted mb-0">Updated: <?php echo formatDate($content['updated_at']); ?></p>
                                </div>
                                
                                <hr>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Update Content
                                    </button>
                                    <a href="content_manage.php" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </a>
                                    <a href="<?php echo SITE_URL; ?>learn/view.php?slug=<?php echo $content['slug']; ?>" class="btn btn-outline-info" target="_blank">
                                        <i class="bi bi-eye"></i> Preview
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
