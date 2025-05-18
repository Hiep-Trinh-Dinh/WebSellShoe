<?php
class CartController extends BaseController {
    private $cartItems;
    private $productModel;
    private $orderModel;


    public function __construct() {
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->cartItems = [];
    }



    public function index() {

        $this->view('layouts/main', [
            'content' => 'cart/index.php',
            'title' => 'Giỏ hàng - Shop Giày'
        ]);
    }


    public function add() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $productId = $_POST['productId'];
            $quantity = $_POST['quantity'];


            $product = $this->productModel->getProductByIdArray($productId);
            if($product)
            {
                $product = json_encode($product);
                $cartItem = [
                    'product' => $product,
                    'quantity' => $quantity
                ];

                echo json_encode([
                    'success' => true,
                    'cartItem' => $cartItem
                    
                ]);
                return;
            }
            else 
            {
                echo json_encode([
                    'success' => false,
                    'message' => "Có lỗi xảy ra"
                ]);
                return;
            }
        }
    }

    public function pay() {
        // Thiết lập header để đảm bảo phản hồi được nhận dạng là JSON
        header('Content-Type: application/json; charset=utf-8');
        
        $formDataHD = [];
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData, true);
            
            if (isset($data["cartItems"])) 
            {
                $cartItems = $data["cartItems"];
                
                // Lấy thông tin người dùng từ database thay vì từ form
                $userModel = $this->loadModel('User');
                $user = $userModel->getById($data['maTK']);
                
                if (!$user) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Không tìm thấy thông tin người dùng'
                    ]);
                    return;
                }
                
                // Kiểm tra xem người dùng đã cập nhật thông tin chưa
                if (empty($user->getSoDienThoai()) || empty($user->getDiaChi())) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Vui lòng cập nhật thông tin cá nhân trước khi thanh toán'
                    ]);
                    return;
                }
                
                $formDataHD['ngayTao'] = $data['date'];
                $formDataHD['tongSoLuong'] = $data['totalQuantity'];
                $formDataHD['tongTien'] = $data['total'];
                $formDataHD['maTK'] = $data['maTK'];
                $formDataHD['trangThai'] = 1;
                $formDataHD['thanhToan'] = $data['thanhToan'];
                $formDataHD['diaChi'] = $user->getDiaChi();
                $formDataHD['soDienThoai'] = $user->getSoDienThoai();

                // Tạo đơn hàng mới
                $orderModel = $this->loadModel('Order');
                $maHoaDon = $orderModel->createHD($formDataHD);

                if ($maHoaDon) 
                {
                    // Thêm sản phẩm vào chi tiết đơn hàng
                    $allProductsAdded = true;
                    $productModel = $this->loadModel('Product');

                    foreach ($cartItems as $item) 
                    {
                        $formDataCTHD = [];
                        $product = json_decode($item["product"], true);
                        $quantity = $item["quantity"];

                        $formDataCTHD['maHD'] = $maHoaDon;
                        $formDataCTHD['maGiay'] = $product['maGiay']; 
                        $formDataCTHD['size'] = $product['size'] ?? ''; 
                        $formDataCTHD['giaBan'] = $product['giaBan'];
                        $formDataCTHD['soLuong'] = $quantity;
                        $formDataCTHD['thanhTien'] = $product['giaBan'] * $quantity;

                        // Thêm vào bảng chi tiết hóa đơn
                        if (!$orderModel->createCTHD($formDataCTHD)) 
                        {
                            $allProductsAdded = false;
                        }

                        // Cập nhật số lượng sản phẩm
                        $productModel->decreaseStock($product['maGiay'], $quantity);
                    }

                    if ($allProductsAdded) 
                    {
                        echo json_encode([
                            'success' => true,
                            'maHoaDon' => $maHoaDon
                        ]);
                    } 
                    else 
                    {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Có lỗi khi thêm sản phẩm vào đơn hàng'
                        ]);
                    }
                } 
                else 
                {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Có lỗi khi tạo đơn hàng'
                    ]);
                }
            } 
            else 
            {
                echo json_encode([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ'
                ]);
            }
        } 
        else 
        {
            echo json_encode([
                'success' => false,
                'message' => 'Yêu cầu không hợp lệ'
            ]);
        }
    }
}
?> 