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
            $password = md5($_POST['password']); // Mã hóa mật khẩu để so sánh
            
            $user = $this->userModel->getUserByUsername($username);
            
            if ($user) {
                if ($user->getTrangThai() == 0) {
                    // Tài khoản bị khóa
                    $_SESSION['error'] = 'Tài khoản đã bị khóa';
                    header('Location: ' . BASE_URL . '/login');
                    exit();
                } else if ($user->getMatKhau() === $password) {
                    // Đăng nhập thành công
                    $_SESSION['user_id'] = $user->getMaTK();
                    $_SESSION['username'] = $user->getTenTK();
                    $_SESSION['user_role'] = $user->getMaQuyen();
                    $_SESSION['success'] = 'Đăng nhập thành công!';

                    echo "<script> 
                            localStorage.setItem('maTK', '" . $user->getMaTK() . "') 
                        </script>";
                    
                    // Chuyển hướng dựa vào quyền
                    if ($user->getMaQuyen() == 1) {
                        echo "<script> 
                            window.location.href = '" . BASE_URL . "/admin'; 
                        </script>";
                    } else {
                        echo "<script> 
                            window.location.href = '" . BASE_URL . "/';
                        </script>";
                    }
                    exit();
                } else {
                    $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không chính xác';
                    header('Location: ' . BASE_URL . '/login');
                    exit();
                }
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
            // Lấy dữ liệu từ form
            $data = [
                'tenTK' => trim($_POST['username']),
                'matKhau' => md5($_POST['password']), // Mã hóa mật khẩu
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

            try {
                if ($this->userModel->createUser($data)) {
                    $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
                    header('Location: ' . BASE_URL . '/login');
                    exit();
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                    header('Location: ' . BASE_URL . '/register');
                    exit();
                }
            } catch (PDOException $e) {
                error_log("Lỗi đăng ký: " . $e->getMessage());
                $_SESSION['error'] = 'Lỗi hệ thống: ' . $e->getMessage();
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
        echo "<script> 
                    localStorage.removeItem('maTK');
                    localStorage.removeItem('cartItem');
                    localStorage.removeItem('cartItems');
                    window.location.href = '" . BASE_URL . "/login';
            </script>";
        exit();
    }
}
?> 