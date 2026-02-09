<?php
$page_title = 'Learn Stock Market';
require_once __DIR__ . '/../includes/header.php';

// Get category filter
$category_filter = $_GET['category'] ?? null;

// Get user progress
$completed = [];
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT content_id FROM user_progress WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $completed[] = $row['content_id'];
    }
    $stmt->close();
}

// Get all content
$all_content = getAllContent($category_filter);

// Category names
$categories = [
    'basics' => 'Basics',
    'fundamental' => 'Fundamental Analysis',
    'technical' => 'Technical Analysis',
    'advanced' => 'Advanced Trading'
];
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-3">
                <i class="bi bi-book"></i> 
                <?php echo $category_filter ? $categories[$category_filter] : 'All Learning Modules'; ?>
            </h2>
            <p class="text-muted">Master stock market trading with our comprehensive learning modules</p>
        </div>
    </div>
    
    <!-- Category Filter -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="btn-group" role="group">
                <a href="index.php" class="btn btn-<?php echo !$category_filter ? 'primary' : 'outline-primary'; ?>">
                    All Modules
                </a>
                <?php foreach ($categories as $key => $name): ?>
                    <a href="?category=<?php echo $key; ?>" class="btn btn-<?php echo $category_filter === $key ? 'primary' : 'outline-primary'; ?>">
                        <?php echo $name; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="col-md-4">
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo count($all_content) > 0 ? (count($completed) / count($all_content)) * 100 : 0; ?>%"></div>
            </div>
            <small class="text-muted"><?php echo count($completed); ?> of <?php echo count($all_content); ?> completed</small>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Content List -->
    <div class="row">
        <?php if (empty($all_content)): ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No learning modules available in this category yet.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($all_content as $content): ?>
                <div class="col-md-6 mb-4">
                    <div class="card learning-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-<?php 
                                    echo $content['category'] === 'basics' ? 'primary' : 
                                        ($content['category'] === 'fundamental' ? 'info' : 
                                        ($content['category'] === 'technical' ? 'warning' : 'success')); 
                                ?> category-badge">
                                    <?php echo ucfirst($content['category']); ?>
                                </span>
                                <div>
                                    <small class="text-muted">
                                        <i class="bi bi-eye"></i> <?php echo $content['views']; ?> views
                                    </small>
                                    <?php if (in_array($content['content_id'], $completed)): ?>
                                        <span class="completed-badge ms-2">✓ Completed</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <h5 class="card-title"><?php echo htmlspecialchars($content['title']); ?></h5>
                            <p class="card-text text-muted">
                                <?php echo htmlspecialchars($content['meta_description']); ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> <?php echo formatDate($content['created_at']); ?>
                                </small>
                                <a href="view.php?slug=<?php echo $content['slug']; ?>" class="btn btn-sm btn-primary">
                                    Read More <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
