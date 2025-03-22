<?php
class AdminOrderController extends BaseController {
    private $orderModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        $this->orderModel = $this->loadModel('Order');
    }

    public function index() {
        $orders = $this->orderModel->getAll();
        $this->view('admin/layouts/main', [
            'content' => 'admin/orders/index.php',
            'title' => 'Quản lý đơn hàng',
            'currentPage' => 'orders',
            'orders' => $orders
        ]);
    }

    public function viewOrder($id = null) {
        if (!$id) {
            $_SESSION['error'] = 'ID đơn hàng không hợp lệ';
            header('Location: ' . BASE_URL . '/admin/orders');
            exit();
        }

        $order = $this->orderModel->getById($id);
        $orderDetails = $this->orderModel->getOrderDetails($id);
        
        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: ' . BASE_URL . '/admin/orders');
            exit();
        }

        $this->view('admin/layouts/main', [
            'content' => 'admin/orders/view.php',
            'title' => 'Chi tiết đơn hàng #' . $id,
            'currentPage' => 'orders',
            'order' => $order,
            'orderDetails' => $orderDetails
        ]);
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderId = $_POST['orderId'] ?? null;
            $status = $_POST['status'] ?? null;

            if ($orderId && $status !== null) {
                if ($this->orderModel->updateStatus($orderId, $status)) {
                    echo json_encode(['success' => true]);
                    exit();
                }
            }
            echo json_encode(['success' => false, 'message' => 'Cập nhật trạng thái thất bại']);
            exit();
        }
    }
} 