-- ============================================
-- STOCK MARKET LEARNING & TRADING PLATFORM
-- DATABASE SCHEMA - PHASE 1
-- ============================================

CREATE DATABASE IF NOT EXISTS tradetechie_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tradetechie_db;

-- ============================================
-- USERS TABLE
-- ============================================
CREATE TABLE users (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- EDUCATIONAL CONTENT TABLE
-- ============================================
CREATE TABLE educational_content (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- USER SESSIONS TABLE (for security)
-- ============================================
CREATE TABLE user_sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_session_token (session_token),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- ACTIVITY LOG TABLE
-- ============================================
CREATE TABLE activity_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- INSERT DEFAULT ADMIN USER
-- Password: Admin@123 (hashed with PASSWORD_DEFAULT)
-- ============================================
INSERT INTO users (full_name, email, password, user_type, status) VALUES
('Admin User', 'admin@tradetechie.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');

-- ============================================
-- INSERT SAMPLE EDUCATIONAL CONTENT
-- ============================================
INSERT INTO educational_content (title, slug, category, content, meta_description, order_position, created_by) VALUES
('Introduction to Stock Market', 'introduction-to-stock-market', 'basics', 
'<h2>What is Stock Market?</h2>
<p>The stock market is a platform where shares of publicly listed companies are bought and sold. It serves as a marketplace connecting buyers and sellers of stocks, which represent ownership claims on businesses.</p>

<h3>Key Components:</h3>
<ul>
    <li><strong>Stocks/Shares:</strong> Units of ownership in a company</li>
    <li><strong>Exchanges:</strong> Platforms where trading happens (NSE, BSE)</li>
    <li><strong>Investors:</strong> People who buy and hold stocks</li>
    <li><strong>Traders:</strong> People who frequently buy and sell for short-term profits</li>
</ul>

<h3>Why Stock Market Exists?</h3>
<p>Companies need capital to grow their business. Instead of taking loans, they can sell shares to the public and raise funds. Investors buy these shares hoping the company will grow and share prices will increase.</p>

<h3>How Does It Work?</h3>
<p>When you buy a share, you become a partial owner of that company. If the company performs well, the share price increases, and you can sell it at a profit. If it performs poorly, you may incur losses.</p>', 
'Learn the basics of stock market, how it works, and why it exists', 1, 1),

('NSE vs BSE: Understanding Indian Stock Exchanges', 'nse-vs-bse', 'basics',
'<h2>NSE (National Stock Exchange)</h2>
<p>Founded in 1992, NSE is India\'s largest stock exchange by trading volume. It introduced electronic trading in India and is known for its benchmark index - NIFTY 50.</p>

<h3>Key Features of NSE:</h3>
<ul>
    <li>Fully automated electronic trading</li>
    <li>NIFTY 50 index (top 50 companies)</li>
    <li>Higher liquidity and trading volume</li>
    <li>More modern infrastructure</li>
</ul>

<h2>BSE (Bombay Stock Exchange)</h2>
<p>Established in 1875, BSE is Asia\'s oldest stock exchange. Its benchmark index is SENSEX, which tracks 30 well-established companies.</p>

<h3>Key Features of BSE:</h3>
<ul>
    <li>Oldest stock exchange in Asia</li>
    <li>SENSEX index (top 30 companies)</li>
    <li>More listed companies (5000+)</li>
    <li>Rich historical legacy</li>
</ul>

<h3>Key Differences:</h3>
<table class="table table-bordered">
    <tr>
        <th>Parameter</th>
        <th>NSE</th>
        <th>BSE</th>
    </tr>
    <tr>
        <td>Founded</td>
        <td>1992</td>
        <td>1875</td>
    </tr>
    <tr>
        <td>Benchmark Index</td>
        <td>NIFTY 50</td>
        <td>SENSEX</td>
    </tr>
    <tr>
        <td>Liquidity</td>
        <td>Higher</td>
        <td>Lower</td>
    </tr>
    <tr>
        <td>Listed Companies</td>
        <td>~2000</td>
        <td>~5000</td>
    </tr>
</table>',
'Compare NSE and BSE - India\'s two major stock exchanges', 2, 1),

('Understanding Shares, IPO, and Stock Indices', 'shares-ipo-indices', 'basics',
'<h2>What are Shares?</h2>
<p>Shares represent units of ownership in a company. When you buy shares, you become a shareholder and own a portion of that company.</p>

<h3>Types of Shares:</h3>
<ul>
    <li><strong>Equity Shares:</strong> Common shares with voting rights</li>
    <li><strong>Preference Shares:</strong> Fixed dividend, no voting rights</li>
</ul>

<h2>What is an IPO?</h2>
<p><strong>IPO (Initial Public Offering)</strong> is when a private company offers its shares to the public for the first time. This process is called "going public".</p>

<h3>IPO Process:</h3>
<ol>
    <li>Company files documents with SEBI (Securities and Exchange Board of India)</li>
    <li>Sets IPO price range</li>
    <li>Opens for public subscription</li>
    <li>Shares are allotted to investors</li>
    <li>Stock starts trading on exchanges</li>
</ol>

<h2>Stock Market Indices</h2>
<p>An index is a statistical measure that tracks the performance of a group of stocks. It helps investors understand overall market trends.</p>

<h3>Major Indian Indices:</h3>
<ul>
    <li><strong>SENSEX:</strong> BSE\'s index of 30 top companies</li>
    <li><strong>NIFTY 50:</strong> NSE\'s index of 50 top companies</li>
    <li><strong>NIFTY Bank:</strong> Tracks banking sector stocks</li>
    <li><strong>NIFTY IT:</strong> Tracks IT sector stocks</li>
</ul>

<h3>Why Indices Matter?</h3>
<p>Indices help you understand if the overall market is going up or down. If NIFTY is up 2%, it means on average, the top 50 companies have gained 2% in value.</p>',
'Learn about shares, IPO process, and stock market indices like SENSEX and NIFTY', 3, 1);
