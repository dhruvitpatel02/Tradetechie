-- ============================================
-- EXTENDED DATABASE SCHEMA - WATCHLIST & NOTES
-- Add these tables to existing database
-- ============================================

USE tradetechie_db;

-- ============================================
-- STOCK COMPANIES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS stock_companies (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- USER WATCHLIST TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS user_watchlist (
    watchlist_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES stock_companies(company_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_stock (user_id, company_id),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- STOCK NOTES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS stock_notes (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- INSERT SAMPLE STOCK COMPANIES
-- ============================================
INSERT INTO stock_companies (symbol, company_name, exchange, sector, last_price, change_percent) VALUES
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
('TATAMOTORS', 'Tata Motors Ltd', 'NSE', 'Automobile', 620.75, 2.10);
