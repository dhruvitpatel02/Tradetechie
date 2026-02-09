<?php
$page_title = 'Manage Content';
require_once __DIR__ . '/../includes/header.php';

// Require admin access
requireAdmin();

// Get category filter
$category_filter = $_GET['category'] ?? null;

// Get all content (including drafts for admin)
if ($category_filter) {
    $stmt = $conn->prepare("SELECT * FROM educational_content WHERE category = ? ORDER BY order_position ASC, created_at DESC");
    $stmt->bind_param("s", $category_filter);
    $stmt->execute();
    $result = $stmt->get_result();
    $all_content = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM educational_content ORDER BY order_position ASC, created_at DESC");
    $all_content = $result->fetch_all(MYSQLI_ASSOC);
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-file-text"></i> Manage Content</h2>
                <a href="add_content.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add New Content
                </a>
            </div>
            
            <!-- Category Filter -->
            <div class="mb-4">
                <div class="btn-group" role="group">
                    <a href="content_manage.php" class="btn btn-<?php echo !$category_filter ? 'primary' : 'outline-primary'; ?>">
                        All
                    </a>
                    <a href="?category=basics" class="btn btn-<?php echo $category_filter === 'basics' ? 'primary' : 'outline-primary'; ?>">
                        Basics
                    </a>
                    <a href="?category=fundamental" class="btn btn-<?php echo $category_filter === 'fundamental' ? 'primary' : 'outline-primary'; ?>">
                        Fundamental
                    </a>
                    <a href="?category=technical" class="btn btn-<?php echo $category_filter === 'technical' ? 'primary' : 'outline-primary'; ?>">
                        Technical
                    </a>
                    <a href="?category=advanced" class="btn btn-<?php echo $category_filter === 'advanced' ? 'primary' : 'outline-primary'; ?>">
                        Advanced
                    </a>
                </div>
            </div>
            
            <!-- Content Table -->
            <div class="card">
                <div class="card-body">
                    <?php if (empty($all_content)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No content available. <a href="add_content.php">Add your first content</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Views</th>
                                        <th>Order</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($all_content as $content): ?>
                                        <tr>
                                            <td><?php echo $content['content_id']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($content['title']); ?></strong><br>
                                                <small class="text-muted"><?php echo $content['slug']; ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $content['category'] === 'basics' ? 'primary' : 
                                                        ($content['category'] === 'fundamental' ? 'info' : 
                                                        ($content['category'] === 'technical' ? 'warning' : 'success')); 
                                                ?>">
                                                    <?php echo ucfirst($content['category']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $content['status'] === 'published' ? 'success' : 'secondary'; ?>">
                                                    <?php echo ucfirst($content['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo $content['views']; ?></td>
                                            <td><?php echo $content['order_position']; ?></td>
                                            <td><?php echo formatDate($content['created_at']); ?></td>
                                            <td>
                                                <a href="edit_content.php?id=<?php echo $content['content_id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?php echo SITE_URL; ?>learn/view.php?slug=<?php echo $content['slug']; ?>" class="btn btn-sm btn-outline-info" title="View" target="_blank">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="delete_content.php?id=<?php echo $content['content_id']; ?>" class="btn btn-sm btn-outline-danger btn-delete" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
