<?php
require_once __DIR__ . '/../includes/header.php';

// Get slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header('Location: index.php');
    exit();
}

// Get content by slug
$content = getContentBySlug($slug);

if (!$content) {
    header('Location: index.php');
    exit();
}

$page_title = $content['title'];
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Learn</a></li>
                    <li class="breadcrumb-item active"><?php echo htmlspecialchars($content['title']); ?></li>
                </ol>
            </nav>
            
            <!-- Content Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-<?php 
                            echo $content['category'] === 'basics' ? 'primary' : 
                                ($content['category'] === 'fundamental' ? 'info' : 
                                ($content['category'] === 'technical' ? 'warning' : 'success')); 
                        ?>">
                            <?php echo ucfirst($content['category']); ?>
                        </span>
                        <small class="text-muted">
                            <i class="bi bi-eye"></i> <?php echo $content['views']; ?> views
                        </small>
                    </div>
                    
                    <h1 class="mb-3"><?php echo htmlspecialchars($content['title']); ?></h1>
                    
                    <div class="text-muted">
                        <i class="bi bi-calendar"></i> Published on <?php echo formatDate($content['created_at']); ?>
                        <?php if ($content['updated_at'] !== $content['created_at']): ?>
                            | <i class="bi bi-pencil"></i> Updated on <?php echo formatDate($content['updated_at']); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Content Body -->
            <div class="card">
                <div class="card-body content-display">
                    <?php echo $content['content']; ?>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="card-footer">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <button class="btn btn-success" data-content="<?php echo $content['content_id']; ?>" onclick="markComplete(<?php echo $content['content_id']; ?>)">
                        <i class="bi bi-check-circle"></i> Mark as Complete
                    </button>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Navigation -->
            <div class="d-flex justify-content-between mt-4">
                <a href="index.php" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back to All Modules
                </a>
                
                <?php if (isLoggedIn()): ?>
                    <a href="<?php echo SITE_URL; ?>dashboard.php" class="btn btn-primary">
                        Go to Dashboard <i class="bi bi-arrow-right"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>auth/register.php" class="btn btn-success">
                        Register to Track Progress <i class="bi bi-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
