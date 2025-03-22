<?php
class AuthController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->loadModel('User');
    }

    public function login() {
        // Nếu đã đăng nhập, chuyển về trang chủ
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL);
            exit();
        }

        $this->view('layouts/main', [
            'content' => 'auth/login.php',
            'title' => 'Đăng nhập'
        ]);
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            $user = $this->userModel->getUserByUsername($username);
            
            if ($user && $user['matKhau'] === $password && $user['trangThai'] == 1) {
                // Đăng nhập thành công
                $_SESSION['user_id'] = $user['maTK'];
                $_SESSION['username'] = $user['tenTK'];
                $_SESSION['user_role'] = $user['maQuyen'];
                $_SESSION['success'] = 'Đăng nhập thành công!';
                
                // Chuyển hướng dựa vào quyền
                if ($user['maQuyen'] == 1) {
                    header('Location: ' . BASE_URL . '/admin');
                } else {
                    header('Location: ' . BASE_URL);
                }
                exit();
            } else {
                $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không chính xác';
                header('Location: ' . BASE_URL . '/login');
                exit();
            }
        } else {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    public function register() {
        // Nếu đã đăng nhập, chuyển về trang chủ
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL);
            exit();
        }

        $this->view('layouts/main', [
            'content' => 'auth/register.php',
            'title' => 'Đăng ký'
        ]);
    }

    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tenTK' => $_POST['username'],
                'matKhau' => $_POST['password'],
                'maQuyen' => 2, // Quyền mặc định cho người dùng mới
                'trangThai' => 1 // Trạng thái hoạt động
            ];

            // Kiểm tra username đã tồn tại
            if ($this->userModel->getUserByUsername($data['tenTK'])) {
                $_SESSION['error'] = 'Tên đăng nhập đã được sử dụng';
                header('Location: ' . BASE_URL . '/register');
                exit();
            }

            // Kiểm tra mật khẩu xác nhận
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $_SESSION['error'] = 'Mật khẩu xác nhận không khớp';
                header('Location: ' . BASE_URL . '/register');
                exit();
            }

            if ($this->userModel->createUser($data)) {
                $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
                header('Location: ' . BASE_URL . '/login');
                exit();
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                header('Location: ' . BASE_URL . '/register');
                exit();
            }
        } else {
            header('Location: ' . BASE_URL . '/register');
            exit();
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit();
    }
}
?> 