<?php
namespace Admin;

use Exception;
use PDO;
use PDOException;

class DashboardController extends \BaseController {
    public function __construct() {
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    public function index() {
        try {
            $orderModel = $this->loadModel('Order');
            $userModel = $this->loadModel('User');
            $productModel = $this->loadModel('Product');

            // Lấy thống kê tổng quan
            $stats = [
                'totalProducts' => $productModel->getTotalProducts(),
                'totalOrders' => $orderModel->getTotalOrders(),
                'totalUsers' => $userModel->getTotalUsers(),
                'lowStockProducts' => $productModel->getLowStockProducts(),
                'recentOrders' => $orderModel->getRecentOrders(5)
            ];
            
            $this->view('admin/layouts/main', [
                'content' => 'admin/dashboard/index.php',
                'title' => 'Dashboard',
                'stats' => $stats
            ]);
            
        } catch (Exception $e) {
            error_log("Error in dashboard index: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            $this->view('admin/layouts/main', [
                'content' => 'admin/dashboard/index.php',
                'title' => 'Dashboard',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getTopCustomers() {
        try {
            if (!isset($_GET['month']) || !isset($_GET['year'])) {
                return json_encode([]);
            }
            
            $month = (int)$_GET['month'];
            $year = (int)$_GET['year'];
            
            $orderModel = $this->loadModel('Order');
            $topCustomers = $orderModel->getTopCustomers($month, $year);
            
            header('Content-Type: application/json');
            echo json_encode($topCustomers ?? []);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }
    }

    public function customerOrders($params) {
        try {
            if (!isset($params[0])) {
                throw new Exception('Thiếu thông tin khách hàng');
            }

            $userId = (int)$params[0];
            $month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
            $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
            
            $orderModel = $this->loadModel('Order');
            $userModel = $this->loadModel('User');
            
            $customerData = $userModel->getUserById($userId);
            if (!$customerData) {
                throw new Exception('Không tìm thấy thông tin khách hàng với ID: ' . $userId);
            }

            $orders = $orderModel->getCustomerMonthlyOrdersWithDetails($userId, $month, $year);
            $totalOrders = count($orders);
            $totalAmount = array_sum(array_column($orders, 'tongTien'));

            $this->view('admin/layouts/main', [
                'content' => 'admin/dashboard/customer-orders.php',
                'title' => 'Chi tiết đơn hàng',
                'customerData' => $customerData,
                'orders' => $orders,
                'month' => $month,
                'year' => $year,
                'totalOrders' => $totalOrders,
                'totalAmount' => $totalAmount
            ]);

        } catch (Exception $e) {
            error_log("Error in customerOrders: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/dashboard');
            exit;
        }
    }

    public function searchOrders() {
        try {
            if (!isset($_GET['startDate']) || !isset($_GET['endDate'])) {
                throw new Exception('Thiếu thông tin ngày bắt đầu hoặc kết thúc');
            }

            $startDate = $_GET['startDate'];
            $endDate = $_GET['endDate'];
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 5; // Số đơn hàng trên mỗi trang
            
            $orderModel = $this->loadModel('Order');
            $result = $orderModel->searchOrdersByDate($startDate, $endDate, $page, $limit);

            // Đảm bảo không có output nào trước khi gửi JSON
            if (ob_get_length()) ob_clean();
            
            header('Content-Type: application/json');
            echo json_encode([
                'orders' => $result['orders'],
                'totalPages' => $result['totalPages'],
                'currentPage' => $page,
                'totalOrders' => $result['totalOrders'] ?? 0
            ]);
            exit;
        } catch (Exception $e) {
            if (ob_get_length()) ob_clean();
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => $e->getMessage(),
                'orders' => [],
                'totalPages' => 0,
                'currentPage' => 1,
                'totalOrders' => 0
            ]);
            exit;
        }
    }

    public function getMonthlyStats() {
        try {
            if (!isset($_GET['month']) || !isset($_GET['year'])) {
                throw new Exception('Thiếu thông tin tháng hoặc năm');
            }

            $month = (int)$_GET['month'];
            $year = (int)$_GET['year'];
            
            $orderModel = $this->loadModel('Order');
            $stats = $orderModel->getMonthlyStatistics($month, $year);
            
            header('Content-Type: application/json');
            echo json_encode($stats);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
} 