<?php

class Watchlist extends Model {
    protected $table = 'user_watchlist';
    
    public function getUserWatchlist($userId) {
        $stmt = $this->db->prepare("
            SELECT w.*, s.symbol, s.company_name, s.last_price, s.change_percent, s.sector, s.last_updated
            FROM user_watchlist w
            JOIN stock_companies s ON w.company_id = s.company_id
            WHERE w.user_id = ?
            ORDER BY w.added_at DESC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function add($userId, $companyId) {
        $stmt = $this->db->prepare("INSERT IGNORE INTO user_watchlist (user_id, company_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $companyId);
        return $stmt->execute();
    }
    
    public function remove($userId, $watchlistId) {
        $stmt = $this->db->prepare("DELETE FROM user_watchlist WHERE watchlist_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $watchlistId, $userId);
        return $stmt->execute();
    }
    
    public function exists($userId, $companyId) {
        $stmt = $this->db->prepare("SELECT watchlist_id FROM user_watchlist WHERE user_id = ? AND company_id = ?");
        $stmt->bind_param("ii", $userId, $companyId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
    
    public function getCount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM user_watchlist WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['count'];
    }
}
