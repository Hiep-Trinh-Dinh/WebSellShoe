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
        $user = $this->userModel->getUserById($userId);
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
            $data = [
                'hoTen' => $_POST['hoTen'],
                'email' => $_POST['email'],
                'soDienThoai' => $_POST['soDienThoai'],
                'diaChi' => $_POST['diaChi']
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
}
?> 