<?php
$page_title = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

// Require login
requireLogin();

// Get user data
$user = getUserById($_SESSION['user_id']);
$content_counts = countContentByCategory();

// Get watchlist count
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM user_watchlist WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$watchlist_count = $stmt->fetch()['count'];

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM stock_notes WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$notes_count = $stmt->fetch()['count'];
?>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Welcome back, <?php echo htmlspecialchars($user['full_name']); ?>! 👋</h2>
        </div>
    </div>
    
    <!-- Dashboard Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card dashboard-card" style="background: var(--color-accent); color: white; border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Learning Progress</h6>
                            <h3>0%</h3>
                            <small>Complete your first module</small>
                        </div>
                        <i class="bi bi-book stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card dashboard-card" style="background: var(--color-success); color: white; border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Virtual Balance</h6>
                            <h3>₹0</h3>
                            <small>Coming in Phase 3</small>
                        </div>
                        <i class="bi bi-wallet2 stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card dashboard-card" style="background: var(--color-info); color: white; border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Portfolio Value</h6>
                            <h3>₹0</h3>
                            <small>Start trading soon</small>
                        </div>
                        <i class="bi bi-pie-chart stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card dashboard-card" style="background: var(--color-warning); color: white; border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Watchlist</h6>
                            <h3><?php echo $watchlist_count; ?></h3>
                            <small>Stocks tracked</small>
                        </div>
                        <i class="bi bi-star stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-lightning-charge"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="<?php echo SITE_URL; ?>learn/" class="btn btn-outline-primary w-100">
                                <i class="bi bi-book"></i><br>Start Learning
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?php echo SITE_URL; ?>watchlist/" class="btn btn-outline-success w-100">
                                <i class="bi bi-star"></i><br>My Watchlist
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?php echo SITE_URL; ?>watchlist/" class="btn btn-outline-info w-100">
                                <i class="bi bi-journal-text"></i><br>Stock Notes (<?php echo $notes_count; ?>)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Learning Modules -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-mortarboard"></i> Continue Learning</h5>
                </div>
                <div class="card-body">
                    <?php
                    $categories = [
                        'basics' => ['name' => 'Basics', 'icon' => 'book', 'color' => 'primary'],
                        'fundamental' => ['name' => 'Fundamental Analysis', 'icon' => 'calculator', 'color' => 'info'],
                        'technical' => ['name' => 'Technical Analysis', 'icon' => 'graph-up', 'color' => 'warning'],
                        'advanced' => ['name' => 'Advanced Trading', 'icon' => 'trophy', 'color' => 'success']
                    ];
                    
                    foreach ($categories as $key => $cat):
                        $count = $content_counts[$key] ?? 0;
                    ?>
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                        <div>
                            <h6 class="mb-1">
                                <i class="bi bi-<?php echo $cat['icon']; ?> text-<?php echo $cat['color']; ?>"></i>
                                <?php echo $cat['name']; ?>
                            </h6>
                            <small class="text-muted"><?php echo $count; ?> modules available</small>
                        </div>
                        <a href="<?php echo SITE_URL; ?>learn/?category=<?php echo $key; ?>" class="btn btn-sm btn-outline-<?php echo $cat['color']; ?>">
                            Start <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-person-circle"></i> Account Info</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong><br><?php echo htmlspecialchars($user['full_name']); ?></p>
                    <p><strong>Email:</strong><br><?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Account Type:</strong><br>
                        <span class="badge badge-primary">
                            <?php echo ucfirst($user['user_type']); ?>
                        </span>
                    </p>
                    <p><strong>Member Since:</strong><br><?php echo formatDate($user['created_at']); ?></p>
                    <hr>
                    <div class="alert alert-info">
                        <small><i class="bi bi-info-circle"></i> Phase 1 is live! More features coming soon in Phase 2 & 3.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
