<?php
class AdminCategoryController extends BaseController {
    private $categoryModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        $this->categoryModel = $this->loadModel('Category');
    }

    public function index() {
        $categories = $this->categoryModel->getAll();
        $this->view('admin/layouts/main', [
            'content' => 'admin/categories/index.php',
            'title' => 'Quản lý loại giày',
            'currentPage' => 'categories',
            'categories' => $categories
        ]);
    }

    // Thêm các methods add, edit và delete
} 