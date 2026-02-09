<?php
$page_title = 'Add Content';
require_once __DIR__ . '/../includes/header.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('error', 'Invalid request.');
        header('Location: add_content.php');
        exit();
    }
    
    $conn = db();
    if (!$conn) {
        setFlashMessage('error', 'Service temporarily unavailable.');
        header('Location: add_content.php');
        exit();
    }
    
    // Sanitize inputs
    $title = sanitize($_POST['title']);
    $category = sanitize($_POST['category']);
    $content = $_POST['content']; // Don't sanitize HTML content
    $meta_description = sanitize($_POST['meta_description']);
    $order_position = intval($_POST['order_position']);
    $status = sanitize($_POST['status']);
    
    // Generate slug
    $slug = createSlug($title);
    
    // Check if slug already exists
    $stmt = $conn->prepare("SELECT content_id FROM educational_content WHERE slug = ?");
    $stmt->execute([$slug]);
    
    if ($stmt->rowCount() > 0) {
        $slug = $slug . '-' . time();
    }
    
    // Insert content
    $stmt = $conn->prepare("INSERT INTO educational_content (title, slug, category, content, meta_description, order_position, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$title, $slug, $category, $content, $meta_description, $order_position, $status, $_SESSION['user_id']])) {
        logActivity($_SESSION['user_id'], 'Content Created', 'Created content: ' . $title);
        setFlashMessage('success', 'Content created successfully!');
        header('Location: content_manage.php');
    } else {
        setFlashMessage('error', 'Failed to create content.');
        header('Location: add_content.php');
    }
    
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
                <a class="nav-link" href="content_manage.php">
                    <i class="bi bi-file-text"></i> Manage Content
                </a>
                <a class="nav-link active" href="add_content.php">
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
            <h2 class="mb-4"><i class="bi bi-plus-circle"></i> Add New Content</h2>
            
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title *</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                    <div class="invalid-feedback">Please enter a title.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content * (HTML Allowed)</label>
                                    <textarea class="form-control" id="content" name="content" rows="15" required></textarea>
                                    <small class="text-muted">You can use HTML tags for formatting.</small>
                                    <div class="invalid-feedback">Please enter content.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2" maxlength="255"></textarea>
                                    <small class="text-muted">Brief description for SEO (max 255 characters)</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="basics">Basics</option>
                                        <option value="fundamental">Fundamental Analysis</option>
                                        <option value="technical">Technical Analysis</option>
                                        <option value="advanced">Advanced Trading</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a category.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="published">Published</option>
                                        <option value="draft">Draft</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="order_position" class="form-label">Order Position</label>
                                    <input type="number" class="form-control" id="order_position" name="order_position" value="0" min="0">
                                    <small class="text-muted">Lower numbers appear first</small>
                                </div>
                                
                                <hr>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Create Content
                                    </button>
                                    <a href="content_manage.php" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle"></i> Cancel
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
