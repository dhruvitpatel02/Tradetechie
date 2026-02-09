<?php
/**
 * Stock API Wrapper
 * Fetches stock data from free API (Yahoo Finance alternative)
 */

class StockAPI {
    private $cache_duration = 300; // 5 minutes
    
    /**
     * Get stock quote from database cache
     */
    public function getStockQuote($symbol) {
        global $conn;
        
        $stmt = $conn->prepare("SELECT * FROM stock_companies WHERE symbol = ?");
        $stmt->bind_param("s", $symbol);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Search stocks by symbol or name
     */
    public function searchStocks($query) {
        global $conn;
        
        $search = "%{$query}%";
        $stmt = $conn->prepare("
            SELECT * FROM stock_companies 
            WHERE symbol LIKE ? OR company_name LIKE ? 
            LIMIT 10
        ");
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get all stocks
     */
    public function getAllStocks($limit = 50) {
        global $conn;
        
        $stmt = $conn->prepare("SELECT * FROM stock_companies ORDER BY company_name ASC LIMIT ?");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Update stock price (simulated - in production, call real API)
     */
    public function updateStockPrice($symbol, $price, $change_percent) {
        global $conn;
        
        $stmt = $conn->prepare("
            UPDATE stock_companies 
            SET last_price = ?, change_percent = ?, last_updated = NOW() 
            WHERE symbol = ?
        ");
        $stmt->bind_param("dds", $price, $change_percent, $symbol);
        
        return $stmt->execute();
    }
    
    /**
     * Get historical data (simulated)
     */
    public function getHistoricalData($symbol, $days = 30) {
        $data = [];
        $basePrice = 1000;
        
        for ($i = $days; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $price = $basePrice + rand(-50, 50);
            $data[] = [
                'date' => $date,
                'price' => $price
            ];
        }
        
        return $data;
    }
}
?>
