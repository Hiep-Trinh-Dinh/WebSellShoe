<?php
class ProductController extends BaseController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = $this->loadModel('Product');
        $this->categoryModel = $this->loadModel('Category');
    }

    public function index() {
        try {
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $perPage = 6; // Thay đổi từ 9 xuống 6 sản phẩm mỗi trang
            
            // Gọi phương thức searchProducts với tham số mặc định
            $result = $this->productModel->searchProducts('', [], $page, $perPage);
            $categories = $this->categoryModel->getAll();

            $this->view('layouts/main', [
                'content' => 'product/index.php',
                'title' => 'Tất cả sản phẩm',
                'products' => $result['products'],
                'categories' => $categories,
                'keyword' => '',
                'filters' => [],
                'currentPage' => $result['currentPage'],
                'totalPages' => $result['totalPages'],
                'total' => $result['total']
            ]);
        } catch (Exception $e) {
            error_log("Error in index: " . $e->getMessage());
            $this->view('layouts/main', [
                'content' => 'product/index.php',
                'title' => 'Tất cả sản phẩm',
                'products' => [],
                'categories' => $this->categoryModel->getAll(),
                'error' => 'Đã xảy ra lỗi khi tải sản phẩm',
                'currentPage' => 1,
                'totalPages' => 0,
                'total' => 0
            ]);
        }
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
        try {
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $perPage = 6; // Thay đổi từ 9 xuống 6 sản phẩm mỗi trang
            
            $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
            $filters = [
                'categories' => isset($_GET['category']) ? array_map('intval', (array)$_GET['category']) : [],
                'price_range' => isset($_GET['price_range']) ? $_GET['price_range'] : '',
                'sort' => isset($_GET['sort']) ? $_GET['sort'] : ''
            ];
            
            $result = $this->productModel->searchProducts($keyword, $filters, $page, $perPage);
            $categories = $this->categoryModel->getAll();

            $this->view('layouts/main', [
                'content' => 'product/index.php',
                'title' => $keyword ? 'Kết quả tìm kiếm: ' . htmlspecialchars($keyword) : 'Tất cả sản phẩm',
                'products' => $result['products'],
                'categories' => $categories,
                'keyword' => $keyword,
                'filters' => $filters,
                'currentPage' => $result['currentPage'],
                'totalPages' => $result['totalPages'],
                'total' => $result['total']
            ]);

        } catch (Exception $e) {
            error_log("Error in search: " . $e->getMessage());
            $this->view('layouts/main', [
                'content' => 'product/index.php',
                'title' => 'Lỗi tìm kiếm',
                'products' => [],
                'categories' => $this->categoryModel->getAll(),
                'error' => 'Đã xảy ra lỗi trong quá trình tìm kiếm',
                'currentPage' => 1,
                'totalPages' => 0,
                'total' => 0
            ]);
        }
    }
}
?> 