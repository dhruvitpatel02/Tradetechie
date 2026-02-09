<?php
/**
 * Automatic Database Installer
 * Run this file once to setup everything
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tradetechie_db');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Installer - TradeTechie</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid green; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid red; margin: 10px 0; }
        .info { color: blue; padding: 10px; background: #d1ecf1; border: 1px solid blue; margin: 10px 0; }
        h1 { color: #333; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>🚀 TradeTechie Database Installer</h1>";

try {
    // Connect to MySQL (without database)
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "<div class='info'>✓ Connected to MySQL server</div>";
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if ($conn->query($sql)) {
        echo "<div class='success'>✓ Database '" . DB_NAME . "' created successfully</div>";
    }
    
    // Select database
    $conn->select_db(DB_NAME);
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(15),
        user_type ENUM('user', 'admin') DEFAULT 'user',
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_email (email),
        INDEX idx_user_type (user_type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql)) {
        echo "<div class='success'>✓ Table 'users' created</div>";
    }
    
    // Create educational_content table
    $sql = "CREATE TABLE IF NOT EXISTS educational_content (
        content_id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) UNIQUE NOT NULL,
        category ENUM('basics', 'fundamental', 'technical', 'advanced') DEFAULT 'basics',
        content TEXT NOT NULL,
        meta_description VARCHAR(255),
        order_position INT DEFAULT 0,
        status ENUM('published', 'draft') DEFAULT 'published',
        views INT DEFAULT 0,
        created_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL,
        INDEX idx_slug (slug),
        INDEX idx_category (category),
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql)) {
        echo "<div class='success'>✓ Table 'educational_content' created</div>";
    }
    
    // Create user_sessions table
    $sql = "CREATE TABLE IF NOT EXISTS user_sessions (
        session_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        session_token VARCHAR(255) NOT NULL,
        ip_address VARCHAR(45),
        user_agent VARCHAR(255),
        last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
        INDEX idx_session_token (session_token),
        INDEX idx_user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql)) {
        echo "<div class='success'>✓ Table 'user_sessions' created</div>";
    }
    
    // Create activity_log table
    $sql = "CREATE TABLE IF NOT EXISTS activity_log (
        log_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        action VARCHAR(100) NOT NULL,
        description TEXT,
        ip_address VARCHAR(45),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
        INDEX idx_user_id (user_id),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql)) {
        echo "<div class='success'>✓ Table 'activity_log' created</div>";
    }
    
    // Create stock_companies table
    $sql = "CREATE TABLE IF NOT EXISTS stock_companies (
        company_id INT AUTO_INCREMENT PRIMARY KEY,
        symbol VARCHAR(20) UNIQUE NOT NULL,
        company_name VARCHAR(255) NOT NULL,
        exchange ENUM('NSE', 'BSE') DEFAULT 'NSE',
        sector VARCHAR(100),
        last_price DECIMAL(10,2),
        change_percent DECIMAL(5,2),
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_symbol (symbol),
        INDEX idx_company_name (company_name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql)) {
        echo "<div class='success'>✓ Table 'stock_companies' created</div>";
    }
    
    // Create user_watchlist table
    $sql = "CREATE TABLE IF NOT EXISTS user_watchlist (
        watchlist_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        company_id INT NOT NULL,
        added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
        FOREIGN KEY (company_id) REFERENCES stock_companies(company_id) ON DELETE CASCADE,
        UNIQUE KEY unique_user_stock (user_id, company_id),
        INDEX idx_user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql)) {
        echo "<div class='success'>✓ Table 'user_watchlist' created</div>";
    }
    
    // Create stock_notes table
    $sql = "CREATE TABLE IF NOT EXISTS stock_notes (
        note_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        company_id INT NOT NULL,
        note_title VARCHAR(255) NOT NULL,
        note_content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
        FOREIGN KEY (company_id) REFERENCES stock_companies(company_id) ON DELETE CASCADE,
        INDEX idx_user_company (user_id, company_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql)) {
        echo "<div class='success'>✓ Table 'stock_notes' created</div>";
    }
    
    // Insert admin user
    $check = $conn->query("SELECT user_id FROM users WHERE email = 'admin@tradetechie.com'");
    if ($check->num_rows == 0) {
        $sql = "INSERT INTO users (full_name, email, password, user_type, status) VALUES
        ('Admin User', 'admin@tradetechie.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active')";
        
        if ($conn->query($sql)) {
            echo "<div class='success'>✓ Admin user created (admin@tradetechie.com / Admin@123)</div>";
        }
    } else {
        echo "<div class='info'>✓ Admin user already exists</div>";
    }
    
    // Insert sample stocks
    $check = $conn->query("SELECT company_id FROM stock_companies LIMIT 1");
    if ($check->num_rows == 0) {
        $sql = "INSERT INTO stock_companies (symbol, company_name, exchange, sector, last_price, change_percent) VALUES
        ('RELIANCE', 'Reliance Industries Ltd', 'NSE', 'Energy', 2450.50, 1.25),
        ('TCS', 'Tata Consultancy Services', 'NSE', 'IT', 3650.75, -0.85),
        ('HDFCBANK', 'HDFC Bank Ltd', 'NSE', 'Banking', 1580.30, 0.45),
        ('INFY', 'Infosys Ltd', 'NSE', 'IT', 1420.60, 1.10),
        ('ICICIBANK', 'ICICI Bank Ltd', 'NSE', 'Banking', 950.25, -0.30),
        ('HINDUNILVR', 'Hindustan Unilever Ltd', 'NSE', 'FMCG', 2680.90, 0.75),
        ('ITC', 'ITC Ltd', 'NSE', 'FMCG', 420.15, 0.50),
        ('SBIN', 'State Bank of India', 'NSE', 'Banking', 580.40, 1.85),
        ('BHARTIARTL', 'Bharti Airtel Ltd', 'NSE', 'Telecom', 890.55, -0.65),
        ('KOTAKBANK', 'Kotak Mahindra Bank', 'NSE', 'Banking', 1750.80, 0.95),
        ('LT', 'Larsen & Toubro Ltd', 'NSE', 'Infrastructure', 2890.45, 1.40),
        ('WIPRO', 'Wipro Ltd', 'NSE', 'IT', 420.30, -0.40),
        ('AXISBANK', 'Axis Bank Ltd', 'NSE', 'Banking', 980.60, 0.80),
        ('MARUTI', 'Maruti Suzuki India Ltd', 'NSE', 'Automobile', 9850.25, 1.60),
        ('TATAMOTORS', 'Tata Motors Ltd', 'NSE', 'Automobile', 620.75, 2.10)";
        
        if ($conn->query($sql)) {
            echo "<div class='success'>✓ Sample stocks inserted (15 companies)</div>";
        }
    } else {
        echo "<div class='info'>✓ Sample stocks already exist</div>";
    }
    
    // Insert sample content
    $check = $conn->query("SELECT content_id FROM educational_content LIMIT 1");
    if ($check->num_rows == 0) {
        $content1 = $conn->real_escape_string('<h2>What is Stock Market?</h2><p>The stock market is a platform where shares of publicly listed companies are bought and sold.</p>');
        $content2 = $conn->real_escape_string('<h2>NSE vs BSE</h2><p>NSE and BSE are India\'s two major stock exchanges.</p>');
        $content3 = $conn->real_escape_string('<h2>Understanding Shares</h2><p>Shares represent units of ownership in a company.</p>');
        
        $sql = "INSERT INTO educational_content (title, slug, category, content, meta_description, order_position, created_by) VALUES
        ('Introduction to Stock Market', 'introduction-to-stock-market', 'basics', '$content1', 'Learn stock market basics', 1, 1),
        ('NSE vs BSE', 'nse-vs-bse', 'basics', '$content2', 'Compare NSE and BSE', 2, 1),
        ('Understanding Shares', 'understanding-shares', 'basics', '$content3', 'Learn about shares', 3, 1)";
        
        if ($conn->query($sql)) {
            echo "<div class='success'>✓ Sample educational content inserted (3 articles)</div>";
        }
    } else {
        echo "<div class='info'>✓ Sample content already exists</div>";
    }
    
    $conn->close();
    
    echo "<div class='success'><h2>🎉 Installation Complete!</h2></div>";
    echo "<div class='info'>
        <strong>Next Steps:</strong><br>
        1. Delete this install.php file for security<br>
        2. Go to homepage: <a href='index.php'>Click Here</a><br>
        3. Login with: admin@tradetechie.com / Admin@123
    </div>";
    echo "<a href='index.php' class='btn'>Go to Homepage</a>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error: " . $e->getMessage() . "</div>";
    echo "<div class='info'>Make sure XAMPP MySQL is running!</div>";
}

echo "</body></html>";
?>
