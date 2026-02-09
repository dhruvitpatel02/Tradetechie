<?php

class Note extends Model {
    protected $table = 'stock_notes';
    
    public function getUserNotes($userId, $companyId = null) {
        if ($companyId) {
            $stmt = $this->db->prepare("
                SELECT n.*, s.symbol, s.company_name
                FROM stock_notes n
                JOIN stock_companies s ON n.company_id = s.company_id
                WHERE n.user_id = ? AND n.company_id = ?
                ORDER BY n.updated_at DESC
            ");
            $stmt->bind_param("ii", $userId, $companyId);
        } else {
            $stmt = $this->db->prepare("
                SELECT n.*, s.symbol, s.company_name
                FROM stock_notes n
                JOIN stock_companies s ON n.company_id = s.company_id
                WHERE n.user_id = ?
                ORDER BY n.updated_at DESC
            ");
            $stmt->bind_param("i", $userId);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function createNote($data) {
        $stmt = $this->db->prepare("INSERT INTO stock_notes (user_id, company_id, note_title, note_content, tags) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $data['user_id'], $data['company_id'], $data['note_title'], $data['note_content'], $data['tags']);
        return $stmt->execute();
    }
    
    public function updateNote($noteId, $userId, $data) {
        $stmt = $this->db->prepare("UPDATE stock_notes SET note_title = ?, note_content = ?, tags = ? WHERE note_id = ? AND user_id = ?");
        $stmt->bind_param("sssii", $data['note_title'], $data['note_content'], $data['tags'], $noteId, $userId);
        return $stmt->execute();
    }
    
    public function deleteNote($noteId, $userId) {
        $stmt = $this->db->prepare("DELETE FROM stock_notes WHERE note_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $noteId, $userId);
        return $stmt->execute();
    }
    
    public function getCount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM stock_notes WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['count'];
    }
    
    public function searchByTag($userId, $tag) {
        $search = "%{$tag}%";
        $stmt = $this->db->prepare("SELECT * FROM stock_notes WHERE user_id = ? AND tags LIKE ? ORDER BY updated_at DESC");
        $stmt->bind_param("is", $userId, $search);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
