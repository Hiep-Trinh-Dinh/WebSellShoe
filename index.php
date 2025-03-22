<?php
session_start();

// Load config
require_once 'config/config.php';

// Load core classes
require_once 'core/Database.php';
require_once 'core/BaseController.php';
require_once 'core/BaseModel.php';
require_once 'core/Router.php';

// Khởi tạo router
$router = new Router();

// Lấy URL từ $_GET
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Route request
$router->route($url);
?> 