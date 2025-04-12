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
        $formDataHD = [];
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData, true);
            
        
            if (isset($data["cartItems"])) 
            {
                $cartItems = $data["cartItems"];
                
                $formDataHD['ngayTao'] = $data['date'];
                $formDataHD['tongSoLuong'] = $data['totalQuantity'];
                $formDataHD['tongTien'] = $data['total'];
                $formDataHD['maTK'] = $data['maTK'];
                $formDataHD['trangThai'] = 1;
                $formDataHD['thanhToan'] = $data['thanhToan'];
                $formDataHD['diaChi'] = $data['diaChi'];

                $maHD = $this->orderModel->createHD($formDataHD);

                if($maHD)
                {
                    foreach ($cartItems as $item) 
                    {
                        $formDataCTHD = [];
                        $product = json_decode($item["product"], true);
                        $quantity = $item["quantity"];
            
                        $formDataCTHD['maHD'] = $maHD;
                        $formDataCTHD['maGiay'] = $product['maGiay']; 
                        $formDataCTHD['size'] = $product['size']; 
                        $formDataCTHD['giaBan'] = $product['giaBan'];
                        $formDataCTHD['soLuong'] = $quantity;
                        $formDataCTHD['thanhTien'] = $product['giaBan'] * $quantity;
                        $this->productModel->decreaseStock($product['maGiay'], $quantity);
                        $this->orderModel->createCTHD($formDataCTHD);
                    }
                    
                    echo json_encode([
                        'success' => true
                    ]);
                }
                else 
                {
                    echo json_encode([
                        'success' => false
                    ]);
                }
                
            } 
            else 
            {
                echo "Không có dữ liệu giỏ hàng!";
            }
        }
    }
}
?> 