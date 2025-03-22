<?php
require_once 'app/routes.php';

class Router {
    private $routes;

    public function __construct() {
        $this->routes = Routes::getRoutes();
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
        header("HTTP/1.0 404 Not Found");
        include 'app/views/404.php';
        exit();
    }
}
?> 