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
        try {
            // Lấy tham số từ URL
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
            $status = isset($_GET['status']) && $_GET['status'] !== '' ? intval($_GET['status']) : null;
            $sortColumn = isset($_GET['sort']) ? trim($_GET['sort']) : 'ngayTao';
            $sortOrder = isset($_GET['order']) ? trim($_GET['order']) : 'DESC';
            
            // Debug để kiểm tra dữ liệu đầu vào
            error_log("AdminOrderController::index - Page: $page, Search: $searchTerm, Status: " . ($status !== null ? $status : 'null') . 
                      ", Sort: $sortColumn, Order: $sortOrder");
            
            // Định nghĩa các trạng thái đơn hàng
            $orderStatuses = [
                1 => 'Đang xử lý',
                2 => 'Đang giao hàng',
                3 => 'Đã giao hàng',
                4 => 'Đã hủy'
            ];
            
            // Lấy danh sách đơn hàng theo tìm kiếm hoặc tất cả
            if (!empty($searchTerm)) {
                // Nếu có từ khóa tìm kiếm, sử dụng phương thức tìm kiếm
                $result = $this->orderModel->searchOrdersByPhoneOrAddress($searchTerm, $page, 6, $status, $sortColumn, $sortOrder);
                error_log("Tìm kiếm với từ khóa: '$searchTerm', trạng thái: " . ($status !== null ? $status : 'tất cả'));
            } else if ($status !== null) {
                // Nếu chỉ có lọc theo trạng thái, không có tìm kiếm
                $result = $this->orderModel->getOrdersWithPagination($page, 6, $status, $sortColumn, $sortOrder);
                error_log("Lọc theo trạng thái: $status");
            } else {
                // Lấy tất cả đơn hàng
                $result = $this->orderModel->getOrdersWithPagination($page, 6, null, $sortColumn, $sortOrder);
                error_log("Lấy tất cả đơn hàng");
            }
            
            // Truyền dữ liệu sang view
            $data = [
                'content' => 'admin/orders/index.php',
                'title' => 'Quản lý đơn hàng',
                'currentPage' => 'orders',
                'orders' => $result['orders'],
                'totalPages' => $result['totalPages'],
                'currentPage' => $result['currentPage'],
                'searchTerm' => $searchTerm,
                'total' => $result['total'],
                'status' => $status,
                'orderStatuses' => $orderStatuses,
                'sortColumn' => $sortColumn,
                'sortOrder' => $sortOrder
            ];
            
            $this->view('admin/layouts/main', $data);
            
        } catch (Exception $e) {
            error_log("Error in AdminOrderController::index: " . $e->getMessage());
            echo "Lỗi: " . $e->getMessage();
        }
    }

    protected function getOrderStatus($status) {
        switch($status) {
            case 1: return 'Đang xử lý';
            case 2: return 'Đang giao hàng';
            case 3: return 'Đã giao hàng';
            case 4: return 'Đã hủy';
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
            $status = isset($_POST['status']) ? (int)$_POST['status'] : null;

            error_log("Updating order status - Order ID: " . $orderId . ", Status: " . $status);

            if ($orderId && $status !== null) {
                // Kiểm tra giá trị status hợp lệ
                if (!in_array($status, [1, 2, 3, 4])) {
                    error_log("Invalid status value: " . $status);
                    echo json_encode(['success' => false, 'message' => 'Giá trị trạng thái không hợp lệ']);
                    exit();
                }
                
                // Lấy trạng thái hiện tại để kiểm tra
                $currentOrder = $this->orderModel->getOrderById($orderId);
                error_log("Current status: " . $currentOrder['trangThai'] . ", New status: " . $status);
                
                if ($this->orderModel->updateStatus($orderId, $status)) {
                    error_log("Status updated successfully to: " . $status);
                    
                    // Kiểm tra lại xem đã cập nhật thành công chưa
                    $updatedOrder = $this->orderModel->getOrderById($orderId);
                    error_log("Verified status after update: " . $updatedOrder['trangThai']);
                    
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Cập nhật trạng thái thành công',
                        'oldStatus' => $currentOrder['trangThai'],
                        'newStatus' => $updatedOrder['trangThai']
                    ]);
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