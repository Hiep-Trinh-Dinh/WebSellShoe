<?php
class CartController extends BaseController {
    public function index() {
        $this->view('layouts/main', [
            'content' => 'cart/index.php',
            'title' => 'Giỏ hàng - Shop Giày'
        ]);
    }

    public function add() {
        // Xử lý thêm vào giỏ hàng
    }
}
?> 