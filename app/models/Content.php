<?php

class Content extends Model {
    protected $table = 'educational_content';
    
    public function getByCategory($category, $status = 'published') {
        $stmt = $this->db->prepare("SELECT * FROM educational_content WHERE category = ? AND status = ? ORDER BY order_position ASC");
        $stmt->bind_param("ss", $category, $status);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM educational_content WHERE slug = ? AND status = 'published'");
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function incrementViews($contentId) {
        $stmt = $this->db->prepare("UPDATE educational_content SET views = views + 1 WHERE content_id = ?");
        $stmt->bind_param("i", $contentId);
        return $stmt->execute();
    }
    
    public function search($query) {
        $search = "%{$query}%";
        $stmt = $this->db->prepare("SELECT * FROM educational_content WHERE (title LIKE ? OR content LIKE ?) AND status = 'published' LIMIT 20");
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getUserProgress($userId) {
        $stmt = $this->db->prepare("SELECT content_id FROM user_progress WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return array_column($result, 'content_id');
    }
    
    public function markComplete($userId, $contentId) {
        $stmt = $this->db->prepare("INSERT IGNORE INTO user_progress (user_id, content_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $contentId);
        return $stmt->execute();
    }
}
