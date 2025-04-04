<?php
class AdminSupplierController extends BaseController {
    private $data = [];
    private $supplierModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        $this->supplierModel = $this->loadModel('Supplier');
        $this->generator();
    }

    public function generator() {
        $suppliers = $this->supplierModel->getAll();
        $this->data['content'] = 'admin/suppliers/index.php';
        $this->data['title'] = 'Quản lý nhà cung cấp';  
        $this->data['currentPage'] = 'suppliers';
        $this->data['suppliers'] = $suppliers;
    }

    public function index() {
        $this->view('admin/layouts/main', $this->data);
        // $this->view('admin/layouts/main', [
        //     'content' => 'admin/suppliers/index.php',
        //     'title' => 'Quản lý nhà cung cấp',
        //     'currentPage' => 'suppliers',
        //     'suppliers' => $suppliers
        // ]);
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

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'maNCC' => $_POST['maNCC'],
                'tenNCC' => $_POST['tenNCC'],
                'email' => $_POST['email'],
                'diaChi' => $_POST['diaChi'],
                'trangThai' => $_POST['trangThai']
            ];

            if ($this->supplierModel->update($data['maNCC'],$data)) {
                $_SESSION['success'] = 'Cập nhật nhà cung cấp thành công';
                header('Location: ' . BASE_URL . '/admin/suppliers');
                exit();
            } else {
                $_SESSION['error'] = 'Cập nhật nhà cung cấp thất bại';
            }
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['maNCC'];
        
            if ($this->supplierModel->delete($id)) {
                $_SESSION['success'] = 'Xóa nhà cung cấp thành công';
            } else {
                $_SESSION['error'] = 'Xóa nhà cung cấp thất bại';
            }
            header('Location: ' . BASE_URL . '/admin/suppliers');
            exit();
        }
    }

    public function unlock() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['maNCC'];
        
            if ($this->supplierModel->unlock($id)) {
                $_SESSION['success'] = 'Mở khoá nhà cung cấp thành công';
            } else {
                $_SESSION['error'] = 'Mở khoá nhà cung cấp thất bại';
            }
            header('Location: ' . BASE_URL . '/admin/suppliers');
            exit();
        }
    }
} 