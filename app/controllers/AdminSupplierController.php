<?php
class AdminSupplierController extends BaseController {
    private $supplierModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        $this->supplierModel = $this->loadModel('Supplier');
    }

    public function index() {
        $suppliers = $this->supplierModel->getAll();
        $this->view('admin/layouts/main', [
            'content' => 'admin/suppliers/index.php',
            'title' => 'Quản lý nhà cung cấp',
            'currentPage' => 'suppliers',
            'suppliers' => $suppliers
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'tenNCC' => $_POST['tenNCC'],
                'email' => $_POST['email'],
                'diaChi' => $_POST['diaChi']
            ];

            if ($this->supplierModel->add($data)) {
                $_SESSION['success'] = 'Thêm nhà cung cấp thành công';
                header('Location: ' . BASE_URL . '/admin/suppliers');
                exit();
            } else {
                $_SESSION['error'] = 'Thêm nhà cung cấp thất bại';
            }
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'tenNCC' => $_POST['tenNCC'],
                'email' => $_POST['email'],
                'diaChi' => $_POST['diaChi']
            ];

            if ($this->supplierModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật nhà cung cấp thành công';
                header('Location: ' . BASE_URL . '/admin/suppliers');
                exit();
            } else {
                $_SESSION['error'] = 'Cập nhật nhà cung cấp thất bại';
            }
        }
    }

    public function delete($id) {
        if ($this->supplierModel->delete($id)) {
            $_SESSION['success'] = 'Xóa nhà cung cấp thành công';
        } else {
            $_SESSION['error'] = 'Xóa nhà cung cấp thất bại';
        }
        header('Location: ' . BASE_URL . '/admin/suppliers');
        exit();
    }
} 