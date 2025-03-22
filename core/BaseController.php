<?php
class BaseController {
    protected function loadModel($model) {
        require_once 'app/models/' . $model . '.php';
        return new $model();
    }

    protected function view($view, $data = []) {
        if (is_array($data)) {
            extract($data);
        }
        
        // Kiểm tra xem có content được truyền vào không
        if (isset($data['content'])) {
            $content = 'app/views/' . $data['content'];
        }
        
        require_once 'app/views/' . $view . '.php';
    }

    protected function redirect($url) {
        header('Location: ' . BASE_URL . '/' . $url);
        exit();
    }

    protected function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    protected function requireAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }
}
?> 