<?php
session_start();

// Load config
require_once 'config/config.php';

// Load core classes
require_once 'core/Database.php';
require_once 'core/BaseController.php';
require_once 'core/BaseModel.php';
require_once 'app/routes.php';
require_once 'core/Router.php';

// Autoload các file trong thư mục models và controllers
spl_autoload_register(function ($className) {
    if (file_exists('app/models/' . $className . '.php')) {
        require_once 'app/models/' . $className . '.php';
    } elseif (file_exists('app/controllers/' . $className . '.php')) {
        require_once 'app/controllers/' . $className . '.php';
    }
});

// Khởi tạo router
$router = new Router();

// Lấy URL từ $_GET
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Route request
$router->route($url);
?> 