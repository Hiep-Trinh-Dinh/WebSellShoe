<?php
class Router {
    private $routes = [];

    public function __construct() {
        $this->loadRoutes();
    }

    private function loadRoutes() {
        require_once 'app/routes.php';
        $this->routes = Routes::getRoutes();
    }

    public function route($url) {
        foreach ($this->routes as $pattern => $route) {
            // Thêm dấu ^ vào đầu và $ vào cuối để khớp chính xác
            $pattern = '#^' . str_replace('/', '\/', $pattern) . '$#';
            
            if (preg_match($pattern, $url, $matches)) {
                $controller = $route['controller'];
                $action = $route['action'];
                $params = [];

                // Nếu có params được định nghĩa trong route
                if (isset($route['params'])) {
                    // Bỏ phần tử đầu tiên của matches (full match)
                    array_shift($matches);
                    // Gán giá trị cho các params
                    foreach ($route['params'] as $key => $paramName) {
                        $params[$paramName] = $matches[$key] ?? null;
                    }
                }

                // Kiểm tra và tạo instance của controller
                if (file_exists('app/controllers/' . $controller . '.php')) {
                    require_once 'app/controllers/' . $controller . '.php';
                    $controllerInstance = new $controller();
                    
                    // Gọi action với params
                    if (method_exists($controllerInstance, $action)) {
                        return $controllerInstance->$action($params);
                    }
                }
            }
        }

        // Nếu không tìm thấy route phù hợp
        header("HTTP/1.0 404 Not Found");
        require_once 'app/views/404.php';
    }
}
?> 