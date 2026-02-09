<?php

class Stock extends Model {
    protected $table = 'stock_companies';
    
    public function search($query) {
        $search = "%{$query}%";
        $stmt = $this->db->prepare("SELECT * FROM stock_companies WHERE symbol LIKE ? OR company_name LIKE ? LIMIT 10");
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getBySymbol($symbol) {
        $stmt = $this->db->prepare("SELECT * FROM stock_companies WHERE symbol = ?");
        $stmt->bind_param("s", $symbol);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function updatePrice($symbol, $price, $changePercent) {
        $stmt = $this->db->prepare("UPDATE stock_companies SET last_price = ?, change_percent = ?, last_updated = NOW() WHERE symbol = ?");
        $stmt->bind_param("dds", $price, $changePercent, $symbol);
        return $stmt->execute();
    }
    
    public function getTopMovers($limit = 10) {
        $stmt = $this->db->prepare("SELECT * FROM stock_companies ORDER BY ABS(change_percent) DESC LIMIT ?");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getBySector($sector) {
        $stmt = $this->db->prepare("SELECT * FROM stock_companies WHERE sector = ? ORDER BY company_name");
        $stmt->bind_param("s", $sector);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
