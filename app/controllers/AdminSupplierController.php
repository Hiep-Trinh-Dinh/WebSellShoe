<?php
require_once 'app/controllers/Admin/AdminController.php';

class AdminSupplierController extends \Admin\AdminController {
    private $supplierModel;

    public function __construct() {
        parent::__construct();
        $this->supplierModel = $this->loadModel('Supplier');
    }

    public function index() {
        // Lấy tham số page từ URL, mặc định là trang 1
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Đảm bảo page là giá trị hợp lệ
        if ($page < 1) {
            $page = 1;
        }
        
        // Lấy danh sách nhà cung cấp có phân trang
        $supplierData = $this->supplierModel->getAllWithPagination($page, 6);
        
        $this->view('admin/layouts/main', [
            'content' => 'admin/suppliers/index.php',
            'title' => 'Quản lý nhà cung cấp',
            'currentPage' => 'suppliers',
            'suppliers' => $supplierData['suppliers'],
            'pagination' => [
                'currentPage' => $supplierData['currentPage'],
                'totalPages' => $supplierData['totalPages'],
                'total' => $supplierData['total']
            ],
            'BASE_URL' => BASE_URL
        ]);
    }

    public function get($id) {
        // Tắt hiển thị lỗi PHP
        error_reporting(0);
        ini_set('display_errors', 0);
        
        // Xóa tất cả output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Đảm bảo không có session_start() hoặc các output khác
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        
        header('Content-Type: application/json');
        
        try {
            // Debug để xem giá trị ID
            error_log("Received ID in get method: " . $id);
            
            // Chuyển đổi ID thành số nguyên
            $id = intval($id);
            
            if ($id <= 0) {
                throw new Exception('ID không hợp lệ');
            }

            $supplier = $this->supplierModel->getById($id);
            error_log("Supplier data from getById: " . print_r($supplier, true));
            
            if ($supplier && !empty($supplier)) {
                echo json_encode([
                    'success' => true,
                    'supplier' => $supplier
                ]);
            } else {
                throw new Exception('Không tìm thấy nhà cung cấp');
            }
        } catch (Exception $e) {
            error_log("Error in get method: " . $e->getMessage());
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit();
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            
            try {
                if (empty($_POST['tenNCC']) || empty($_POST['email']) || empty($_POST['diaChi'])) {
                    throw new Exception('Vui lòng điền đầy đủ thông tin');
                }

                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Email không hợp lệ');
                }

                if ($this->supplierModel->add($_POST)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Thêm nhà cung cấp thành công'
                    ]);
                } else {
                    throw new Exception('Thêm nhà cung cấp thất bại');
                }
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            exit();
        }
    }

    public function update($id) {
        // Log the input parameter
        error_log("AdminSupplierController::update called with parameter: " . print_r($id, true));
        
        // Extract the ID if it's in an array
        if (is_array($id) && isset($id['id'])) {
            $id = $id['id'];
            error_log("Extracted ID from array: " . $id);
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Tắt hiển thị lỗi PHP
            error_reporting(0);
            ini_set('display_errors', 0);
            
            // Xóa tất cả output buffer
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            // Đảm bảo không có session_start() hoặc các output khác
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }
            
            header('Content-Type: application/json');
            
            try {
                // Check if ID is passed in POST data
                if (isset($_POST['id']) && !empty($_POST['id'])) {
                    // Use ID from POST data if available
                    $idFromPost = intval($_POST['id']);
                    error_log("Found ID in POST data: " . $idFromPost);
                    
                    // If both IDs are available, make sure they match
                    if ($id && $idFromPost != $id) {
                        error_log("Warning: ID mismatch - URL: " . $id . ", POST: " . $idFromPost);
                    }
                    
                    // Use POST ID as it should be correct
                    $id = $idFromPost;
                }
                
                // Convert ID to integer
                $id = intval($id);
                error_log("Update method - Final ID to use: " . $id);
                
                if ($id <= 0) {
                    throw new Exception('ID không hợp lệ');
                }

                // Validate input
                if (empty($_POST['tenNCC']) || empty($_POST['email']) || empty($_POST['diaChi'])) {
                    throw new Exception('Vui lòng điền đầy đủ thông tin');
                }

                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Email không hợp lệ');
                }

                $data = [
                    'tenNCC' => trim($_POST['tenNCC']),
                    'email' => trim($_POST['email']),
                    'diaChi' => trim($_POST['diaChi'])
                ];

                // Debug thông tin
                error_log("Updating supplier ID: " . $id);
                error_log("Update data: " . print_r($data, true));

                // Kiểm tra supplier có tồn tại không
                $existingSupplier = $this->supplierModel->getById($id);
                error_log("Existing supplier check result: " . print_r($existingSupplier, true));
                
                if (!$existingSupplier) {
                    throw new Exception('Không tìm thấy nhà cung cấp');
                }

                if ($this->supplierModel->update($id, $data)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Cập nhật nhà cung cấp thành công'
                    ]);
                } else {
                    throw new Exception('Cập nhật nhà cung cấp thất bại');
                }
            } catch (Exception $e) {
                error_log("Error in update method: " . $e->getMessage());
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            exit();
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/suppliers');
            exit();
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        
        try {
            if ($this->supplierModel->delete($id)) {
                $_SESSION['success'] = 'Xóa nhà cung cấp thành công';
            } else {
                $_SESSION['error'] = 'Không thể xóa nhà cung cấp này';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/admin/suppliers');
        exit();
    }
} 