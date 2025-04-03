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

    protected function getOrderStatus($status) {
        switch($status) {
            case 1: return 'Đang xử lý';
            case 2: return 'Đã xác nhận';
            case 3: return 'Đang giao hàng';
            case 4: return 'Đã giao hàng';
            case 5: return 'Đã hủy';
            default: return 'Không xác định';
        }
    }

    public function viewOrder($id) {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        // Đảm bảo $id là số nguyên
        $orderId = is_array($id) ? $id[0] : $id;
        $orderId = (int)$orderId;

        // Lấy thông tin đơn hàng
        $orderModel = $this->loadModel('Order');
        $order = $orderModel->getOrderById($orderId);

        if (!$order) {
            // Sử dụng session để lưu thông báo lỗi
            $_SESSION['error'] = 'Không tìm thấy đơn hàng';
            $this->redirect('admin/orders');
            return;
        }

        // Lấy chi tiết đơn hàng
        $orderDetails = $orderModel->getOrderDetails($orderId);

        // Render view
        $this->view('admin/orders/view', [
            'order' => $order,
            'orderDetails' => $orderDetails
        ]);
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderId = $_POST['orderId'] ?? null;
            $status = $_POST['status'] ?? null;

            error_log("Updating order status - Order ID: " . $orderId . ", Status: " . $status);

            if ($orderId && $status !== null) {
                if ($this->orderModel->updateStatus($orderId, $status)) {
                    error_log("Status updated successfully");
                    echo json_encode(['success' => true]);
                    exit();
                } else {
                    error_log("Failed to update status");
                }
            } else {
                error_log("Invalid orderId or status");
            }
            echo json_encode(['success' => false, 'message' => 'Cập nhật trạng thái thất bại']);
            exit();
        }
    }
} 