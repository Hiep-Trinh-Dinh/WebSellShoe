<?php
class UserController extends BaseController {
    private $userModel;
    private $orderModel;

    public function __construct() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        
        $this->userModel = $this->loadModel('User');
        $this->orderModel = $this->loadModel('Order');
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getById($userId);
        $orders = $this->orderModel->getOrdersByUserId($userId);
        
        $this->view('layouts/main', [
            'content' => 'user/profile.php',
            'title' => 'Tài khoản của tôi',
            'user' => $user,
            'orders' => $orders
        ]);
    }

    public function orders() {
        $userId = $_SESSION['user_id'];
        $orders = $this->orderModel->getOrdersByUserId($userId);
        
        $this->view('layouts/main', [
            'content' => 'user/orders.php',
            'title' => 'Đơn hàng của tôi',
            'orders' => $orders
        ]);
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            
            // Kiểm tra số điện thoại
            $soDienThoai = $_POST['soDienThoai'] ?? '';
            $diaChi = $_POST['diaChi'] ?? '';
            
            if (empty($soDienThoai) || !preg_match('/^[0-9]{10}$/', $soDienThoai)) {
                $_SESSION['error'] = 'Số điện thoại không hợp lệ. Vui lòng nhập đúng 10 chữ số.';
                header('Location: ' . BASE_URL . '/user');
                exit();
            }
            
            // Cập nhật thông tin
            $data = [
                'soDienThoai' => $soDienThoai,
                'diaChi' => $diaChi
            ];
            
            if ($this->userModel->updateUser($userId, $data)) {
                $_SESSION['success'] = 'Cập nhật thông tin thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật thông tin';
            }
            
            header('Location: ' . BASE_URL . '/user');
            exit();
        }
    }

    public function getUserInfo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['maTK'])) {
                error_log("API getUserInfo: Không có maTK");
                echo json_encode([
                    'success' => false,
                    'message' => 'Thiếu mã tài khoản'
                ]);
                return;
            }

            $userId = $_POST['maTK'];
            error_log("API getUserInfo: Đang lấy thông tin cho userId = " . $userId);
            
            $user = $this->userModel->getById($userId);
            
            if ($user) {
                error_log("API getUserInfo: Tìm thấy user thành công");
                $userData = [
                    'maTK' => $user->getMaTK(),
                    'tenTK' => $user->getTenTK(),
                    'soDienThoai' => $user->getSoDienThoai(),
                    'diaChi' => $user->getDiaChi(),
                ];
                error_log("API getUserInfo: Dữ liệu trả về: " . json_encode($userData));
                
                echo json_encode([
                    'success' => true,
                    'user' => $userData
                ]);
            } else {
                error_log("API getUserInfo: Không tìm thấy user với ID = " . $userId);
                echo json_encode([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin người dùng'
                ]);
            }
        }
    }
}
?> 