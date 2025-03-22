<?php
class ProductController extends BaseController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = $this->loadModel('Product');
        $this->categoryModel = $this->loadModel('Category');
    }

    public function index() {
        $products = $this->productModel->getAllProducts();
        $categories = $this->categoryModel->getAll();

        $this->view('layouts/main', [
            'content' => 'product/index.php',
            'title' => 'Tất cả sản phẩm',
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function detail($params = []) {
        $id = isset($params['id']) ? $params['id'] : null;
        
        if (!$id) {
            header('Location: ' . BASE_URL . '/products');
            exit();
        }

        $product = $this->productModel->getProductById($id);
        
        if (!$product) {
            header('Location: ' . BASE_URL . '/products');
            exit();
        }

        // Lấy loại giày của sản phẩm
        $category = $this->categoryModel->getById($product['maLoaiGiay']);
        if ($category) {
            $product['tenLoaiGiay'] = $category['tenLoaiGiay'];
        }

        $this->view('layouts/main', [
            'content' => 'product/detail.php',
            'title' => $product['tenGiay'],
            'product' => $product
        ]);
    }

    public function category($id = null) {
        if (!$id) {
            header('Location: ' . BASE_URL . '/products');
            exit();
        }

        $products = $this->productModel->getProductsByCategory($id);
        $categories = $this->categoryModel->getAll();
        $currentCategory = $this->categoryModel->getById($id);

        $this->view('layouts/main', [
            'content' => 'products/index.php',
            'title' => $currentCategory['tenLoaiGiay'] ?? 'Sản phẩm',
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $currentCategory
        ]);
    }

    public function search() {
        $keyword = $_GET['keyword'] ?? '';
        $products = $this->productModel->searchProducts($keyword);
        $categories = $this->categoryModel->getAll();

        $this->view('layouts/main', [
            'content' => 'products/index.php',
            'title' => 'Kết quả tìm kiếm: ' . $keyword,
            'products' => $products,
            'categories' => $categories,
            'keyword' => $keyword
        ]);
    }
}
?> 