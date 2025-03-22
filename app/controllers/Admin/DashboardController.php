<?php
class DashboardController extends BaseController {
    public function __construct() {
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    public function index() {
        // Load models
        $productModel = $this->loadModel('Product');
        $orderModel = $this->loadModel('Order');
        $userModel = $this->loadModel('User');

        // Lấy thống kê
        $stats = [
            'totalProducts' => $productModel->countAll(),
            'totalOrders' => $orderModel->countAll(),
            'totalUsers' => $userModel->countAll(),
            'recentOrders' => $orderModel->getRecentOrders(5),
            'lowStockProducts' => $productModel->getLowStockProducts()
        ];

        $this->view('admin/layouts/main', [
            'content' => 'admin/dashboard/index.php',
            'title' => 'Bảng điều khiển',
            'currentPage' => 'dashboard',
            'stats' => $stats
        ]);
    }
} 