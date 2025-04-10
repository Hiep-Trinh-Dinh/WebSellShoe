<?php
require_once 'app/routes.php';

class Router {
    private $routes;

    public function __construct() {
        $this->routes = Routes::getRoutes();
    }

    public function dispatch() {
        $url = $this->getUrl();
        $route = $this->matchRoute($url);
        
        if ($route) {
            $controller = $route['controller'];
            $action = $route['action'];
            $params = $route['params'] ?? [];
            
            // Debug logging
            error_log("Dispatching to controller: " . $controller . ", action: " . $action);
            error_log("Parameters: " . print_r($params, true));
            
            // Kiểm tra xem controller có tồn tại không
            $controllerFile = $this->loadController($controller);
            
            // Kiểm tra xem action có tồn tại không
            if (method_exists($controllerFile, $action)) {
                // Nếu params là mảng associative, truyền từng tham số riêng lẻ
                if (is_array($params) && !empty($params)) {
                    // Debug logging
                    error_log("Calling " . $action . " with params: " . print_r(array_values($params), true));
                    call_user_func_array([$controllerFile, $action], array_values($params));
                } else {
                    // Debug logging
                    error_log("Calling " . $action . " with param: " . print_r($params, true));
                    call_user_func_array([$controllerFile, $action], [$params]);
                }
            } else {
                error_log("Action not found: " . $action);
                $this->error404();
            }
        } else {
            error_log("Route not found for URL: " . $url);
            $this->error404();
        }
    }

    public function route($url) {
        // Xóa dấu / ở đầu và cuối URL nếu có
        $url = trim($url, '/');
        
        // Nếu URL rỗng, sử dụng route mặc định
        if (empty($url)) {
            $url = 'home';
        }

        foreach ($this->routes as $pattern => $route) {
            // Thêm dấu ^ vào đầu và $ vào cuối để đảm bảo khớp chính xác
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $url, $matches)) {
                // Xóa phần tử đầu tiên (toàn bộ chuỗi khớp)
                array_shift($matches);
                
                $controller = $route['controller'];
                $action = $route['action'];
                
                // Kiểm tra và tạo controller
                if (!class_exists($controller)) {
                    throw new Exception("Controller {$controller} không tồn tại");
                }
                
                $controllerInstance = new $controller();
                
                // Kiểm tra phương thức tồn tại
                if (!method_exists($controllerInstance, $action)) {
                    throw new Exception("Action {$action} không tồn tại trong controller {$controller}");
                }
                
                // Gọi phương thức với các tham số từ URL
                return call_user_func_array([$controllerInstance, $action], [$matches]);
            }
        }

        // Không tìm thấy route phù hợp
        $this->handleNotFound();
    }

    public function handleNotFound() {
        // Thay đổi đường dẫn để trỏ đến file 404 trong thư mục error
        require_once 'app/views/error/404.php';
        exit();
    }

    private function getUrl() {
        // Implement the logic to get the current URL
        // This is a placeholder and should be replaced with the actual implementation
        return $_SERVER['REQUEST_URI'];
    }

    private function matchRoute($url) {
        // Loại bỏ BASE_URL nếu có
        $url = str_replace(BASE_URL, '', $url);
        
        // Loại bỏ các tham số query string
        $url = strtok($url, '?');
        
        // Debug
        error_log("URL after processing: " . $url);
        
        // Tách URL thành các phần
        $parts = explode('/', trim($url, '/'));
        
        foreach ($this->routes as $pattern => $route) {
            // Thêm dấu ^ vào đầu và $ vào cuối để đảm bảo khớp chính xác
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $url, $matches)) {
                // Debug
                error_log("Route matched: " . $pattern);
                error_log("Matches: " . print_r($matches, true));
                
                // Xóa phần tử đầu tiên (toàn bộ chuỗi khớp)
                array_shift($matches);
                
                $controller = $route['controller'];
                $action = $route['action'];
                
                // Nếu có params trong route, sử dụng chúng
                if (isset($route['params'])) {
                    $params = [];
                    foreach ($route['params'] as $key => $paramName) {
                        // Debug
                        error_log("Mapping param: " . $paramName . " at key " . $key . " from matches");
                        $params[$paramName] = isset($matches[$key]) ? $matches[$key] : null;
                    }
                    
                    // Debug
                    error_log("Final params with names: " . print_r($params, true));
                    
                    return [
                        'controller' => $controller,
                        'action' => $action,
                        'params' => $params
                    ];
                }
                
                // Debug
                error_log("Final params without names: " . print_r($matches, true));
                
                return [
                    'controller' => $controller,
                    'action' => $action,
                    'params' => $matches
                ];
            }
        }

        return null;
    }

    private function error404() {
        $this->handleNotFound();
    }

    private function loadController($controller) {
        // Xử lý namespace
        $controllerParts = explode('\\', $controller);
        $controllerName = end($controllerParts);
        $controllerPath = 'app/controllers/';
        
        // Nếu có namespace, thêm thư mục tương ứng
        if (count($controllerParts) > 1) {
            $controllerPath .= implode('/', array_slice($controllerParts, 0, -1)) . '/';
        }
        
        $controllerPath .= $controllerName . '.php';
        
        // Debug để xem đường dẫn
        error_log("Trying to load controller from: " . $controllerPath);
        
        if (!file_exists($controllerPath)) {
            error_log("Controller file not found at: " . $controllerPath);
            throw new Exception("Controller {$controller} không tồn tại");
        }
        
        require_once $controllerPath;
        
        if (!class_exists($controller)) {
            error_log("Controller class {$controller} not found in file");
            throw new Exception("Controller class {$controller} không tồn tại");
        }
        
        return new $controller();
    }
}
?> 