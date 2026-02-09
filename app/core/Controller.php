<?php

class Controller {
    
    protected function view($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
    
    protected function redirect($url) {
        header('Location: ' . SITE_URL . $url);
        exit();
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
    }
    
    protected function requireAdmin() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $this->redirect('dashboard');
        }
    }
}
