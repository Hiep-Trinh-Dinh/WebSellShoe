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
            
            // Kiểm tra xem controller có tồn tại không
            $controllerFile = 'app/controllers/' . $controller . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                
                // Tạo instance của controller
                $controllerInstance = new $controller();
                
                // Kiểm tra xem action có tồn tại không
                if (method_exists($controllerInstance, $action)) {
                    // Gọi action với params
                    call_user_func_array([$controllerInstance, $action], [$params]);
                } else {
                    error_log("Action not found: " . $action);
                    $this->error404();
                }
            } else {
                error_log("Controller not found: " . $controller);
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
        
        // Tách URL thành các phần
        $parts = explode('/', trim($url, '/'));
        
        // Kiểm tra route admin/orders/view/{id}
        if (count($parts) >= 4 && 
            $parts[0] === 'admin' && 
            $parts[1] === 'orders' && 
            $parts[2] === 'view') {
            
            return [
                'controller' => 'AdminOrderController',
                'action' => 'viewOrder',
                'params' => ['id' => $parts[3]]
            ];
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

        return null;
    }

    private function error404() {
        $this->handleNotFound();
    }
}
?> 