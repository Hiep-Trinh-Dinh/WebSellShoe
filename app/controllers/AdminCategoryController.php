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
        $this->view('admin/layouts/main', $this->data);
    }

    public function generator()
    {
        $categories = $this->categoryModel->getAll();
        $this->data['content'] = 'admin/categories/index.php';
        $this->data['title'] = 'Quản lý loại giày';
        $this->data['currentPage'] = 'categories';
        $this->data['categories'] = $categories;
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
} 