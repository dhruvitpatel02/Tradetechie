<?php
$page_title = 'Admin Dashboard';
require_once __DIR__ . '/../includes/header.php';

// Require admin access
requireAdmin();

// Get statistics
$total_users = getTotalUsers();
$content_counts = countContentByCategory();
$total_content = array_sum($content_counts);
$recent_activities = getRecentActivities(10);
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 admin-sidebar">
            <nav class="nav flex-column">
                <a class="nav-link active" href="index.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link" href="content_manage.php">
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
            <h2 class="mb-4"><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
            
            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card dashboard-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-uppercase">Total Users</h6>
                                    <h2><?php echo $total_users; ?></h2>
                                </div>
                                <i class="bi bi-people stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card dashboard-card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-uppercase">Total Content</h6>
                                    <h2><?php echo $total_content; ?></h2>
                                </div>
                                <i class="bi bi-file-text stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card dashboard-card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-uppercase">Basics</h6>
                                    <h2><?php echo $content_counts['basics'] ?? 0; ?></h2>
                                </div>
                                <i class="bi bi-book stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card dashboard-card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-uppercase">Advanced</h6>
                                    <h2><?php echo ($content_counts['technical'] ?? 0) + ($content_counts['advanced'] ?? 0); ?></h2>
                                </div>
                                <i class="bi bi-graph-up stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content by Category -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Content by Category</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Count</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge bg-primary">Basics</span></td>
                                        <td><?php echo $content_counts['basics'] ?? 0; ?></td>
                                        <td><a href="content_manage.php?category=basics" class="btn btn-sm btn-outline-primary">View</a></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-info">Fundamental</span></td>
                                        <td><?php echo $content_counts['fundamental'] ?? 0; ?></td>
                                        <td><a href="content_manage.php?category=fundamental" class="btn btn-sm btn-outline-info">View</a></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-warning">Technical</span></td>
                                        <td><?php echo $content_counts['technical'] ?? 0; ?></td>
                                        <td><a href="content_manage.php?category=technical" class="btn btn-sm btn-outline-warning">View</a></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-success">Advanced</span></td>
                                        <td><?php echo $content_counts['advanced'] ?? 0; ?></td>
                                        <td><a href="content_manage.php?category=advanced" class="btn btn-sm btn-outline-success">View</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activities -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Activities</h5>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            <?php if (empty($recent_activities)): ?>
                                <p class="text-muted">No recent activities</p>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($recent_activities as $activity): ?>
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between">
                                                <strong><?php echo htmlspecialchars($activity['action']); ?></strong>
                                                <small class="text-muted"><?php echo formatDateTime($activity['created_at']); ?></small>
                                            </div>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($activity['full_name'] ?? 'Unknown'); ?> - 
                                                <?php echo htmlspecialchars($activity['description']); ?>
                                            </small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-lightning-charge"></i> Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <a href="add_content.php" class="btn btn-primary me-2">
                                <i class="bi bi-plus-circle"></i> Add New Content
                            </a>
                            <a href="content_manage.php" class="btn btn-outline-primary me-2">
                                <i class="bi bi-list"></i> Manage All Content
                            </a>
                            <a href="<?php echo SITE_URL; ?>learn/" class="btn btn-outline-secondary" target="_blank">
                                <i class="bi bi-eye"></i> View Learning Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
