<?php
class Routes {
    private static $routes = [
        // Routes chính
        'home' => ['controller' => 'HomeController', 'action' => 'index'],
        'login' => ['controller' => 'AuthController', 'action' => 'login'],
        'login/process' => ['controller' => 'AuthController', 'action' => 'processLogin'],
        'register' => ['controller' => 'AuthController', 'action' => 'register'],
        'register/process' => ['controller' => 'AuthController', 'action' => 'processRegister'],
        'logout' => ['controller' => 'AuthController', 'action' => 'logout'],
        // Sản phẩm
        'products' => ['controller' => 'ProductController', 'action' => 'index'],
        'products/detail/([0-9]+)' => [
            'controller' => 'ProductController',
            'action' => 'detail',
            'params' => ['id']
        ],
        'products/category' => ['controller' => 'ProductController', 'action' => 'category'],
        'products/search' => ['controller' => 'ProductController', 'action' => 'search'],
        'products/loadTonKho' => ['controller' => 'ProductController', 'action' => 'loadTonKho'],
        
        // Giỏ hàng
        'cart' => ['controller' => 'CartController', 'action' => 'index'],
        'cart/add' => ['controller' => 'CartController', 'action' => 'add'],
        'cart/update' => ['controller' => 'CartController', 'action' => 'update'],
        'cart/remove' => ['controller' => 'CartController', 'action' => 'remove'],
        'cart/pay' => ['controller' => 'CartController', 'action' => 'pay'],
        
        // Tài khoản người dùng
        'user' => ['controller' => 'UserController', 'action' => 'index'],
        'user/profile' => ['controller' => 'UserController', 'action' => 'index'],
        'user/orders' => ['controller' => 'UserController', 'action' => 'orders'],
        'user/updateProfile' => ['controller' => 'UserController', 'action' => 'updateProfile'],
        'user/changePassword' => ['controller' => 'UserController', 'action' => 'changePassword'],
        
        // Trang tĩnh
        'about' => ['controller' => 'PageController', 'action' => 'about'],
        'contact' => ['controller' => 'PageController', 'action' => 'contact'],

        // Admin routes
        'admin' => ['controller' => 'AdminDashboardController', 'action' => 'index'],
        'admin/products' => ['controller' => 'AdminProductController', 'action' => 'index'],
        'admin/products/add' => ['controller' => 'AdminProductController', 'action' => 'add'],
        'admin/products/edit' => ['controller' => 'AdminProductController', 'action' => 'edit'],
        'admin/products/delete' => ['controller' => 'AdminProductController', 'action' => 'delete'],
        'admin/products/unlock' => ['controller' => 'AdminProductController', 'action' => 'unlock'],
        'admin/products/lock' => ['controller' => 'AdminProductController', 'action' => 'lock'],
        
        'admin/categories' => ['controller' => 'AdminCategoryController', 'action' => 'index'],
        'admin/categories/add' => ['controller' => 'AdminCategoryController', 'action' => 'add'],
        'admin/categories/edit' => ['controller' => 'AdminCategoryController', 'action' => 'edit'],
        'admin/categories/delete' => ['controller' => 'AdminCategoryController', 'action' => 'delete'],
        'admin/categories/unlock' => ['controller' => 'AdminCategoryController', 'action' => 'unlock'],
        
        'admin/orders' => ['controller' => 'AdminOrderController', 'action' => 'index'],
        'admin/orders/view/([0-9]+)' => [
            'controller' => 'AdminOrderController',
            'action' => 'viewOrder',
            'params' => ['id']
        ],
        'admin/orders/update-status' => ['controller' => 'AdminOrderController', 'action' => 'updateStatus'],
        
        'admin/users' => ['controller' => 'AdminUserController', 'action' => 'index'],
        'admin/users/add' => ['controller' => 'AdminUserController', 'action' => 'add'],
        'admin/users/edit' => ['controller' => 'AdminUserController', 'action' => 'edit'],
        'admin/users/delete' => ['controller' => 'AdminUserController', 'action' => 'delete'],
        'admin/users/unlock' => ['controller' => 'AdminUserController', 'action' => 'unlock'],
        'admin/users/changePassword' => ['controller' => 'AdminUserController', 'action' => 'changePassword'],
        
        'admin/suppliers' => ['controller' => 'AdminSupplierController', 'action' => 'index'],
        'admin/suppliers/add' => ['controller' => 'AdminSupplierController', 'action' => 'add'],
        'admin/suppliers/update/([0-9]+)' => [
            'controller' => 'AdminSupplierController',
            'action' => 'update',
            'params' => ['id']
        ],
        'admin/suppliers/delete' => ['controller' => 'AdminSupplierController', 'action' => 'delete'],
        'admin/suppliers/get/([0-9]+)' => [
            'controller' => 'AdminSupplierController',
            'action' => 'get',
            'params' => ['id']
        ],
        
        'admin/dashboard' => ['controller' => 'Admin\DashboardController', 'action' => 'index'],
        'admin/dashboard/top-customers' => ['controller' => 'Admin\DashboardController', 'action' => 'getTopCustomers'],
        'admin/orders/get-order-detail/:id' => [
            'controller' => 'Admin\OrderController',
            'action' => 'getOrderDetail',
            'params' => ['id']
        ],
        'admin/dashboard/customer-orders/([0-9]+)' => [
            'controller' => 'Admin\DashboardController',
            'action' => 'customerOrders',
            'params' => ['id']
        ],
        'admin/dashboard/search-orders' => ['controller' => 'Admin\DashboardController', 'action' => 'searchOrders'],
    ];

    public static function getRoutes() {
        return self::$routes;
    }
}
?> 