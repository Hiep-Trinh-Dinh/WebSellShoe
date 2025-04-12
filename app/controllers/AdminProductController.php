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
        
        // Debug để kiểm tra dữ liệu
        // echo '<pre>'; print_r($products); echo '</pre>';
        
        $this->view('admin/layouts/main', $this->data);
    }

    public function generator()
    {
        $products = $this->productModel->getAll();
        $categories = $this->categoryModel->getAll();
        $this->data['content'] = 'admin/products/index.php';
        $this->data['title'] = 'Quản lý sản phẩm';
        $this->data['currentPage'] = 'products';
        $this->data['products'] = $products;
        $this->data['categories'] = $categories;
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
            
            $isDeleteProduct = $this->productModel->delete($formData['maGiay']);
            if($isDeleteProduct)
            {
                echo "<script>
                        localStorage.setItem('showToast', 'success');
                        localStorage.setItem('toastMessage', 'Khóa thành công');
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