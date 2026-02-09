<?php
$page_title = 'Home';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1>Master the Stock Market</h1>
        <p>Learn, Practice, and Trade with Confidence</p>
        <div class="mt-4">
            <?php if (!isLoggedIn()): ?>
                <a href="<?php echo SITE_URL; ?>auth/register.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-rocket-takeoff"></i> Get Started Free
                </a>
                <a href="<?php echo SITE_URL; ?>learn/" class="btn btn-light btn-lg">
                    <i class="bi bi-book"></i> Start Learning
                </a>
            <?php else: ?>
                <a href="<?php echo SITE_URL; ?>dashboard.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-speedometer2"></i> Go to Dashboard
                </a>
                <a href="<?php echo SITE_URL; ?>learn/" class="btn btn-light btn-lg">
                    <i class="bi bi-book"></i> Continue Learning
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Why Choose TradeTechie?</h2>
            <p class="text-muted">Everything you need to become a successful trader</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-book feature-icon"></i>
                        <h4 class="card-title">Comprehensive Learning</h4>
                        <p class="card-text">From basics to advanced strategies, learn everything about stock market trading with easy-to-understand modules.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-graph-up-arrow feature-icon"></i>
                        <h4 class="card-title">Real-Time Data</h4>
                        <p class="card-text">Access live stock prices, market trends, and company information from NSE and BSE exchanges.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-wallet2 feature-icon"></i>
                        <h4 class="card-title">Virtual Trading</h4>
                        <p class="card-text">Practice trading with virtual money in a risk-free environment before investing real capital.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-bar-chart feature-icon"></i>
                        <h4 class="card-title">Technical Analysis</h4>
                        <p class="card-text">Learn and apply technical indicators like RSI, MACD, and Moving Averages with interactive charts.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-pie-chart feature-icon"></i>
                        <h4 class="card-title">Portfolio Management</h4>
                        <p class="card-text">Track your investments, analyze performance, and optimize your portfolio with detailed analytics.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-shield-check feature-icon"></i>
                        <h4 class="card-title">100% Safe</h4>
                        <p class="card-text">Your data is secure with us. Practice trading without any financial risk or commitment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Learning Path Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Your Learning Path</h2>
            <p class="text-muted">Structured curriculum from beginner to expert</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card h-100 border-primary">
                    <div class="card-body text-center">
                        <div class="badge bg-primary mb-3">Level 1</div>
                        <h5 class="card-title">Basics</h5>
                        <p class="card-text">Stock Market Fundamentals, NSE vs BSE, Shares, IPO, Indices</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card h-100 border-info">
                    <div class="card-body text-center">
                        <div class="badge bg-info mb-3">Level 2</div>
                        <h5 class="card-title">Fundamental Analysis</h5>
                        <p class="card-text">Financial Statements, Ratios, Company Valuation</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card h-100 border-warning">
                    <div class="card-body text-center">
                        <div class="badge bg-warning mb-3">Level 3</div>
                        <h5 class="card-title">Technical Analysis</h5>
                        <p class="card-text">Charts, Patterns, Indicators, Trading Strategies</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card h-100 border-success">
                    <div class="card-body text-center">
                        <div class="badge bg-success mb-3">Level 4</div>
                        <h5 class="card-title">Advanced Trading</h5>
                        <p class="card-text">Options, Futures, Risk Management, Portfolio Optimization</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?php echo SITE_URL; ?>learn/" class="btn btn-primary btn-lg">
                <i class="bi bi-play-circle"></i> Start Learning Now
            </a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
                <h2><?php echo getTotalUsers(); ?>+</h2>
                <p class="text-muted">Active Learners</p>
            </div>
            <div class="col-md-3">
                <h2><?php echo array_sum(countContentByCategory()); ?>+</h2>
                <p class="text-muted">Learning Modules</p>
            </div>
            <div class="col-md-3">
                <h2>500+</h2>
                <p class="text-muted">Stocks Listed</p>
            </div>
            <div class="col-md-3">
                <h2>24/7</h2>
                <p class="text-muted">Platform Access</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<?php if (!isLoggedIn()): ?>
<section class="py-5" style="background: var(--color-primary); color: white;">
    <div class="container text-center">
        <h2 style="color: white; margin-bottom: 1rem;">Ready to Start Your Trading Journey?</h2>
        <p style="font-size: 1.125rem; margin-bottom: 1.5rem;">Join thousands of learners and become a confident trader</p>
        <a href="<?php echo SITE_URL; ?>auth/register.php" class="btn btn-light btn-lg">
            <i class="bi bi-rocket-takeoff"></i> Create Free Account
        </a>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
