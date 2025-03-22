<?php
class AdminProductController extends BaseController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        $this->productModel = $this->loadModel('Product');
        $this->categoryModel = $this->loadModel('Category');
    }

    public function index() {
        $products = $this->productModel->getAll();
        
        // Debug để kiểm tra dữ liệu
        // echo '<pre>'; print_r($products); echo '</pre>';
        
        $this->view('admin/layouts/main', [
            'content' => 'admin/products/index.php',
            'title' => 'Quản lý sản phẩm',
            'currentPage' => 'products',
            'products' => $products
        ]);
    }

    public function add() {
        $categories = $this->categoryModel->getAll();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Xử lý thêm sản phẩm
        }
        $this->view('admin/layouts/main', [
            'content' => 'admin/products/add.php',
            'title' => 'Thêm sản phẩm mới',
            'currentPage' => 'products',
            'categories' => $categories
        ]);
    }

    // Thêm các methods edit và delete
} 