<?php

class Router {
    private $routes = [];
    
    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }
    
    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = str_replace('/Personal%20Projects/Tradetechie', '', $path);
        $path = str_replace('/Personal Projects/Tradetechie', '', $path);
        $path = rtrim($path, '/') ?: '/';
        
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $callback) {
                $pattern = preg_replace('/\{[a-zA-Z]+\}/', '([^/]+)', $route);
                $pattern = '#^' . $pattern . '$#';
                
                if (preg_match($pattern, $path, $matches)) {
                    array_shift($matches);
                    return call_user_func_array($callback, $matches);
                }
            }
        }
        
        http_response_code(404);
        echo "404 - Page Not Found";
    }
}
