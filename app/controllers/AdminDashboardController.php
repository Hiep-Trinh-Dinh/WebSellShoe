<?php
class AdminDashboardController extends BaseController {
    private $productModel;
    private $orderModel;
    private $userModel;

    public function __construct() {
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        $this->productModel = $this->loadModel('Product');
        $this->orderModel = $this->loadModel('Order');
        $this->userModel = $this->loadModel('User');
    }

    public function index() {
        // Lấy thống kê
        $stats = [
            'totalProducts' => $this->productModel->countAll(),
            'totalOrders' => $this->orderModel->countAll(),
            'totalUsers' => $this->userModel->countAll(),
            'recentOrders' => $this->orderModel->getRecentOrders(5),
            'lowStockProducts' => $this->productModel->getLowStockProducts()
        ];

        $this->view('admin/layouts/main', [
            'content' => 'admin/dashboard/index.php',
            'title' => 'Bảng điều khiển',
            'currentPage' => 'dashboard',
            'stats' => $stats
        ]);
    }
}
?> 