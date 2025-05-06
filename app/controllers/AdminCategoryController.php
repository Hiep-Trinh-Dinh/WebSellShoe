<?php
class AdminCategoryController extends BaseController {
    private $data = [];
    private $categoryModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
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
        // Lấy danh sách loại giày theo phân trang
        $categoryData = $this->categoryModel->getAllWithPagination($page, 6);
        
        $this->data['content'] = 'admin/categories/index.php';
        $this->data['title'] = 'Quản lý loại giày';
        $this->data['currentPage'] = 'categories';
        $this->data['categories'] = $categoryData['categories'];
        $this->data['pagination'] = [
            'currentPage' => $categoryData['currentPage'],
            'totalPages' => $categoryData['totalPages'],
            'total' => $categoryData['total']
        ];
    }

    // Thêm các methods add, edit và delete
    public function add()
    {
        $formData = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $formData['tenLoaiGiay'] = $_POST['tenLoaiGiay'];
            
            $isAddCategory = $this->categoryModel->add($formData);
            if($isAddCategory)
            {
                echo "<script>
                        localStorage.setItem('showToast', 'success');
                        localStorage.setItem('toastMessage', 'Thêm thành công');
                        window.location.href = '" . BASE_URL . "/admin/categories';
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
            $formData['maLoaiGiay'] = $_POST['maLoaiGiay'];
            $formData['tenLoaiGiay'] = $_POST['tenLoaiGiay'];
            $formData['trangThai'] = $_POST['trangThai'];
            
            $isEditCategory = $this->categoryModel->update($formData['maLoaiGiay'], $formData);
            if($isEditCategory)
            {
                echo "<script>
                        localStorage.setItem('showToast', 'success');
                        localStorage.setItem('toastMessage', 'Sửa thành công');
                        window.location.href = '" . BASE_URL . "/admin/categories';
                    </script>";
                exit();
            }

        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $formData['maLoaiGiay'] = $_POST['maLoaiGiay'];
            
            $isDeleteCategory = $this->categoryModel->delete($formData['maLoaiGiay']);
            if($isDeleteCategory)
            {
                echo "<script>
                        localStorage.setItem('showToast', 'success');
                        localStorage.setItem('toastMessage', 'Khóa thành công');
                        window.location.href = '" . BASE_URL . "/admin/categories';
                    </script>";
                exit();
            }
        }
    }

    public function unlock()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $formData['maLoaiGiay'] = $_POST['maLoaiGiay'];
            
            $isUnlockCategory = $this->categoryModel->unlock($formData['maLoaiGiay']);
            if($isUnlockCategory)
            {
                echo "<script>
                        localStorage.setItem('showToast', 'success');
                        localStorage.setItem('toastMessage', 'Mở khóa thành công');
                        window.location.href = '" . BASE_URL . "/admin/categories';
                    </script>";
                exit();
            }
        }
    }
} 