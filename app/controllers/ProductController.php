<?php
class ProductController extends BaseController {
    private $data;
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
        try {
            // Debug
            error_log("Detail method called with params: " . print_r($params, true));
            
            // Lấy ID sản phẩm từ params
            $id = isset($params[0]) ? $params[0] : null;
            
            if (!$id) {
                throw new Exception('Product ID not provided');
            }

            // Debug
            error_log("Fetching product with ID: " . $id);
            
            $product = $this->productModel->getProductById($id);
            
            if (!$product) {
                throw new Exception('Product not found');
            }

            // Debug
            error_log("Product found: " . print_r($product->toArray(), true));

            // Lấy loại giày của sản phẩm
            $category = $this->categoryModel->getById($product->getMaLoaiGiay());
            if ($category) {
                $product->setTenLoaiGiay($category['tenLoaiGiay']);
            }

            // Lấy sản phẩm liên quan
            $relatedProducts = $this->productModel->getRelatedProducts(
                $product->getMaLoaiGiay(),
                $product->getMaGiay()
            );

            $this->view('layouts/main', [
                'content' => 'product/detail.php',
                'title' => $product->getTenGiay(),
                'product' => $product,
                'relatedProducts' => $relatedProducts
            ]);
            
        } catch (Exception $e) {
            // Log error
            error_log("Error in ProductController::detail - " . $e->getMessage());
            
            // Redirect to products page with error message
            $_SESSION['error'] = 'Không tìm thấy sản phẩm';
            header('Location: ' . BASE_URL . '/products');
            exit();
        }
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
            $perPage = 6;
            
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
                'currentPage' => $page,
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