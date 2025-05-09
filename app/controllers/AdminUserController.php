<?php
class AdminUserController extends BaseController {
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        $this->userModel = $this->loadModel('User');
    }

    public function index() {
        // Lấy tham số page từ URL, mặc định là trang 1
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Đảm bảo page là giá trị hợp lệ
        if ($page < 1) {
            $page = 1;
        }
        
        // Lấy danh sách người dùng có phân trang
        $userData = $this->userModel->getAllWithPagination($page, 6);
        $roles = $this->userModel->getAllRoles();
        
        $this->view('admin/layouts/main', [
            'content' => 'admin/users/index.php',
            'title' => 'Quản lý người dùng',
            'currentPage' => 'users',
            'users' => $userData['users'],
            'roles' => $roles,
            'pagination' => [
                'currentPage' => $userData['currentPage'],
                'totalPages' => $userData['totalPages'],
                'total' => $userData['total']
            ]
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $username = $_POST['tenTK'] ?? '';
                $password = $_POST['matKhau'] ?? '';
                $role = $_POST['maQuyen'] ?? 2;
                $status = $_POST['trangThai'] ?? 1;

                // Validate input
                if (empty($username) || empty($password)) {
                    throw new Exception('Vui lòng điền đầy đủ thông tin');
                }

                // Kiểm tra username đã tồn tại chưa
                if ($this->userModel->checkUsername($username)) {
                    throw new Exception('Tên đăng nhập đã tồn tại');
                }

                $data = [
                    'tenTK' => $username,
                    'matKhau' => $password,
                    'maQuyen' => $role,
                    'trangThai' => $status
                ];

                if ($this->userModel->add($data)) {
                    echo json_encode(['success' => true]);
                } else {
                    throw new Exception('Thêm người dùng thất bại');
                }
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            exit();
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['maTK'] ?? null;
            $username = $_POST['tenTK'] ?? '';
            $role = $_POST['maQuyen'] ?? 2;
            $status = $_POST['trangThai'] ?? 1;

            // Kiểm tra username đã tồn tại chưa (trừ user hiện tại)
            if ($this->userModel->checkUsername($username, $id)) {
                echo json_encode(['success' => false, 'message' => 'Tên đăng nhập đã tồn tại']);
                exit();
            }

            $data = [
                'tenTK' => $username,
                'maQuyen' => $role,
                'trangThai' => $status
            ];

            if ($this->userModel->update($id, $data)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cập nhật người dùng thất bại']);
            }
            exit();
        }
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['maTK'] ?? null;
            $newPassword = $_POST['matKhau'] ?? '';

            if ($this->userModel->updatePassword($id, md5($newPassword))) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Đổi mật khẩu thất bại']);
            }
            exit();
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/users');
            exit();
        }
        
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        
        // Không cho phép khóa tài khoản admin
        $user = $this->userModel->getById($id);
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy tài khoản';
            header('Location: ' . BASE_URL . '/admin/users');
            exit();
        }
        
        if ($user->getMaQuyen() == 1) {
            $_SESSION['error'] = 'Không thể khóa tài khoản admin';
            header('Location: ' . BASE_URL . '/admin/users');
            exit();
        }

        // Thay đổi trạng thái tài khoản thành khóa (0) thay vì xóa
        if ($this->userModel->changeStatus($id, 0)) {
            $_SESSION['success'] = 'Khóa tài khoản thành công';
        } else {
            $_SESSION['error'] = 'Khóa tài khoản thất bại';
        }
        header('Location: ' . BASE_URL . '/admin/users');
        exit();
    }

    public function unlock() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/users');
            exit();
        }
        
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        
        $user = $this->userModel->getById($id);
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy tài khoản';
            header('Location: ' . BASE_URL . '/admin/users');
            exit();
        }
        
        // Mở khóa tài khoản bằng cách đặt trạng thái thành 1 (hoạt động)
        if ($this->userModel->changeStatus($id, 1)) {
            $_SESSION['success'] = 'Mở khóa tài khoản thành công';
        } else {
            $_SESSION['error'] = 'Mở khóa tài khoản thất bại';
        }
        header('Location: ' . BASE_URL . '/admin/users');
        exit();
    }
} 