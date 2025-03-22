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
        $users = $this->userModel->getAll();
        $roles = $this->userModel->getAllRoles();
        
        $this->view('admin/layouts/main', [
            'content' => 'admin/users/index.php',
            'title' => 'Quản lý người dùng',
            'currentPage' => 'users',
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['tenTK'] ?? '';
            $password = $_POST['matKhau'] ?? '';
            $role = $_POST['maQuyen'] ?? 2;
            $status = $_POST['trangThai'] ?? 1;

            // Kiểm tra username đã tồn tại chưa
            if ($this->userModel->checkUsername($username)) {
                echo json_encode(['success' => false, 'message' => 'Tên đăng nhập đã tồn tại']);
                exit();
            }

            $data = [
                'tenTK' => $username,
                'matKhau' => md5($password), // Trong thực tế nên dùng password_hash()
                'maQuyen' => $role,
                'trangThai' => $status
            ];

            if ($this->userModel->add($data)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Thêm người dùng thất bại']);
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

    public function delete($id) {
        // Không cho phép xóa tài khoản admin
        $user = $this->userModel->getById($id);
        if ($user['maQuyen'] == 1) {
            $_SESSION['error'] = 'Không thể xóa tài khoản admin';
            header('Location: ' . BASE_URL . '/admin/users');
            exit();
        }

        if ($this->userModel->delete($id)) {
            $_SESSION['success'] = 'Xóa người dùng thành công';
        } else {
            $_SESSION['error'] = 'Xóa người dùng thất bại';
        }
        header('Location: ' . BASE_URL . '/admin/users');
        exit();
    }
} 