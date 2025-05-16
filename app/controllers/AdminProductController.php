<?php
class AdminProductController extends BaseController {
    private $data;
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
        $this->generator();
    }

    public function index() {
        // Lấy tham số page từ URL, mặc định là trang 1
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Đảm bảo page là giá trị hợp lệ
        if ($page < 1) {
            $page = 1;
        }
        
        $this->generator($page);
        
        $this->view('admin/layouts/main', $this->data);
    }

    public function generator($page = 1)
    {
        // Lấy danh sách sản phẩm theo phân trang
        $productData = $this->productModel->getAllWithPagination($page, 6);
        $categories = $this->categoryModel->getAll();
        
        $this->data['content'] = 'admin/products/index.php';
        $this->data['title'] = 'Quản lý sản phẩm';
        $this->data['currentPage'] = 'products';
        $this->data['products'] = $productData['products'];
        $this->data['categories'] = $categories;
        $this->data['pagination'] = [
            'currentPage' => $productData['currentPage'],
            'totalPages' => $productData['totalPages'],
            'total' => $productData['total']
        ];
    }

    public function add()
    {
        $formData = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $formData['tenGiay'] = $_POST['tenGiay'];
            $formData['maLoaiGiay'] = $_POST['maLoaiGiay'];
            $formData['size'] = $_POST['size'];
            $formData['giaBan'] = $_POST['giaBan'];
            $formData['tonKho']= $_POST['tonKho'];
            $formData['hinhAnh'] = $_POST['hinhAnh'];
            if($formData['giaBan'] < 0)
            {
                echo "<script>
                        localStorage.setItem('showToast', 'error');
                        localStorage.setItem('toastMessage', 'Giá bán không được nhỏ hơn 0');
                        window.location.href = '" . BASE_URL . "/admin/products';
                    </script>";
                exit();
            }
            if($formData['tonKho'] < 0)
            {
                echo "<script>
                        localStorage.setItem('showToast', 'error');
                        localStorage.setItem('toastMessage', 'Tồn kho không được nhỏ hơn 0');
                        window.location.href = '" . BASE_URL . "/admin/products';
                    </script>";
                exit();
            }
            $isTenGiayExists = $this->productModel->isTenGiayExists($formData['tenGiay'], $formData['size']);
            if($isTenGiayExists)
            {
                echo "<script>
                        localStorage.setItem('showToast', 'error');
                        localStorage.setItem('toastMessage', 'Giày có size này đã tồn tại trong danh sách');
                        window.location.href = '" . BASE_URL . "/admin/products';
                    </script>";
                return;
            }
            $isAddProduct = $this->productModel->add($formData);
            if($isAddProduct)
            {
                echo "<script>
                        localStorage.setItem('showToast', 'success');
                        localStorage.setItem('toastMessage', 'Thêm thành công');
                        window.location.href = '" . BASE_URL . "/admin/products';
                    </script>";
                exit();
            }
            else
            {
                echo "<script>
                        localStorage.setItem('showToast', 'error');
                        localStorage.setItem('toastMessage', 'Thêm thất bại');
                        window.location.href = '" . BASE_URL . "/admin/products';
                    </script>";
                exit();
            }
        }
    }

    public function edit()
    {
        $formData = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $formData['maGiay'] = $_POST['maGiay'];
            $formData['tenGiay'] = $_POST['tenGiay'];
            $formData['maLoaiGiay'] = $_POST['maLoaiGiay'];
            $formData['size'] = $_POST['size'];
            $formData['giaBan'] = $_POST['giaBan'];
            $formData['tonKho']= $_POST['tonKho'];
            $formData['hinhAnh'] = $_POST['hinhAnhMoi'] ? $_POST['hinhAnhMoi'] : $_POST['hinhAnhCu'];
            $formData['trangThai']= $_POST['trangThai'];

            if($formData['giaBan'] < 0)
            {
                echo "<script>
                        localStorage.setItem('showToast', 'error');
                        localStorage.setItem('toastMessage', 'Giá bán không được nhỏ hơn 0');
                        window.location.href = '" . BASE_URL . "/admin/products';
                    </script>";
                exit();
            }
            if($formData['tonKho'] < 0)
            {
                echo "<script>
                        localStorage.setItem('showToast', 'error');
                        localStorage.setItem('toastMessage', 'Tồn kho không được nhỏ hơn 0');
                        window.location.href = '" . BASE_URL . "/admin/products';
                    </script>";
                exit();
            }

            $isEditProduct = $this->productModel->update($formData['maGiay'], $formData);
            if($isEditProduct)
            {
                echo "<script>
                        localStorage.setItem('showToast', 'success');
                        localStorage.setItem('toastMessage', 'Sửa thành công');
                        window.location.href = '" . BASE_URL . "/admin/products';
                    </script>";
                exit();
            }
            else
            {
                echo "<script>
                        localStorage.setItem('showToast', 'error');
                        localStorage.setItem('toastMessage', 'Sửa thất bại');
                        window.location.href = '" . BASE_URL . "/admin/products';
                    </script>";
                exit();
            }
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $formData['maGiay'] = $_POST['maGiay'];
            
            $result = $this->productModel->delete($formData['maGiay']);
            
            if($result['success'])
            {
                if($result['action'] == 'lock') {
                    echo "<script>
                            localStorage.setItem('showToast', 'success');
                            localStorage.setItem('toastMessage', 'Sản phẩm đã được khóa vì đã tồn tại trong đơn hàng');
                            window.location.href = '" . BASE_URL . "/admin/products';
                        </script>";
                } else {
                    echo "<script>
                            localStorage.setItem('showToast', 'success');
                            localStorage.setItem('toastMessage', 'Sản phẩm đã được xóa hoàn toàn');
                            window.location.href = '" . BASE_URL . "/admin/products';
                        </script>";
                }
                exit();
            } else {
                echo "<script>
                        localStorage.setItem('showToast', 'error');
                        localStorage.setItem('toastMessage', '" . $result['message'] . "');
                        window.location.href = '" . BASE_URL . "/admin/products';
                    </script>";
                exit();
            }
        }
    }

    public function unlock()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $formData['maGiay'] = $_POST['maGiay'];
            
            $isUnlockProduct = $this->productModel->unlock($formData['maGiay']);
            if($isUnlockProduct)
            {
                echo "<script>
                        localStorage.setItem('showToast', 'success');
                        localStorage.setItem('toastMessage', 'Mở khóa thành công');
                        window.location.href = '" . BASE_URL . "/admin/products';
                    </script>";
                exit();
            }

        }
    }

} 